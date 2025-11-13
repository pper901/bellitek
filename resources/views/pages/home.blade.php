@extends('layouts.app')

@section('content')
<section class="text-center py-20 bg-gradient-to-r from-blue-600 to-red-500 text-white">
  <h1 class="text-5xl font-bold mb-4">We Fix. You Relax.</h1>
  <p class="text-lg mb-6">Laptop & Phone Repairs | Affordable Devices | ICT Solutions</p>

  <div class="flex justify-center space-x-4">
    <a href="/track" class="bg-white text-blue-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-200 transition">
      Track Your Repair
    </a>

    @guest
      <a href="{{ route('login') }}" class="border border-white px-6 py-3 rounded-full font-semibold hover:bg-white hover:text-blue-700 transition">
        Login
      </a>
      <a href="{{ route('register') }}" class="bg-red-700 px-6 py-3 rounded-full font-semibold hover:bg-red-800 transition">
        Sign Up
      </a>
    @endguest

    @auth
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="bg-green-600 px-6 py-3 rounded-full font-semibold hover:bg-green-700 transition">
            Go to Admin Dashboard
        </a>
    @else
        <a href="{{ route('user.dashboard') }}" class="bg-green-600 px-6 py-3 rounded-full font-semibold hover:bg-green-700 transition">
            Go to Dashboard
        </a>
    @endif
@endauth
  </div>
</section>

<section class="max-w-6xl mx-auto py-16 grid md:grid-cols-3 gap-8 text-center">
  <div class="p-6 bg-white shadow rounded-lg">
    <img src="{{ asset('images/phone_repair.jpg') }}" class="mx-auto mb-4 rounded-lg" alt="Phone Repair" />
    <h3 class="font-bold text-lg mb-2">Expert Repairs</h3>
    <p>We fix phones, laptops, and gadgets using top-quality parts and skilled technicians.</p>
  </div>

  <div class="p-6 bg-white shadow rounded-lg">
    <img src="{{ asset('images/laptop-sales.jpg') }}" class="mx-auto mb-4 rounded-lg" alt="Device Sales"/>
    <h3 class="font-bold text-lg mb-2">Device Sales</h3>
    <p>Buy reliable smartphones and laptops at the best prices in Lagos.</p>
  </div>

  <div class="p-6 bg-white shadow rounded-lg">
    <img src="{{ asset('images/tower.jpg') }}" class="mx-auto mb-4 rounded-lg" alt="ICT Installation"/>
    <h3 class="font-bold text-lg mb-2">ICT Installations</h3>
    <p>We handle network setups, CCTV, and office ICT infrastructure for businesses.</p>
  </div>
</section>
@endsection
