<h2>Great news, {{ $order->customer_name }}!</h2>
<p>Your order #{{ $order->id }} is now on its way to you.</p>

<div style="padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
    <p><strong>Tracking Number:</strong> {{ $order->tracking_code }}</p>
    <p><strong>Courier:</strong> {{ $order->courier_name ?? 'Standard Courier' }}</p>
    
    <a href="{{ $order->tracking_url }}" 
       style="display: inline-block; padding: 10px 20px; background-color: #4f46e5; color: white; text-decoration: none; border-radius: 5px;">
       Track Your Package
    </a>
</div>

<p>Thank you for shopping with us!</p>