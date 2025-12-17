@extends('admin.layout')

@section('content')


{{-- ---------------------------------------------------------------------------------------------------------------- --}}
{{-- 💡 MESSAGE HANDLING (Place this at the top so it loads early) 💡 --}}
{{-- ---------------------------------------------------------------------------------------------------------------- --}}
@if (session('error'))
    {{-- Call the component with type 'error' and the message from the session --}}
    <x-message-alert type="error" :message="session('error')" />
@elseif (session('success'))
    {{-- Call the component with type 'success' and the message from the session --}}
    <x-message-alert type="success" :message="session('success')" />
@endif
{{-- ---------------------------------------------------------------------------------------------------------------- --}}



<div class="container mt-4">
    <h2>Select Warehouse Sender Address (ShipBubble)</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.warehouse.store') }}">
        @csrf

        <label class="form-label">Choose Address:</label>

        <select name="address_code" class="form-control" required>
            <option value="">-- Select ShipBubble Address --</option>

            @foreach($addresses as $address)
                <option value="{{ $address['address_code'] }}"
                    {{ optional($current)->address_code == $address['address_code'] ? 'selected' : '' }}>
                    {{ $address['street'] }}, {{ $address['city'] }} ({{ $address['address_code'] }})
                </option>
            @endforeach
        </select>

        <button class="btn btn-primary mt-3">Save Warehouse Address</button>
    </form>
</div>
@endsection
