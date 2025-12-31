<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShipped;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a list of paid orders within a specified date range (for Revenue).
     */
    public function index(Request $request)
    {
        // 1. Get dates from query parameters, falling back to a default range (e.g., last 30 days)
        $start = $request->get('start') ? Carbon::parse($request->get('start'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->get('end') ? Carbon::parse($request->get('end'))->endOfDay() : Carbon::now()->endOfDay();

        // --- 2. Calculation for Top 5 Selling Products ---
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(qty) as total_quantity'))
            // Filter by orders that are paid and within the date range
            ->whereHas('order', function ($query) use ($start, $end) {
                $query->where('payment_status', 'paid')
                      ->whereBetween('created_at', [$start, $end]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->with('product') // Eager load the product details
            ->get();


        // --- 3. Filter orders for pagination (as before) ---
        $orders = Order::with(['user', 'items.product']) // Eager load product on items for the table
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->paginate(6); 

        return view('admin.orders.index', compact('orders', 'start', 'end', 'topProducts'));
    }

    public function retryShipping(Order $order)
    {
        // 1. Basic Check: Don't retry if it's already shipped
        if ($order->tracking_code && $order->tracking_code !== 'Pending assignment') {
            return back()->with('error', 'This order already has a tracking code.');
        }

        // 2. Re-prepare the payload
        // Note: We stored the request_token and courier details in the order/metadata previously
        $shippingPayload = [
            "request_token" => $order->request_token,
            "courier_id"    => $order->courier_id, // Ensure you saved this to your order table
            "service_code"  => $order->service_code, 
        ];

        // 3. Make the ShipBubble API Call
        $shipResponse = $this->makeApiCall(
            'POST', 
            'shipping/labels', 
            $shippingPayload, 
            'ShipBubble Manual Retry',
            self::SHIPBUBBLE_IDENTIFIER,
            $order->id
        );

        // 4. Handle Response
        if (!$shipResponse->successful()) {
            $error = $shipResponse->json('message') ?? 'Label creation failed again.';
            return back()->with('error', 'ShipBubble Error: ' . $error);
        }

        // 5. Success! Update the Order
        $shipData = $shipResponse->json('data');
        $order->update([
            'tracking_code' => $shipData['order_id'],
            'tracking_url'  => $shipData['tracking_url'],
            'label_url'     => $shipData['label_url'] ?? null,
            'order_status'  => 'processing', // Move it out of 'manual_intervention'
        ]);

        // Optional: Send "Order Shipped" email to customer here
        Mail::to($order->customer_email)->send(new OrderShipped($order));

        return back()->with('success', 'Shipping label generated successfully!');
    }

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
}
