@extends('admin.layout')

@section('title', 'Warehouse Management')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-8">

    {{-- SECTION 1: CURRENT ACTIVE ADDRESS --}}
    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-6">
        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">Current Warehouse Origin</h2>
        
        @if($current)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs text-gray-400">Warehouse Name</p>
                    <p class="font-bold text-gray-800 text-lg">{{ $current->name }}</p>
                    
                    <p class="text-xs text-gray-400 mt-3">Contact Details</p>
                    <p class="text-gray-700">{{ $current->email }}</p>
                    <p class="text-gray-700">{{ $current->phone }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <p class="text-xs text-gray-400 mb-1">Full Pickup Address</p>
                    <p class="text-gray-800 leading-relaxed">
                        {{ $current->address }}<br>
                        {{ $current->city }}, {{ $current->state }}<br>
                        <span class="text-blue-600 text-xs font-mono">{{ $current->latitude }}, {{ $current->longitude }}</span>
                    </p>
                </div>
            </div>
        @else
            <div class="text-center py-4">
                <p class="text-gray-500 italic">No warehouse address found. Please create one below.</p>
            </div>
        @endif
    </div>

    <hr class="border-gray-200">

    {{-- SECTION 2: CREATE / UPDATE FORM --}}
    <div class="bg-white rounded-xl shadow-xl p-8 border border-gray-100">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Configure New Warehouse</h1>
            <p class="text-gray-500 text-sm">This address will be used as the "Pickup Point" for all ShipBubble deliveries.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.warehouse.storeAgain') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Warehouse Name</label>
                <input type="text" name="name" placeholder="e.g. Bellitek Ikeja Hub" required class="w-full border-gray-300 border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Contact Email</label>
                <input type="email" name="email" placeholder="logistics@yourstore.com" required class="w-full border-gray-300 border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Phone Number</label>
                <input type="text" name="phone" placeholder="080XXXXXXXX" required class="w-full border-gray-300 border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Street Address</label>
                <input type="text" name="address" placeholder="123 Shop Street, Off Allen Avenue" required class="w-full border-gray-300 border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">City</label>
                <input type="text" name="city" placeholder="Ikeja" required class="w-full border-gray-300 border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">State</label>
                <input type="text" name="state" placeholder="Lagos" required class="w-full border-gray-300 border px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="md:col-span-2 pt-4">
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white px-6 py-4 rounded-lg font-bold shadow-lg transition duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Save & Set as Primary Warehouse</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection