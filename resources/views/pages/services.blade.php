@extends('layouts.app')

@section('content')

<div class="w-full">

<!-- Hero Section for Services -->
<section class="text-center py-20 bg-gray-900 text-white shadow-xl">
    <div class="max-w-4xl mx-auto px-6">
        <h1 class="text-5xl font-extrabold mb-4">Your Tech Needs, Mastered.</h1>
        <p class="text-xl opacity-80 mb-8">
            From fixing cracked screens to building robust business networks, Bellitek offers reliable, expert service with guaranteed results.
        </p>
        <a href="/contact" class="bg-red-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-red-700 transition duration-300 shadow-2xl transform hover:scale-105">
            Get a Free Quote
        </a>
    </div>
</section>

<!-- Detailed Services Grid -->
<section class="max-w-7xl mx-auto py-16 px-6">
    <h2 class="text-4xl font-bold text-gray-900 text-center mb-12">Our Core Offerings</h2>
    
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- 1. Phone & Laptop Repairs -->
        <div class="bg-white p-6 rounded-xl shadow-xl transition duration-300 hover:shadow-2xl border-t-4 border-red-500">
            <div class="text-red-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Expert Device Repair</h3>
            <p class="text-gray-600 mb-4">
                Fast and reliable repairs for all major brands of smartphones, laptops, and tablets. We use genuine or high-quality parts.
            </p>
            <a href="/services/book" class="text-red-500 font-semibold hover:text-red-700 transition duration-150 flex items-center">
                Book Repair Now 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

        <!-- 2. System Upgrades & Installations -->
        <div class="bg-white p-6 rounded-xl shadow-xl transition duration-300 hover:shadow-2xl border-t-4 border-blue-500">
            <div class="text-blue-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.807 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.807 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">PC Performance & Upgrades</h3>
            <p class="text-gray-600 mb-4">
                Boost speed with RAM/SSD upgrades, OS installation, and software troubleshooting for peak productivity.
            </p>
            <a href="/contact" class="text-blue-500 font-semibold hover:text-blue-700 transition duration-150 flex items-center">
                Request Upgrade Quote
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

        <!-- 3. ICT Setup for Businesses -->
        <div class="bg-white p-6 rounded-xl shadow-xl transition duration-300 hover:shadow-2xl border-t-4 border-green-500">
            <div class="text-green-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h6" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Business ICT Solutions</h3>
            <p class="text-gray-600 mb-4">
                Complete networking, CCTV installation, server management, and IT infrastructure setup for offices and businesses.
            </p>
            <a href="/contact" class="text-green-500 font-semibold hover:text-green-700 transition duration-150 flex items-center">
                Consult Our Experts
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

        <!-- 4. Sales of Phones & Accessories -->
        <div class="bg-white p-6 rounded-xl shadow-xl transition duration-300 hover:shadow-2xl border-t-4 border-yellow-500">
            <div class="text-yellow-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold mb-2 text-gray-800">Device Sales & Accessories</h3>
            <p class="text-gray-600 mb-4">
                High-quality new and used devices, genuine accessories, and necessary peripherals, all quality-tested by us.
            </p>
            <a href="/store" class="text-yellow-500 font-semibold hover:text-yellow-700 transition duration-150 flex items-center">
                Visit Our Store
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
        
    </div>
</section>

<!-- Repair Process / Trust Section -->
<section class="bg-gray-100 py-16 px-6">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Our Simple, Trusted Repair Process</h2>
        <div class="grid md:grid-cols-3 gap-8 text-left">
            
            <div class="p-4 border-b-2 border-red-500">
                <p class="text-4xl font-extrabold text-red-500 mb-2">01</p>
                <h3 class="text-lg font-semibold text-gray-800">Drop Off / Pickup</h3>
                <p class="text-sm text-gray-600">Schedule a time or drop your device at our service center.</p>
            </div>
            
            <div class="p-4 border-b-2 border-red-500">
                <p class="text-4xl font-extrabold text-red-500 mb-2">02</p>
                <h3 class="text-lg font-semibold text-gray-800">Diagnosis & Quote</h3>
                <p class="text-sm text-gray-600">Our expert team quickly diagnoses the issue and provides a transparent cost estimate.</p>
            </div>
            
            <div class="p-4 border-b-2 border-red-500">
                <p class="text-4xl font-extrabold text-red-500 mb-2">03</p>
                <h3 class="text-lg font-semibold text-gray-800">Quick Fix & Collect</h3>
                <p class="text-sm text-gray-600">We fix your device and notify you when it's ready for collection or delivery.</p>
            </div>
            
        </div>
    </div>
</section>


</div>

@endsection