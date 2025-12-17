@extends('admin.layout')

@section('title', 'Create Warehouse Address')

@section('content')

<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Create Warehouse Address</h1>
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.warehouse.storeAgain') }}" method="POST" class="space-y-4">
    @csrf
    <input type="text" name="name" placeholder="Warehouse Name" required value="{{ old('name', $current->name ?? '') }}" class="w-full border px-3 py-2 rounded">
    <input type="email" name="email" placeholder="Email Address" required value="{{ old('email', $current->email ?? '') }}" class="w-full border px-3 py-2 rounded">
    <input type="text" name="phone" placeholder="Phone Number" required value="{{ old('phone', $current->phone ?? '') }}" class="w-full border px-3 py-2 rounded">
    <textarea name="address" placeholder="Full Address (Street, City, State, Country)" required class="w-full border px-3 py-2 rounded">{{ old('address', $current->address ?? '') }}</textarea>
    <input type="text" name="latitude" placeholder="Latitude (optional)" value="{{ old('latitude', $current->latitude ?? '') }}" class="w-full border px-3 py-2 rounded">
    <input type="text" name="longitude" placeholder="Longitude (optional)" value="{{ old('longitude', $current->longitude ?? '') }}" class="w-full border px-3 py-2 rounded">
    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold">Save Warehouse</button>
</form>

</div>
@endsection
