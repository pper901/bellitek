@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12">

    <h1 class="text-3xl font-bold mb-6 text-center">Track Your Order</h1>

    <div class="bg-white shadow p-6 rounded-lg">

        <h2 class="text-lg font-semibold mb-3">Order Information</h2>

        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->order_status ?? 'processing') }}</p>
        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status ?? 'paid') }}</p>
        <p><strong>Tracking Code:</strong> {{ $order->tracking_code ?? 'Not yet available' }}</p>
        <p><strong>Total Amount:</strong> ₦{{ number_format($order->grand_total) }}</p>

        <hr class="my-6">

        <h2 class="text-lg font-semibold mb-4">Shipping Details</h2>

        <p>{{ $order->customer_name }}</p>
        <p>{{ $order->address_line }}</p>
        <p>{{ $order->city }}, {{ $order->state }}</p>
        <p>Phone: {{ $order->customer_phone }}</p>

        @if($order->tracking_url)
            <div class="mt-10 text-center">
                <a href="{{ $order->tracking_url }}"
                   target="_blank"
                   class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Track on ShipBubble
                </a>
            </div>
        @endif

    </div>

</div>
@endsection
