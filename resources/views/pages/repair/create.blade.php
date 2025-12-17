@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4">

    {{-- ---------------------------------------------------------------------------------------------------------------- --}}
    {{-- 💡 MESSAGE HANDLING 💡 --}}
    {{-- ---------------------------------------------------------------------------------------------------------------- --}}
    @if (session('error'))
        <x-message-alert type="error" :message="session('error')" />
    @elseif (session('success'))
        <x-message-alert type="success" :message="session('success')" />
    @endif
    {{-- ---------------------------------------------------------------------------------------------------------------- --}}

    <h1 class="text-3xl font-bold mb-4 text-gray-800">Book a Repair</h1>

    {{-- Track Repair Link --}}
    <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-lg text-center">
        <p class="text-indigo-800 font-medium mb-2">Already booked a repair?</p>
        <a href="{{ route('repair.index') }}" class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-7-9a1 1 0 000 2h3a1 1 0 100-2H3zm7 5a1 1 0 100-2h3a1 1 0 100-2h-3a1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            Track My Repairs
        </a>
    </div>

    <form action="{{ route('repair.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-lg">
        @csrf

        {{-- Address --}}
        <h2 class="text-xl font-semibold mt-4 text-gray-700 border-b pb-2 mb-4">Your Address</h2>
        <label for="address_id" class="block text-sm font-medium text-gray-700 mb-1">Select Existing Address (Optional)</label>
        <select name="address_id" id="address_id" class="border border-gray-300 p-3 w-full rounded-lg mb-4 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Select an existing address</option>
            @foreach($userAddresses as $addr)
            <option value="{{ $addr->id }}">
                {{ $addr->street }}, {{ $addr->city }}, {{ $addr->state }}, {{ $addr->country }}
            </option>
            @endforeach
        </select>

        {{-- New Address Inputs (Hidden/Optional) --}}
        <h3 class="mt-2 text-sm font-medium text-gray-700 mb-2">Or add new address details:</h3>
        <input type="text" name="name" placeholder="Full Name" class="border border-gray-300 p-3 w-full rounded-lg mb-2">
        <input type="text" name="phone" placeholder="Phone" class="border border-gray-300 p-3 w-full rounded-lg mb-2">
        <input type="text" name="address" placeholder="Street Address" class="border border-gray-300 p-3 w-full rounded-lg mb-2">
        <input type="text" name="latitude" placeholder="Latitude (Optional)" class="border border-gray-300 p-3 w-full rounded-lg mb-2">
        <input type="text" name="longitude" placeholder="Longitude (Optional)" class="border border-gray-300 p-3 w-full rounded-lg mb-2">

        {{-- Device Info --}}
        <h2 class="text-xl font-semibold mt-6 text-gray-700 border-b pb-2 mb-4">Device Info</h2>
        <input type="text" name="device_type" placeholder="Device Type (e.g., Laptop, Phone)" class="border border-gray-300 p-3 w-full rounded-lg mb-2" required>
        <input type="text" name="brand" placeholder="Brand (e.g., Apple iphone 17, Samsung Galaxy S20)" class="border border-gray-300 p-3 w-full rounded-lg mb-2" required>
        <textarea name="issue" placeholder="Detailed Fault / Issue Description" class="border border-gray-300 p-3 w-full rounded-lg mb-2" rows="3" required></textarea>

        {{-- Delivery Option --}}
        <h2 class="text-xl font-semibold mt-6 text-gray-700 border-b pb-2 mb-4">Delivery Option</h2>
        <select name="delivery_method" class="border border-gray-300 p-3 w-full rounded-lg mb-4 focus:ring-indigo-500 focus:border-indigo-500" required>
            <option value="dropoff">Drop-off at our location</option>
            <option value="shipbubble">ShipBubble pickup (delivery charges apply)</option>
        </select>

        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold p-3 mt-4 rounded-lg shadow-md hover:bg-indigo-700 transition duration-150">
            Book Repair & Get Tracking Code
        </button>
    </form>
</div>
@endsection