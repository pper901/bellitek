@extends('layouts.app')

@section('content')


{{-- ---------------------------------------------------------------------------------------------------------------- --}}
{{-- 💡 MESSAGE HANDLING (Place this at the top so it loads early) 💡 --}}
{{-- ---------------------------------------------------------------------------------------------------------------- --}}
@if (session('error'))
    {{-- Call the component with type 'error' and the message from the session --}}
    <x-message-alert type="error" :message="session('error')" />
@elseif (session('success'))
    {{-- Call the component with type 'success' and the message from the session --}}
    <x-message-alert type="success" :message="session('success')" />
@endif
{{-- ---------------------------------------------------------------------------------------------------------------- --}}



<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8 border-b border-gray-200 pb-3">
        Shopping Cart
    </h1>

    @if($cartItems->count() > 0)
        <div class="space-y-6">
            @foreach($cartItems as $item)
                <div class="flex items-center p-4 sm:p-6 bg-white rounded-xl shadow-lg border border-gray-100 transition duration-300 hover:shadow-xl">
                    <!-- Product Image -->
                    <a href="{{ route('store.product', $item->product->id) }}" class="flex-shrink-0">
                        @php
                            $imagePath = optional($item->product->images->first())->path;
                            $imageUrl = $imagePath ? $imagePath : 'https://placehold.co/100x100/f3f4f6/333333?text=Item';
                        @endphp
                        <img src="{{ $imageUrl }}" 
                             onerror="this.onerror=null; this.src='https://placehold.co/100x100/f3f4f6/333333?text=Item';"
                             class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-xl border border-gray-200"
                             alt="{{ $item->product->name }}">
                    </a>

                    <!-- Product Details -->
                    <div class="flex-grow ml-4 sm:ml-6 grid grid-cols-2 sm:grid-cols-4 gap-y-3 items-center">
                        <div class="col-span-2 sm:col-span-1">
                            <a href="{{ route('store.product', $item->product->id) }}">
                                <h2 class="font-bold text-lg text-gray-800 line-clamp-2 hover:text-blue-600 transition">
                                    {{ $item->product->name }}
                                </h2>
                            </a>
                            <p class="text-sm text-gray-500 mt-1">{{ $item->product->brand ?? 'Unbranded' }}</p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="col-span-1">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center space-x-2">
                                @csrf
                                @method('PATCH')
                                <!-- <input type="hidden" name="qty" value="{{ $item->qty }}" id="qty-{{ $item->id }}"> -->
                                
                                <button type="submit" name="action" value="decrement"
                                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-700 transition"
                                        title="Decrease Quantity">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                
                                <span class="text-xl font-semibold w-6 text-center">{{ $item->qty }}</span>
                                
                                <button type="submit" name="action" value="increment"
                                        class="p-2 border border-gray-300 rounded-lg hover:bg-gray-100 text-gray-700 transition"
                                        title="Increase Quantity">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Item Subtotal -->
                        <div class="col-span-1 text-right sm:text-left">
                            <span class="text-lg font-extrabold text-green-700">
                                ₦{{ number_format($item->product->price * $item->qty) }}
                            </span>
                            <p class="text-xs text-gray-500">₦{{ number_format($item->product->price) }} each</p>
                        </div>

                        <!-- Remove Button -->
                        <div class="col-span-1 flex justify-end">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 transition font-medium text-sm flex items-center space-x-1" title="Remove Item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="hidden sm:inline">Remove</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Proceed to Checkout Button -->
            <div class="mt-6 text-right">
                <a href="{{ route('cart.checkout') }}" 
                   class="inline-block w-full sm:w-auto bg-blue-600 text-white py-3 px-6 rounded-lg text-lg font-bold shadow-md hover:bg-blue-700 transition duration-200 transform hover:scale-[1.01]">
                   Proceed to Checkout
                </a>
            </div>

        </div>

    @else
        <div class="flex flex-col items-center justify-center p-16 bg-white rounded-2xl border-2 border-dashed border-gray-300 shadow-inner">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Your Basket is Empty</h2>
            <p class="text-gray-600 text-center max-w-md mb-6">Looks like you haven't added anything to your cart yet. Explore our selection and find something great!</p>
            <a href="{{ route('store.index') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-lg font-medium rounded-xl shadow-md text-white bg-blue-600 hover:bg-blue-700 transition duration-150 transform hover:scale-[1.02]">
                Start Shopping
            </a>
        </div>
    @endif
</div>

@endsection
