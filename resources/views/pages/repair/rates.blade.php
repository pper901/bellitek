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

    <h1 class="text-2xl font-bold mb-4">Select Shipping Rate</h1>

    <p class="mb-2">For your device: <strong>{{ $repair->device_type }} - {{ $repair->issue }}</strong></p>

    @if(count($rates) > 0)
    <form action="{{ route('repair.selectRate', $repair->id) }}" method="POST">
        @csrf
        @foreach($rates as $rate)
        <div class="border p-2 mb-2">
            <input type="radio" name="selected_rate" value="{{ $rate['service_code'] }}" required>
            <strong>{{ $rate['courier_name'] }} - {{ $rate['service_name'] }}</strong>
            <span class="float-right">${{ $rate['amount'] }}</span>
        </div>
        @endforeach
        <button type="submit" class="bg-green-600 text-white p-2 mt-2">Confirm & Proceed to Payment</button>
    </form>
    @else
    <p>No shipping rates available. Try again later.</p>
    @endif
</div>
@endsection
