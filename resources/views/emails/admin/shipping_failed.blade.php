<h2>Urgent: ShipBubble Label Creation Failed</h2>
<p>An order was just paid for, but the shipping label could not be created automatically.</p>

<div style="background: #fff4f4; padding: 15px; border: 1px solid #cc0000;">
    <strong>Error from ShipBubble:</strong> {{ $errorMessage }}
</div>

<h3>Order Details:</h3>
<ul>
    <li><strong>Order ID:</strong> #{{ $order->id }}</li>
    <li><strong>Customer:</strong> {{ $order->customer_name }}</li>
    <li><strong>Amount Paid:</strong> ₦{{ number_format($order->grand_total) }}</li>
    <li><strong>Destination:</strong> {{ $order->city }}, {{ $order->state }}</li>
</ul>

<p><strong>Action Required:</strong> Please fund the ShipBubble wallet and manually generate the label for this order in the ShipBubble dashboard.</p>