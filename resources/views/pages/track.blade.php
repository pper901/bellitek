@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Track or Submit Repair</h2>

@if(session('success'))
<div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('track.submit') }}" class="space-y-4 max-w-lg">
  @csrf
  <input type="text" name="customer_name" placeholder="Full Name" class="w-full border p-2 rounded" required>
  <input type="text" name="device_type" placeholder="Device Type" class="w-full border p-2 rounded" required>
  <input type="text" name="issue" placeholder="Describe the Issue" class="w-full border p-2 rounded" required>
  <input type="text" name="contact" placeholder="Phone or Email" class="w-full border p-2 rounded" required>
  <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">Submit</button>
</form>
@endsection
