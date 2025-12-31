<h1>Thank you for your order, {{ $order->customer_name }}!</h1>
<p>Your payment of ₦{{ number_format($order->grand_total) }} was successful.</p>
<p><strong>Order ID:</strong> #{{ $order->id }}</p>

<h3>Items:</h3>
<ul>
    @foreach($order->items as $item)
        <li>{{ $item->product->name }} (x{{ $item->qty }}) - ₦{{ number_format($item->total) }}</li>
    @endforeach
</ul>

<p>We are currently processing your shipment. You will receive tracking details shortly.</p>