@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto text-center py-16">

    <div class="mb-6">
        <svg class="mx-auto h-20 w-20 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.5 12.75l6 6 9-13.5" />
        </svg>
    </div>

    <h1 class="text-3xl font-bold mb-2">Payment Successful!</h1>

    <p class="text-gray-600">
        Your order has been placed successfully.  
        We’ve sent you an email confirmation.
    </p>

    <div class="bg-white shadow mt-8 p-6 rounded-lg text-left">
        <h2 class="text-lg font-semibold mb-4">Order Details</h2>

        <p><strong>Order ID:</strong> {{ $order->id }}</p>
        <p><strong>Total Amount:</strong> ₦{{ number_format($order->grand_total) }}</p>
        <p><strong>Tracking Code:</strong> 
            <a href="{{ $order->tracking_url ?? '#' }}" target="_blank">
                {{ $order->tracking_code ?? 'Generating…' }}
            </a>
        </p>

    </div>

    <div class="mt-10 flex justify-center gap-4">
        <a href="{{ route('orders.track', $order->id) }}"
           class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Track Order
        </a>

        <a href="{{ route('home') }}"
           class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
            Return Home
        </a>
    </div>

</div>
@endsection
