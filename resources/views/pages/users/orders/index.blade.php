@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">My Orders</h1>
    <div class="space-y-4">
        @foreach($orders as $order)
            <div class="p-5 border rounded-xl shadow-md bg-white flex items-center justify-between">
                
                <div class="flex items-center space-x-4">
                    
               @php
                    $firstItem = $order->items->first();
                    $product = optional($firstItem)->product;
                    $imagePath = optional($product?->images->first())->path;
                    $imageUrl = $imagePath ?: 'https://placehold.co/100x100/f3f4f6/333333?text=Item';
                @endphp


                    {{-- Image Display Area --}}
                    <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100 border">
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="Product image for Order #{{ $order->id }}" 
                            class="w-full h-full object-cover"
                        >
                    </div>

                    {{-- Order Details --}}
                    <div>
                        <p class="font-bold text-lg text-gray-900">Order #{{ $order->id }}</p>
                        <p class="text-gray-600 text-sm">Status: 
                            <span class="font-semibold text-{{ $order->payment_status === 'paid' ? 'green-600' : 'red-600' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        <p class="text-gray-800 font-bold text-sm">Total: ₦{{ number_format($order->items_total + $order->shipping_cost, 2) }}</p>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="flex space-x-3">
                    {{-- 1. TRACK ORDER BUTTON (NEW) --}}
                    <a href="{{ route('orders.track', $order) }}"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Track Order
                    </a>
                    <a href="{{ route('user.orders.show', $order->id) }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        View Details
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection