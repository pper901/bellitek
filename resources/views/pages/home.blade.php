@extends('layouts.app')

@section('content')

<section class="text-center py-20 bg-gradient-to-r from-blue-600 to-red-500 text-white">
<h1 class="text-5xl font-bold mb-4">We Fix. You Relax.</h1>
<p class="text-lg mb-6">Laptop & Phone Repairs | Affordable Devices | ICT Solutions</p>

<div class="flex justify-center space-x-4">
    {{-- Primary CTA for existing repairs --}}
    <a href="/repair/book" class="bg-white text-blue-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-200 transition duration-150 shadow-lg">
        Track Your Repair
    </a>

    {{-- Authentication links removed from here, as requested --}}

    @auth
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="bg-green-600 px-6 py-3 rounded-full font-semibold hover:bg-green-700 transition duration-150 shadow-lg">
                Go to Admin Dashboard
            </a>
        @endif
    @endauth
</div>


</section>

<section class="max-w-6xl mx-auto py-16 grid md:grid-cols-3 gap-8 text-center">

<!-- EXPERT REPAIRS -->
<div class="p-6 bg-white shadow-xl rounded-lg flex flex-col justify-between">
    <div>
        <img src="{{ asset('images/phone_repair.jpg') }}" class="mx-auto mb-4 rounded-lg h-48 w-full object-cover" alt="Phone Repair" />
        <h3 class="font-bold text-xl mb-2 text-gray-800">Expert Repairs</h3>
        <p class="text-gray-600 mb-6">We fix phones, laptops, and gadgets using top-quality parts and skilled technicians.</p>
    </div>
    <a href="/services/book" class="mt-auto block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-150">
        Book a Repair
    </a>
</div>

<!-- DEVICE SALES -->
<div class="p-6 bg-white shadow-xl rounded-lg flex flex-col justify-between">
    <div>
        <img src="{{ asset('images/laptop-sales.jpg') }}" class="mx-auto mb-4 rounded-lg h-48 w-full object-cover" alt="Device Sales"/>
        <h3 class="font-bold text-xl mb-2 text-gray-800">Device Sales</h3>
        <p class="text-gray-600 mb-6">Buy reliable smartphones and laptops at the best prices in Lagos.</p>
    </div>
    <a href="/store" class="mt-auto block bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700 transition duration-150">
        Visit Our Store
    </a>
</div>

<!-- ICT INSTALLATIONS -->
<div class="p-6 bg-white shadow-xl rounded-lg flex flex-col justify-between">
    <div>
        <img src="{{ asset('images/tower.jpg') }}" class="mx-auto mb-4 rounded-lg h-48 w-full object-cover" alt="ICT Installation"/>
        <h3 class="font-bold text-xl mb-2 text-gray-800">ICT Installations</h3>
        <p class="text-gray-600 mb-6">We handle network setups, CCTV, and office ICT infrastructure for businesses and schools.</p>
    </div>
    <a href="/contact" class="mt-auto block bg-gray-800 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-700 transition duration-150">
        Contact Us Now
    </a>
</div>


</section>

{{-- Repair Guides Section (New) --}}

<section class="max-w-6xl mx-auto py-16 text-center bg-gray-100 rounded-xl mb-16 shadow-inner">
<h2 class="text-3xl font-bold mb-4 text-gray-800">Repair Guides for Everything</h2>
<p class="text-lg text-gray-600 mb-6 max-w-2xl mx-auto">
Search our extensive database of step-by-step guides to diagnose and fix common device issues yourself.
It's like having a technician in your pocket!
</p>
<a href="/guides" class="bg-red-600 text-white px-8 py-3 rounded-full font-bold text-lg hover:bg-red-700 transition duration-150 shadow-lg">
Visit Our Guide Page
</a>
</section>

@endsection