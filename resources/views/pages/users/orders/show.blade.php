@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Order Details</h1>

    <div class="bg-white border rounded-lg p-6 shadow-sm">
        <p class="text-lg font-semibold">Order #{{ $order->id }}</p>
        <p class="text-gray-700">Status: <strong>{{ ucfirst($order->status) }}</strong></p>
        <p class="text-gray-700">Total: ₦{{ number_format($order->total, 2) }}</p>

        <hr class="my-4">

        <h2 class="font-semibold mb-2">Items:</h2>

        @foreach($order->items as $item)
            <div class="border-b py-3 flex justify-between items-center">
                
                {{-- LEFT SIDE: Image and Product Name --}}
                <div class="flex items-center space-x-3">
                    @php
                        // Get the first image path safely
                            $imagePath = optional($item->product->images->first())->path;
                            $imageUrl = $imagePath ? $imagePath : 'https://placehold.co/100x100/f3f4f6/333333?text=Item';
                    @endphp

                    {{-- Image Container: 100% fits inside a fixed 48x48 square --}}
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden border">
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="{{ $item->product->name }}" 
                            class="w-full h-full object-cover" 
                        >
                    </div>

                    {{-- Product Details --}}
                    <div>
                        <span class="font-medium text-gray-900">{{ $item->product->name }}</span>
                        <span class="text-gray-600 text-sm block">Qty: x{{ $item->qty }}</span>
                    </div>
                </div>

                {{-- RIGHT SIDE: Price --}}
                <span class="font-semibold text-gray-800">₦{{ number_format($item->price * $item->qty, 2) }}</span>
            </div>
        @endforeach
    </div>
</div>
@endsection