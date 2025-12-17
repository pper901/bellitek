@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <a href="{{ route('admin.warehouse.create') }}" class="bg-white p-6 rounded shadow block hover:bg-gray-50 transition">
        <h2 class="text-lg font-bold mb-2">Warehouse</h2>
        <p class="text-gray-500">Set sender address for ShipBubble.</p>
    </a>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-2">Guides</h2>
        <p class="text-gray-500">Upload and manage repair guides.</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-2">Products</h2>
        <p class="text-gray-500">Manage your store items.</p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-lg font-bold mb-2">Repairs</h2>
        <p class="text-gray-500">Track repair progress and updates.</p>
    </div>

</div>
@endsection
