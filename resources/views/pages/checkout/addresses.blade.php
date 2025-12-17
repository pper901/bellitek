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

@php
    // Pre-calculate the final URL using Blade syntax
    $summaryUrl = route('checkout.summary', ['address' => $address->id]);
@endphp
<div x-data="{ loading: false }">

    {{--
    | 2. INSERT THE LOADING SPINNER COMPONENT
    | x-show="loading" binds its visibility to the state variable.
    --}}
    <x-loading-spinner x-show="loading" x-cloak size="lg">
        Validating Address...
    </x-loading-spinner>


    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">

    <!-- Checkout Progress Indicator -->
    <div class="mb-10 flex justify-between items-center text-center">
        <div class="flex-1">
            <span class="inline-flex items-center justify-center w-10 h-10 bg-blue-600 rounded-full text-white font-bold text-lg">1</span>
            <p class="text-blue-600 font-semibold mt-2">Shipping Address</p>
        </div>
        <div class="flex-1 h-1 bg-gray-300 mx-4"></div>
        <div class="flex-1">
            <span class="inline-flex items-center justify-center w-10 h-10 bg-gray-200 rounded-full text-gray-500 font-bold text-lg">2</span>
            <p class="text-gray-500 mt-2">Review & Pay</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Address Management (2/3 width) -->
        <div class="lg:col-span-2">
            
            <h2 class="text-2xl font-bold text-gray-800 mb-6">1. Select Delivery Address</h2>

            <!-- Existing Address Selection -->
            @if($address)
                <div class="mb-8 p-6 bg-white rounded-xl border-2 border-green-500 shadow-md">
                    <h3 class="text-xl font-semibold mb-2 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.555-4.555a1 1 0 011.414 0l.535.535a1 1 0 010 1.414L16 12m-3 3l-4.555-4.555a1 1 0 01-1.414 0l-.535.535a1 1 0 010 1.414L8 12m-3 3l-4.555-4.555a1 1 0 01-1.414 0l-.535.535a1 1 0 010 1.414L2 12m-2 3l-4.555-4.555a1 1 0 01-1.414 0l-.535.535a1 1 0 010 1.414L-2 12" /></svg>
                        Your Current Default Address
                    </h3>
                    <address class="text-gray-600 space-y-1 not-italic ml-8">
                        <p class="font-medium">{{ $user->name }}</p>
                        <p>{{ $address->street }}, {{ $address->city }}, {{ $address->state }}</p>
                        <p>{{ $address->country }}, {{ $address->postal_code }}</p>
                        <p>Phone: {{ $address->phonenumber }}</p>
                    </address>
                    
                    <a 
                        href="#"
                        {{-- CORRECTED: The final $summaryUrl variable (which is a string) is embedded into the JS string --}}
                        @click.prevent="loading = true; window.location.href = '{{ $summaryUrl }}'"
                        
                        class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition shadow-md"
                        :class="{'opacity-75 cursor-not-allowed': loading}" 
                    >
                        {{-- Show spinner content here --}}
                        <span x-show="!loading">Deliver to this Address</span>
                        <span x-show="loading" style="display: none;">
                            <span class="animate-spin inline-block w-4 h-4 border-2 border-t-2 border-white border-t-blue-300 rounded-full mr-2"></span>
                            Processing...
                        </span>
                    </a>
                </div>
                
                <h3 class="text-xl font-semibold text-gray-800 mb-4 pt-4 border-t border-gray-200">
                    Or Add a New Address:
                </h3>
            @endif

            <!-- New Address Form -->
            <form action="{{ route('checkout.saveAddress') }}" method="POST" class="space-y-4 p-6 bg-gray-50 rounded-xl border border-gray-200 shadow-inner">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <input type="text" name="street" placeholder="Street Address / House No." required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                    <input type="text" name="city" placeholder="City" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                </div>
                
                <div class="grid sm:grid-cols-3 gap-4">
                    <input type="text" name="state" placeholder="State/Region" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                    <input type="text" name="postal_code" placeholder="Postal Code (Optional)" class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                    <input type="text" name="country" placeholder="Country" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                </div>
                
                <input type="tel" name="phonenumber" placeholder="Contact Phone Number (e.g., +234...)" required class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm">
                
                <button 
                    type="submit" 
                    class="w-full bg-green-600 text-white py-3 rounded-lg text-xl font-bold shadow-lg hover:bg-green-700 transition transform hover:scale-[1.01]"
                    {{-- 4. DISABLE BUTTON WHILE LOADING --}}
                    :disabled="loading" 
                >
                    {{-- Change button text when loading --}}
                    <span x-show="!loading"> Save Address & Continue to Review</span>
                    <span x-show="loading" style="display: none;">Processing... Please wait</span>
                </button>
            </form>
        </div>
        
        <!-- Right Column: Cart Summary (Sticky, 1/3 width) -->
        <div class="lg:col-span-1 mt-8 lg:mt-0">
            <div class="sticky top-8 p-6 bg-gray-100 rounded-xl shadow-inner border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b">Your Cart</h2>
                
                <!-- Item List (Simplified) -->
                @php
                    $subtotal = $cart->sum(fn($item) => $item->product->price * $item->qty);
                @endphp
                <div class="space-y-3 mb-6">
                    @foreach($cart as $item)
                        <div class="flex justify-between text-sm text-gray-700">
                            <span class="line-clamp-1">{{ $item->product->name }} (x{{ $item->qty }})</span>
                            <span class="font-semibold">₦{{ number_format($item->product->price * $item->qty) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <!-- Total -->
                <div class="flex justify-between pt-4 border-t text-xl font-extrabold text-gray-900">
                    <span>Subtotal</span>
                    <span>₦{{ number_format($subtotal) }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Shipping and tax calculated in the next step.</p>
            </div>
        </div>

    </div>
</div>

</div>
@endsection