<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class WebhookController extends Controller
{

    public function handleShipbubbleNotification(Request $request)
    {
        // 1. (Optional but Recommended) Security Check: Verify the request signature 
        //    (You must configure Shipbubble webhook security to prevent forged requests)

        // 2. Identify the Tracking Code or Order ID from Shipbubble's payload
        $trackingCode = $request->input('data.tracking_code');
        $newStatus = strtolower($request->input('data.status')); // e.g., 'delivered'

        if ($trackingCode && $newStatus === 'delivered') {
            // Find the order in your database using the tracking code
            $order = Order::where('tracking_code', $trackingCode)->first();

            if ($order) {
                // 3. Apply the 'delivered' logic (clear the tracking code)
                $order->order_status = 'delivered';
                $order->tracking_code = null;
                $order->save();
            }
        }

        // Always return a success response (HTTP 200) quickly, otherwise Shipbubble may retry.
        return response()->json(['message' => 'Notification received and processed.'], 200);
    }
}
