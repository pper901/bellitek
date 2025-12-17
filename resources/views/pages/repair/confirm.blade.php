@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-4">

    {{-- ---------------------------------------------------------------------------------------------------------------- --}}
    {{-- 💡 MESSAGE HANDLING --}}
    {{-- ---------------------------------------------------------------------------------------------------------------- --}}
    @if (session('error'))
        <x-message-alert type="error" :message="session('error')" />
    @elseif (session('success'))
        <x-message-alert type="success" :message="session('success')" />
    @endif
    {{-- ---------------------------------------------------------------------------------------------------------------- --}}

    <h1 class="text-2xl font-bold mb-4">Confirm Repair & Pay</h1>

    <p>Device: <strong>{{ $repair->device_type }} - {{ $repair->issue }}</strong></p>
    <p>Delivery Option: <strong>{{ ucfirst($repair->delivery_option) }}</strong></p>
    <p>Shipping Cost: <strong>${{ $repair->shipping_cost }}</strong></p>

    <form action="{{ route('repair.pay', $repair->id) }}" method="POST">
        @csrf
        <button type="submit" class="bg-blue-600 text-white p-2 mt-4">Pay Now & Book Repair</button>
    </form>
</div>
@endsection

