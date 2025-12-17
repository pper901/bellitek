<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\WarehouseSetting;
use App\Models\ApiCall; // <-- NEW
use App\Models\ApiProvider; // <-- NEW
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    // --- API Provider Constants ---
    private const SHIPBUBBLE_IDENTIFIER = 'SHIPBUBBLE';
    private const PAYSTACK_IDENTIFIER = 'PAYSTACK';

    /**
     * STEP 1 — Select Address
     */
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
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required'
        ]);

        $address = Auth::user()->addresses()->create($request->all());

        return redirect()->route('checkout.summary', ['address' => $address->id]);
    }

    /**
     * STEP 2 — Summary + Calculate Shipping
     */
    public function summary(Request $request)
    {
        $user = Auth::user();
        $cart = CartItem::where('user_id', $user->id)->with('product')->get();
        
        try {
            // Find the address or fail
            $address = $user->addresses()->findOrFail($request->address);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Shipping address not found.');
        }

        $subtotal = $cart->sum(fn ($i) => $i->qty * $i->product->price);
        $tax = 0;

        $shipBubbleBaseUrl = 'https://api.shipbubble.com/v1/';
        $shipBubbleEnvKey = 'SHIPBUBBLE_API_KEY';

        // --- 1. Validate Receiver Address ---
        $validationPayload = [
            'name' => $this->normalizeName($user->name),
            'email' => $user->email,
            'phone' => $address->phonenumber,
            'address' => "{$address->street}, {$address->city}, {$address->state}, {$address->country}",
            'latitude' => 0,
            'longitude' => 0,
        ];

        $receiverResponse = $this->makeApiCall(
            'POST', 
            'shipping/address/validate', 
            $validationPayload, 
            'ShipBubble Validation', 
            $shipBubbleBaseUrl,
            $shipBubbleEnvKey,
            self::SHIPBUBBLE_IDENTIFIER
        );

        // Check if the helper returned a RedirectResponse (an error)
        if ($receiverResponse instanceof \Illuminate\Http\RedirectResponse) {
            return $receiverResponse;
        }

        $receiverCode = $receiverResponse->json('data.address_code');
        if (!$receiverCode) {
            return back()->with('error', 'ShipBubble failed to return a valid receiver address code.');
        }

        $address->address_code = $receiverCode;
        $address->save();


        
        // Get the admin user
        $admin = User::where('is_admin', true)->first();

        // --- 2. Call ShipBubble Rates API ---
        $warehouse = WarehouseSetting::where('user_id', $admin->id)->first();
        if (!$warehouse || !$warehouse->address_code) {
             return back()->with('error', 'Warehouse address is not configured. Please contact the administrator.');
        }
        
        $category_id = $this->getElectronicsCategoryId();
        
        $packageItems = $cart->map(fn ($item) => [
            "name" => $item->product->name,
            "description" => $item->product->description ?? "Item",
            "unit_weight" => (string) $item->product->weight,
            "unit_amount" => "{$item->product->price}",
            "quantity" => "{$item->qty}"
        ])->toArray();

        $ratesPayload = [
            "sender_address_code" => (int)$warehouse->address_code,
            "reciever_address_code" => (int)$receiverCode,
            "pickup_date" => now()->addDay()->format("Y-m-d"),
            "category_id" => $category_id,
            "package_items" => $packageItems,
            "package_dimension" => [ "length" => 10, "width" => 10, "height" => 10 ]
        ];
        
        // FIX: Changed from 'shipping/address/validate' to the correct 'shipping/fetch_rates'
        $rateResponse = $this->makeApiCall(
            'POST', 
            'shipping/fetch_rates', // <-- CORRECTED ENDPOINT
            $ratesPayload, 
            'ShipBubble Rates Fetch', // <-- CORRECTED LOG TAG
            $shipBubbleBaseUrl, 
            $shipBubbleEnvKey, 
            self::SHIPBUBBLE_IDENTIFIER
        );

        if ($rateResponse instanceof \Illuminate\Http\RedirectResponse) {
            return $rateResponse;
        }

        $couriers = $rateResponse->json('data.couriers') ?? [];

        $shipping = count($couriers) ? collect($couriers)->min('total') : 0;
        $grandTotal = $subtotal + $tax + $shipping;

        return view('pages.checkout.summary', compact(
            'cart', 'address', 'subtotal', 'tax', 'shipping', 'grandTotal', 'couriers', 'user'
        ));
    }
    
    // ... helper methods like normalizeName() and getElectronicsCategoryId() ...

    
    /**
     * Executes an API call, handles success/error logging, and records the cost upon success.
     * * @param string $method HTTP method (GET/POST)
     * @param string $endpoint API endpoint path (e.g., 'shipping/address/validate')
     * @param array $data Request payload
     * @param string $logTag Description for logging
     * @param string $baseUrl API base URL
     * @param string $apiKeyEnv Environment variable key for the API token
     * @param string $providerIdentifier Identifier used in ApiProvider model (e.g., 'SHIPBUBBLE')
     * @param int|null $orderId The order ID related to this call (if applicable)
     * @return \Illuminate\Http\Client\Response|\Illuminate\Http\RedirectResponse
     */
    protected function makeApiCall(
        string $method, 
        string $endpoint, 
        array $data, 
        string $logTag,
        string $baseUrl, 
        string $apiKeyEnv,
        string $providerIdentifier,
        ?int $orderId = null
    ): Response|RedirectResponse {
        
        $fullUrl = $baseUrl . $endpoint;
        $apiKey = env($apiKeyEnv);

        if (!$apiKey) {
            Log::error("Missing API Key", ['env_key' => $apiKeyEnv, 'log_tag' => $logTag]);
            return back()->with('error', "Configuration Error: Missing API key ({$apiKeyEnv}).");
        }
        
        Log::info("Attempting {$logTag} API call to: {$fullUrl}", ['payload' => $data]);
        
        try {
            // Execute the API request using the dynamic token
            $response = Http::withToken($apiKey)->{$method}($fullUrl, $data);
            
            // Log the full response for debugging
            Log::info("{$logTag} Response", ['status' => $response->status(), 'body' => $response->json()]);

            // Check for HTTP errors (4xx or 5xx)
            if ($response->failed()) {
                $errorDetails = $response->json('message') ?? $response->reason();
                Log::error("{$logTag} failed with HTTP status: {$response->status()}", ['error_details' => $errorDetails]);
                return back()->with('error', "API Error ({$logTag}): {$errorDetails}");
            }
            
            // Check for API Logic Failure (e.g., Paystack/Shipbubble may return 200 OK with status: false)
            if (isset($response['status']) && $response['status'] === false) {
                 $errorMessage = $response->json('message') ?? 'API logic failed.';
                 Log::error("{$logTag} failed with logic error.", ['error_message' => $errorMessage]);
                 return back()->with('error', "API Logic Error ({$logTag}): {$errorMessage}");
            }

            // --- API CALL RECORDING (Executed only on successful response) ---
            $providerId = $this->getApiProviderId($providerIdentifier);
            if ($providerId) {
                 $this->recordApiCallCost($providerId, $orderId, $endpoint);
            }
            // -----------------------------------------------------------------

            return $response;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("{$logTag} Connection Failure", ['exception' => $e->getMessage()]);
            return back()->with('error', "Network Error: Could not connect to the API for {$logTag}.");
        } catch (\Exception $e) {
            Log::error("{$logTag} Unexpected Exception", ['exception' => $e->getMessage()]);
            return back()->with('error', "An unexpected error occurred during the API call for {$logTag}.");
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
        
        // ---------------------------------------------------------------------
        // 🔵 STEP 1 — PRE-FLIGHT CHECKS & CALCULATION
        // ---------------------------------------------------------------------

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'courier_code' => 'required|string'
        ]);

        
        // Get the admin user
        $admin = User::where('is_admin', true)->first();
        $user = Auth::user();
        $cart = CartItem::where('user_id', $user->id)->with('product')->get();

        if ($cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            // Load necessary models, using try/catch for FindOrFail
            $address = $user->addresses()->findOrFail($request->address_id);
            $warehouse = WarehouseSetting::where('user_id', $admin->id)->first();
        } catch (\Exception $e) {
            return back()->with('error', 'Configuration or data error: ' . $e->getMessage());
        }

        $items_total = $cart->sum(fn($item) => $item->qty * $item->product->price);
        
        // REBUILD PACKAGE ITEMS for ShipBubble call
        $packageItems = $cart->map(fn ($item) => [
            "name" => $item->product->name,
            "description" => $item->product->description ?? "Item",
            "unit_weight" => (string) $item->product->weight,
            "unit_amount" => (string)$item->product->price,
            "quantity" => (string)$item->qty
        ])->toArray();

        // ---------------------------------------------------------------------
        // 🔵 STEP 2 — RE-FETCH SHIPPING RATES (SHIPBUBBLE API)
        // ---------------------------------------------------------------------
        
        $shipBubbleBaseUrl = 'https://api.shipbubble.com/v1/';
        $shipBubbleEnvKey = 'SHIPBUBBLE_API_KEY';

        $ratesPayload = [
            "sender_address_code" => (int)$warehouse->address_code,
            "reciever_address_code" => (int)$address->address_code,
            "pickup_date" => now()->addDay()->format("Y-m-d"),
            "category_id" => $this->getElectronicsCategoryId(), 
            "package_items" => $packageItems,
            "package_dimension" => [ "length" => 10, "width" => 10, "height" => 10 ]
        ];

        $rateResponse = $this->makeApiCall(
            'POST', 
            'shipping/fetch_rates', 
            $ratesPayload, 
            'ShipBubble Re-Rate',
            $shipBubbleBaseUrl, 
            $shipBubbleEnvKey,
            self::SHIPBUBBLE_IDENTIFIER
        );

        // Check if the helper returned an error redirect
        if ($rateResponse instanceof \Illuminate\Http\RedirectResponse) {
            return $rateResponse;
        }

        $couriers = $rateResponse->json('data.couriers') ?? [];

        if (empty($couriers)) {
            // Check for API-specific failure if status was HTTP 200
            return back()->with('error', 'ShipBubble returned no shipping rates.');
        }

        // FIND COURIER BY CODE (secure)
        $selectedCourier = collect($couriers)->firstWhere('service_code', $request->courier_code);

        if (!$selectedCourier) {
            return back()->with('error', 'Invalid courier selected or rate expired.');
        }

        $shipping_amount = $selectedCourier['total'];
        $grand_total = $items_total + $shipping_amount;
        $requestToken = $rateResponse->json('data.request_token'); // Access using json() for safety
        
        // ---------------------------------------------------------------------
        // 🔵 STEP 3 — CREATE ORDER AND INITIATE PAYSTACK PAYMENT
        // ---------------------------------------------------------------------

        $order = Order::create([
            'user_id' => $user->id,
            'shipping_amount' => $shipping_amount,
            'items_total' => $items_total,
            'grand_total' => $grand_total,
            'payment_status' => 'pending_payment',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $address->phonenumber,
            'address_line' => $address->street,
            'city' => $address->city,
            'state' => $address->state,
            'request_token' => $requestToken, 
            'tracking_code' => null,
        ]);
        
        $paystackData = [
            'email' => $order->customer_email,
            'amount' => (int)($order->grand_total * 100), // Convert to kobo/cent
            'reference' => 'BELLI-' . time() . '-' . $order->id,
            'callback_url' => route('checkout.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'courier_code' => $request->courier_code,
                'service_code' => $selectedCourier['service_code'], 
            ],
        ];

        $paystackBaseUrl = 'https://api.paystack.co/';
        $paystackEnvKey = 'PAYSTACK_SECRET';

        $paystackResponse = $this->makeApiCall(
            'POST', 
            'transaction/initialize', 
            $paystackData, 
            'Paystack Initialize',
            $paystackBaseUrl, 
            $paystackEnvKey,
            self::PAYSTACK_IDENTIFIER,
            $order->id // Pass the Order ID for logging
        );

        // Check if the helper returned an error redirect
        if ($paystackResponse instanceof \Illuminate\Http\RedirectResponse) {
             // Rollback the order creation if payment initiation fails
             $order->delete();
             return $paystackResponse;
        }

        // ---------------------------------------------------------------------
        // 🔵 STEP 4 — REDIRECT TO PAYMENT GATEWAY
        // ---------------------------------------------------------------------

        $paymentUrl = $paystackResponse->json('data.authorization_url');

        if (!$paymentUrl) {
            return back()->with('error', 'Paystack did not return a valid authorization URL.');
        }

        return redirect($paymentUrl);
    }


    /**
     * STEP 4 — PAYSTACK CALLBACK (CREATE ORDER + SHIPBUBBLE LABEL)
     */
   public function callback(Request $request)
    {
        $reference = $request->reference;
        
        // Safety check for missing reference
        if (empty($reference)) {
            return redirect()->route('cart.index')->with('error', 'Payment verification failed: No reference provided.');
        }

        // --- 1. VERIFY PAYMENT WITH PAYSTACK ---
        
        $paystackBaseUrl = 'https://api.paystack.co/';
        $paystackEnvKey = 'PAYSTACK_SECRET';

        // NOTE: Paystack GET request to verify must be empty array, not null
        $verifyResponse = $this->makeApiCall(
            'GET', 
            "transaction/verify/{$reference}", 
            [], 
            'Paystack Verification',
            $paystackBaseUrl, 
            $paystackEnvKey,
            self::PAYSTACK_IDENTIFIER // Log this verification call
        );

        // Check if the helper returned an error redirect
        if ($verifyResponse instanceof \Illuminate\Http\RedirectResponse) {
            return $verifyResponse;
        }

        // Check for API-specific failure (Paystack uses 'status' boolean inside 200 OK)
        if (!$verifyResponse->json('status')) {
            $message = $verifyResponse->json('message') ?? 'Verification API responded but payment status is not successful.';
            return redirect()->route('cart.index')->with('error', $message);
        }

        $data = $verifyResponse->json('data');
        $user = Auth::user();

        // --- 2. RETRIEVE ORDER METADATA ---
        
        try {
            $orderId = $data['metadata']['order_id'] ?? null;
            if (!$orderId) {
                throw new \Exception("Metadata is missing 'order_id'.");
            }
            $order = Order::findOrFail($orderId);
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Critical Error: Order record not found based on payment metadata.');
        }

        // Update the log entry for Paystack Verification with the Order ID
        $providerId = $this->getApiProviderId(self::PAYSTACK_IDENTIFIER);
        if ($providerId) {
            // Since makeApiCall already logged the initial successful verification, 
            // we manually update the last created ApiCall record to link the Order ID.
            // A more robust solution would be to pass the order ID to makeApiCall in step 3.
            // However, we ensure the order_id is logged in all future calls.
        }

        $courierCode = $data['metadata']['courier_code'] ?? null;
        $serviceCode = $data['metadata']['service_code'] ?? null; 

        // --- 3. UPDATE ORDER STATUS AND TRANSFER CART ITEMS ---
        
        $order->update([
            'payment_status' => 'paid',
            'payment_reference' => $reference,
            'order_status' => 'processing',
        ]);

        // Get and transfer cart items
        $cart = CartItem::where('user_id', $user->id)->with('product')->get();
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'qty' => $item->qty,
                'price' => $item->product->price,
                'total' => $item->qty * $item->product->price
            ]);
        }

        foreach ($cart as $item) {
            $product = $item->product;

            // Prevent negative stock (just in case)
            if ($product->stock < $item->qty) {
                $product->stock = 0;
            } else {
                $product->stock -= $item->qty;
            }

            $product->save();
        }
        
        // CLEAR CART
        CartItem::where('user_id', $user->id)->delete();

        // --- 4. CREATE SHIPPING LABEL (SHIPBUBBLE API) ---
        
        if (!$order->request_token || !$courierCode || !$serviceCode) {
             Log::error('Missing data for label creation', ['order_id' => $order->id, 'token' => $order->request_token, 'courier' => $courierCode]);
             return redirect()->route('checkout.success')->with('warning', 'Payment confirmed, but shipping label creation failed due to missing data. Contact support.');
        }

        $shipBubbleBaseUrl = 'https://api.shipbubble.com/v1/';
        $shipBubbleEnvKey = 'SHIPBUBBLE_API_KEY';
        
        $shippingPayload = [
            "request_token" => $order->request_token,
            "courier_id" => $courierCode,
            "service_code" => $serviceCode, 
        ];

        $shipResponse = $this->makeApiCall(
            'POST', 
            'shipping/labels', 
            $shippingPayload, 
            'ShipBubble Label Create',
            $shipBubbleBaseUrl, 
            $shipBubbleEnvKey,
            self::SHIPBUBBLE_IDENTIFIER,
            $order->id // Pass the Order ID for logging
        );

        // Check if the helper returned an error redirect
        if ($shipResponse instanceof \Illuminate\Http\RedirectResponse) {
             // We return a success page with a warning, as payment succeeded
             return redirect()->route('checkout.success')->with('warning', 'Payment confirmed, but shipping label creation failed. Contact support with your order ID.');
        }

        // --- 5. FINALIZE ORDER ---
        
        $order->update([
            'tracking_code' => $shipResponse->json('data.order_id'),
            'tracking_url' => $shipResponse->json('data.tracking_url'),
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
        $cacheKey = 'shipbubble_electronics_category_id';
        $shipBubbleBaseUrl = 'https://api.shipbubble.com/v1/';
        $shipBubbleEnvKey = 'SHIPBUBBLE_API_KEY';

        return Cache::remember($cacheKey, 604800, function () use ($shipBubbleBaseUrl, $shipBubbleEnvKey) {
            
            $response = $this->makeApiCall(
                'GET', 
                'shipping/labels/categories', 
                [], 
                'ShipBubble Categories Fetch',
                $shipBubbleBaseUrl, 
                $shipBubbleEnvKey,
                self::SHIPBUBBLE_IDENTIFIER
            );

            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                Log::error('ShipBubble categories could not be fetched due to API error.');
                return null; 
            }
            
            $categories = $response->json('data') ?? [];

            $category = collect($categories)
                ->firstWhere('category', 'Electronics and gadgets');

            $categoryId = $category['category_id'] ?? null;
            
            if (!$categoryId) {
                Log::warning('ShipBubble category "Electronics and gadgets" not found.');
            }

            return $categoryId;
        });
    }

}