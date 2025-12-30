<?php

namespace App\Http\Controllers;

use App\Models\User; // Added User model path
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WarehouseSetting;
use App\Models\ApiCall; 
use App\Models\ApiProvider; 
use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    private const SHIPBUBBLE_IDENTIFIER = 'SHIPBUBBLE';
    private const PAYSTACK_IDENTIFIER = 'PAYSTACK';

    public function index()
    {
        $user = Auth::user();
        $cart = CartItem::where('user_id', $user->id)->with('product')->get();
        $address = $user->addresses()->latest()->first();

        return view('pages.checkout.addresses', compact('cart', 'address', 'user'));
    }

    public function saveAddress(Request $request)
    {
        $request->validate([
            'street'      => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'state'       => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20', // Changed to nullable as per your form placeholder
            'country'     => 'required|string|max:100',
            'phonenumber' => 'required|string|min:10',
        ]);

        // Create the address for the authenticated user
        $address = Auth::user()->addresses()->create([
            'street'       => $request->street,
            'city'         => $request->city,
            'state'        => $request->state,
            'postal_code'  => $request->postal_code ?? '000000', // Default if empty
            'country'      => $request->country,
            'phonenumber'  => $request->phonenumber,
            'address_code' => null, // Ensure this is null so summary() triggers ShipBubble validation
        ]);

        // Redirect to summary page with the newly created address ID
        return redirect()->route('checkout.summary', ['address' => $address->id])
                        ->with('success', 'Address saved successfully!');
    }

    /**
     * STEP 2 — Summary + Calculate Shipping
     */
    public function summary(Request $request)
    {
        $user = Auth::user();
        $cart = CartItem::where('user_id', $user->id)->with('product')->get();
        
        try {
            $address = $user->addresses()->findOrFail($request->address);
        } catch (\Exception $e) {
            return back()->with('error', 'Shipping address not found.');
        }

        $subtotal = $cart->sum(fn ($i) => $i->qty * $i->product->price);
        $tax = 0;

        // Check if address already has a code
        $receiverCode = $address->address_code;

        if (!$receiverCode) {
            $validationPayload = [
                'name' => $this->normalizeName($user->name),
                'email' => $user->email,
                'phone' => $address->phonenumber,
                'address' => "{$address->street}, {$address->city}, {$address->state}, {$address->country}",
                'latitude' => 0, 'longitude' => 0,
            ];

            $receiverResponse = $this->makeApiCall('POST', 'shipping/address/validate', $validationPayload, 'ShipBubble Validation', self::SHIPBUBBLE_IDENTIFIER);

            if ($receiverResponse instanceof RedirectResponse) return $receiverResponse;

            $receiverCode = $receiverResponse->json('data.address_code');
            if (!$receiverCode) return back()->with('error', 'ShipBubble failed to return a valid receiver address code.');

            $address->update(['address_code' => $receiverCode]);
        }

        // Call ShipBubble Rates API
        $admin = User::where('is_admin', true)->first();
        $warehouse = WarehouseSetting::where('user_id', $admin->id)->first();
        
        if (!$warehouse || !$warehouse->address_code) {
            return back()->with('error', 'Warehouse address is not configured.');
        }
        
        $packageItems = $cart->map(fn ($item) => [
            "name" => $item->product->name,
            "description" => $item->product->description ?? "Item",
            "unit_weight" => (string) $item->product->weight,
            "unit_amount" => (string) $item->product->price,
            "quantity" => (string) $item->qty
        ])->toArray();

        $ratesPayload = [
            "sender_address_code" => (int)$warehouse->address_code,
            "reciever_address_code" => (int)$receiverCode,
            "pickup_date" => now()->addDay()->format("Y-m-d"),
            "category_id" => $this->getElectronicsCategoryId(),
            "package_items" => $packageItems,
            "package_dimension" => [ "length" => 10, "width" => 10, "height" => 10 ]
        ];
        
        $rateResponse = $this->makeApiCall('POST', 'shipping/fetch_rates', $ratesPayload, 'ShipBubble Rates Fetch', self::SHIPBUBBLE_IDENTIFIER);

        if ($rateResponse instanceof RedirectResponse) return $rateResponse;

        $couriers = $rateResponse->json('data.couriers') ?? [];
        $requestToken = $rateResponse->json('data.request_token');

        // --- NEW: Cache the rates and token for this user ---
        Cache::put("checkout_rates_{$user->id}", [
            'couriers' => $couriers,
            'request_token' => $requestToken
        ], now()->addMinutes(20));

        $shipping = count($couriers) ? collect($couriers)->min('total') : 0;
        $grandTotal = $subtotal + $tax + $shipping;

        return view('pages.checkout.summary', compact(
            'cart', 'address', 'subtotal', 'tax', 'shipping', 'grandTotal', 'couriers', 'user'
        ));
    }

    /**
     * Refactored makeApiCall using config(services)
     */
    protected function makeApiCall(
        string $method, 
        string $endpoint, 
        array $data, 
        string $logTag,
        string $providerIdentifier,
        ?int $orderId = null
    ): Response|RedirectResponse {
        
        // Dynamic config resolution
        $configKey = strtolower($providerIdentifier); // 'shipbubble' or 'paystack'
        $baseUrl = config("services.{$configKey}.base_url");
        $apiKey = ($providerIdentifier === self::PAYSTACK_IDENTIFIER) 
            ? config('services.paystack.key') 
            : config('services.shipbubble.key');

        if (!$baseUrl || !$apiKey) {
            Log::error("Missing API Configuration for {$providerIdentifier}");
            return back()->with('error', "Configuration Error: API settings missing for {$logTag}.");
        }

        $fullUrl = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');
        
        try {
            $response = Http::withToken($apiKey)->{$method}($fullUrl, $data);
            
            Log::info("{$logTag} Response", ['status' => $response->status(), 'body' => $response->json()]);

            if ($response->failed()) {
                $errorDetails = $response->json('message') ?? $response->reason();
                return back()->with('error', "API Error ({$logTag}): {$errorDetails}");
            }
            
            if (isset($response['status']) && $response['status'] === false) {
                 $errorMessage = $response->json('message') ?? 'API logic failed.';
                 return back()->with('error', "API Logic Error ({$logTag}): {$errorMessage}");
            }

            // Record API Call
            $providerId = $this->getApiProviderId($providerIdentifier);
            if ($providerId) {
                 $this->recordApiCallCost($providerId, $orderId, $endpoint);
            }

            return $response;

        } catch (\Exception $e) {
            Log::error("{$logTag} Exception", ['exception' => $e->getMessage()]);
            return back()->with('error', "Connection error during {$logTag}.");
        }
    }

    /**
     * Retrieves the API Provider ID, using cache for performance.
     */
    protected function getApiProviderId(string $identifier): ?int
    {
        $cacheKey = "api_provider_{$identifier}_id";

        return Cache::rememberForever($cacheKey, function () use ($identifier) {
            $provider = ApiProvider::where('identifier', $identifier)->first();
            
            if (!$provider) {
                Log::error("API Provider not found in database.", ['identifier' => $identifier]);
            }

            return $provider->id ?? null;
        });
    }

    /**
     * Helper to record API cost into the database (transaction logging).
     * This creates a new record for every call.
     */
    private function recordApiCallCost(int $apiProviderId, ?int $orderId, string $endpoint, int $count = 1)
    {
        try {
            // Fetch provider cost per call
            $provider = ApiProvider::findOrFail($apiProviderId);
            
            // Calculate total cost (cost_per_call * count)
            $cost = bcmul((string)$provider->cost_per_call, (string)$count, 4);

            ApiCall::create([
                'api_provider_id' => $apiProviderId,
                'order_id' => $orderId,
                'endpoint' => $endpoint,
                'count' => $count,
                'cost_cached' => $cost,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('API Provider not found when attempting to log cost during runtime.', ['provider_id' => $apiProviderId, 'endpoint' => $endpoint]);
        } catch (\Exception $e) {
            Log::error('Error recording API cost.', ['exception' => $e->getMessage(), 'provider_id' => $apiProviderId, 'endpoint' => $endpoint]);
        }
    }


    /**
     * STEP 3 — INITIATE PAYSTACK PAYMENT
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'courier_code' => 'required|string'
        ]);

        $user = Auth::user();
        
        // --- NEW: Retrieve data from Cache instead of API ---
        $cachedData = Cache::get("checkout_rates_{$user->id}");

        if (!$cachedData) {
            return back()->with('error', 'Your shipping rates have expired. Please refresh the summary page to get updated pricing.');
        }

        $couriers = $cachedData['couriers'];
        $requestToken = $cachedData['request_token'];

        // Find the selected courier in the cached list
        $selectedCourier = collect($couriers)->firstWhere('service_code', $request->courier_code);

        if (!$selectedCourier) {
            return back()->with('error', 'The selected courier is no longer available. Please try refreshing the summary.');
        }

        // Pre-flight checks
        $cart = CartItem::where('user_id', $user->id)->with('product')->get();
        if ($cart->isEmpty()) return redirect()->route('cart.index')->with('error', 'Your cart is empty.');

        try {
            $address = $user->addresses()->findOrFail($request->address_id);
        } catch (\Exception $e) {
            return back()->with('error', 'Address error: ' . $e->getMessage());
        }

        $items_total = $cart->sum(fn($item) => $item->qty * $item->product->price);
        $shipping_amount = $selectedCourier['total'];

        // --- STEP 1: CREATE LOCAL ORDER ---
        $order = Order::create([
            'user_id' => $user->id,
            'shipping_amount' => $shipping_amount,
            'items_total' => $items_total,
            'grand_total' => $items_total + $shipping_amount,
            'payment_status' => 'pending_payment',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $address->phonenumber,
            'address_line' => $address->street,
            'city' => $address->city,
            'state' => $address->state,
            'request_token' => $requestToken, 
        ]);

        // --- STEP 2: INITIATE PAYMENT (PAYSTACK) ---
        $paystackData = [
            'email' => $order->customer_email,
            'amount' => (int)($order->grand_total * 100),
            'reference' => 'BELLI-' . time() . '-' . $order->id,
            'callback_url' => route('checkout.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'courier_code' => $request->courier_code,
                'service_code' => $selectedCourier['service_code'], 
            ],
        ];

        $paystackResponse = $this->makeApiCall(
            'POST', 
            'transaction/initialize', 
            $paystackData, 
            'Paystack Initialize',
            self::PAYSTACK_IDENTIFIER,
            $order->id
        );

        if ($paystackResponse instanceof RedirectResponse) {
            $order->delete();
            return $paystackResponse;
        }

        // Optional: Clear rates cache now that order is created
        Cache::forget("checkout_rates_{$user->id}");

        return redirect($paystackResponse->json('data.authorization_url'));
    }


    /**
     * STEP 4 — PAYSTACK CALLBACK (CREATE ORDER + SHIPBUBBLE LABEL)
     */
    public function callback(Request $request)
    {
        $reference = $request->reference;
        if (empty($reference)) {
            return redirect()->route('cart.index')->with('error', 'No payment reference provided.');
        }

        // --- 1. VERIFY PAYMENT (PAYSTACK) ---
        $verifyResponse = $this->makeApiCall(
            'GET', 
            "transaction/verify/{$reference}", 
            [], 
            'Paystack Verification',
            self::PAYSTACK_IDENTIFIER
        );

        if ($verifyResponse instanceof RedirectResponse) return $verifyResponse;

        $data = $verifyResponse->json('data');
        
        try {
            $orderId = $data['metadata']['order_id'];
            $order = Order::findOrFail($orderId);
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Order not found.');
        }

        // --- 2. UPDATE ORDER & STOCK ---
        $order->update([
            'payment_status' => 'paid',
            'payment_reference' => $reference,
            'order_status' => 'processing',
        ]);

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $item->product->price,
                'total' => $item->qty * $item->product->price
            ]);
            
            $item->product->decrement('stock', $item->qty);
        }
        CartItem::where('user_id', Auth::id())->delete();

        // --- 3. CREATE SHIPPING LABEL (SHIPBUBBLE) ---
        $shippingPayload = [
            "request_token" => $order->request_token,
            "courier_id"    => $data['metadata']['courier_code'],
            "service_code"  => $data['metadata']['service_code'], 
        ];

        $shipResponse = $this->makeApiCall(
            'POST', 
            'shipping/labels', 
            $shippingPayload, 
            'ShipBubble Label Create',
            self::SHIPBUBBLE_IDENTIFIER,
            $order->id
        );

        if ($shipResponse instanceof RedirectResponse) {
            return redirect()->route('checkout.success')->with('warning', 'Payment successful, but label creation failed. Please contact support.');
        }

        $order->update([
            'tracking_code' => $shipResponse->json('data.order_id'),
            'tracking_url'  => $shipResponse->json('data.tracking_url'),
        ]);

        return view('pages.checkout.success', compact('order'));
    }


    /**
     * STEP 5 — TRACK ORDER
     */
    public function track(Order $order)
    {
        return view('pages.users.orders.track', compact('order'));
    }

    function normalizeName($name)
    {
        $words = preg_split('/\s+/', trim($name));

        if (count($words) < 2) {
            return $name . ' BelliTek';
        }

        return $name;
    }

    protected function getElectronicsCategoryId(): ?int 
    {
        return Cache::remember('shipbubble_electronics_category_id', 604800, function () {
            $response = $this->makeApiCall(
                'GET', 
                'shipping/labels/categories', 
                [], 
                'ShipBubble Categories Fetch',
                self::SHIPBUBBLE_IDENTIFIER
            );

            if ($response instanceof RedirectResponse) return null;
            
            $category = collect($response->json('data') ?? [])
                ->firstWhere('category', 'Electronics and gadgets');

            return $category['category_id'] ?? null;
        });
    }

}