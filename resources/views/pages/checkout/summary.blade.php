@extends('layouts.app')

@section('content')

{{-- ---------------------------------------------------------------------------------------------------------------- --}}
{{-- 💡 MESSAGE HANDLING 💡 --}}
{{-- ---------------------------------------------------------------------------------------------------------------- --}}
@if (session('error'))
    <x-message-alert type="error" :message="session('error')" />
@elseif (session('success'))
    <x-message-alert type="success" :message="session('success')" />
@endif
{{-- ---------------------------------------------------------------------------------------------------------------- --}}

{{--
| 1. WRAP ENTIRE CONTENT IN ALPINE SCOPE
| We use x-data="{ loading: false }" to create the state variable.
--}}
<div x-data="{ loading: false }">

    {{--
    | 2. INSERT THE LOADING SPINNER COMPONENT
    | x-show="loading" binds its visibility to the state variable.
    --}}
    <x-loading-spinner x-show="loading" x-cloak size="lg">
        Initializing Payment...
    </x-loading-spinner>


    @php
    // --- Mock Calculations for the Summary page (must match controller logic) ---
    $subtotal = $cart->sum(fn($item) => $item->product->price * $item->qty);
    // Assume $couriers[0]['total_charge'] holds the selected shipping cost
    $selectedShippingCost = $couriers[0]['total_charge'] ?? 1500;
    $taxRate = 0.075;
    $tax = round($subtotal * $taxRate);
    $grandTotal = $subtotal + $selectedShippingCost + $tax;
    // --- End Mock Calculations ---
    @endphp
    
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

    <!-- Checkout Progress Indicator (Completed Address) -->
    <div class="mb-10 flex justify-between items-center text-center">
        <div class="flex-1">
            <span class="inline-flex items-center justify-center w-10 h-10 bg-green-600 rounded-full text-white font-bold text-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </span>
            <p class="text-green-600 font-semibold mt-2">Shipping Address</p>
        </div>
        <div class="flex-1 h-1 bg-blue-600 mx-4"></div>
        <div class="flex-1">
            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 rounded-full text-white font-bold text-lg">2</span>
            <p class="text-blue-600 font-semibold mt-2">Review & Pay</p>
        </div>
    </div>

    <h1 class="text-4xl font-extrabold text-gray-900 mb-8">
        2. Final Order Review
    </h1>

    <div class="lg:grid lg:grid-cols-3 lg:gap-10">

        <!-- Left Column: Order Details (Address, Shipping, Items) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Delivery Details Box -->
            <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Delivery Details
                </h2>
                
                <div class="grid sm:grid-cols-2 gap-6">
                    <!-- Shipping Address -->
                    <div>
                        <h3 class="font-semibold text-lg text-gray-700">Shipping Address</h3>
                        <address class="text-gray-600 not-italic space-y-0.5 mt-1">
                            <p class="font-medium">{{ $user->name }}</p>
                            <p>{{ $address->street }}, {{ $address->city }}, {{ $address->state }}</p>
                            <p>{{ $address->country }}, {{ $address->postal_code }}</p>
                            <p>Phone: {{ $address->phonenumber }}</p>
                        </address>
                        <a href="{{ route('cart.checkout') }}" class="text-sm text-blue-600 hover:text-blue-800 transition mt-2 inline-block">Change Address</a>
                    </div>
                    
                    <!-- Shipping Method (Placeholder for Courier Options) -->
                    <div>
                        <h3 class="font-semibold text-lg text-gray-700 mb-2">Shipping Method</h3>
                        @if(!empty($couriers))
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="font-medium text-gray-800">{{ $couriers[0]['courier_name'] ?? 'Standard Delivery' }}</p>
                                <p class="text-sm text-gray-600">{{ $couriers[0]['service_code'] ?? '3-5 business days' }}</p>
                                <p class="text-sm font-bold text-green-700 mt-1">
                                    ₦{{ number_format($selectedShippingCost) }}
                                </p>
                            </div>
                        @else
                            <p class="text-red-500 text-sm">No shipping options available for this region.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items in Order -->
            <div class="p-6 bg-white rounded-xl shadow-lg border border-gray-100">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Items in Order ({{ $cart->sum('qty') }} items)</h2>
                <div class="space-y-4">
                    @foreach($cart as $item)
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-b-0 last:pb-0">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-600 w-6 text-right">{{ $item->qty }}x</span>
                                <p class="flex-grow font-medium text-gray-800 line-clamp-1">{{ $item->product->name }}</p>
                            </div>
                            <p class="font-bold text-green-700 text-base">₦{{ number_format($item->product->price * $item->qty) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column: Final Order Summary & Payment Button (Sticky) -->
        <div class="lg:col-span-1 mt-8 lg:mt-0">
            <div class="sticky top-8 p-6 bg-blue-50 rounded-xl shadow-2xl border border-blue-200">
                <h2 class="text-2xl font-bold text-blue-800 mb-4 pb-2 border-b border-blue-300">Total Payment</h2>

                <!-- Summary Breakdown -->
                <div class="space-y-3 text-gray-700">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold">₦{{ number_format($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping Cost</span>
                        <span class="font-semibold">₦{{ number_format($selectedShippingCost) }}</span>
                    </div>
                    <div class="flex justify-between border-b border-blue-200 pb-3">
                        <span>Tax (7.5%)</span>
                        <span class="font-semibold">₦{{ number_format($tax) }}</span>
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="flex justify-between pt-4 text-2xl font-extrabold text-blue-800">
                    <span>Order Total</span>
                    <span>₦{{ number_format($grandTotal) }}</span>
                </div>

                <!-- Payment Button -->
                <form action="{{ route('checkout.pay') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                    <input type="hidden" name="courier_code" value="{{ $couriers[0]['service_code'] ?? '' }}">
                    <input type="hidden" name="customer_name" value="{{ $user->name }}">
                    <input type="hidden" name="customer_email" value="{{ $user->email }}">
                    <input type="hidden" name="customer_phone" value="{{ $user->phone ?? '' }}">
                    
                    <button 
                        type="submit" 
                        class="w-full bg-green-600 text-white py-3 rounded-lg text-xl font-bold shadow-lg hover:bg-green-700 transition transform hover:scale-[1.01]"
                        {{-- 4. DISABLE BUTTON WHILE LOADING --}}
                        :disabled="loading" 
                    >
                        {{-- Change button text when loading --}}
                        <span x-show="!loading">Pay ₦{{ number_format($grandTotal) }} Now</span>
                        <span x-show="loading" style="display: none;">Processing... Please wait</span>
                    </button>
                </form>

                <p class="text-xs text-gray-500 mt-4 text-center">
                    By clicking 'Pay Now', you agree to the terms and conditions.
                </p>
            </div>
        </div>

    </div>

    </div>
</div>
@endsection