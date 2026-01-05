<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class ShipbubbleWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Shipbubble Webhook Received', $request->all());

        // OPTIONAL but recommended:
        // Verify signature / secret if Shipbubble provides one

        $event = $request->input('event');
        $data  = $request->input('data');

        if (!$data || !isset($data['order_id'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $order = Order::where('shipbubble_order_id', $data['order_id'])->first();

        if (!$order) {
            Log::warning('Shipbubble webhook: Order not found', [
                'shipbubble_order_id' => $data['order_id']
            ]);
            return response()->json(['message' => 'Order not found'], 200);
        }

        // Map Shipbubble status to your internal status
        $order->update([
            'shipment_status' => $data['status'], // picked_up, in_transit, delivered
            'tracking_code'   => $data['tracking_code'] ?? $order->tracking_code,
            'tracking_url'    => $data['tracking_url'] ?? $order->tracking_url,
        ]);

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}
