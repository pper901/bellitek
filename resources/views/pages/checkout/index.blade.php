@extends('layouts.app')

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



<div class="container mx-auto py-6">
    <h2 class="text-2xl font-bold mb-4">Checkout</h2>

    <form method="POST" action="{{ route('checkout.placeOrder') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Left -->
            <div class="space-y-4 p-4 bg-white shadow rounded">

                <h3 class="font-bold text-lg">Customer Details</h3>

                <input type="text" name="customer_name" class="form-input" placeholder="Full Name" required>
                <input type="text" name="customer_phone" class="form-input" placeholder="Phone Number" required>
                <input type="email" name="customer_email" class="form-input" placeholder="Email" required>

                <h3 class="font-bold text-lg mt-4">Delivery Address</h3>

                <input type="text" name="address_line" class="form-input" placeholder="Address" required>
                <input type="text" name="city" class="form-input" placeholder="City" id="city" required>
                <input type="text" name="state" class="form-input" placeholder="State" id="state" required>

                <button type="button" id="calc-shipping" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Calculate Shipping
                </button>

                <input type="hidden" name="shipping_amount" id="shipping_amount">
                <p id="shippingDisplay" class="text-green-600 font-semibold"></p>
            </div>

            <!-- Right -->
            <div class="p-4 bg-white shadow rounded">
                <h3 class="font-bold text-lg mb-3">Order Summary</h3>

                @foreach ($cart as $item)
                    <p>{{ $item->product->name }} x {{ $item->qty }} — ₦{{ number_format($item->product->price * $item->qty) }}</p>
                @endforeach
            </div>

        </div>

        <button class="mt-6 bg-green-600 text-white px-6 py-3 rounded">Place Order</button>

    </form>
</div>

<script>
document.getElementById('calc-shipping').addEventListener('click', () => {

    let state = document.getElementById('state').value;
    let city = document.getElementById('city').value;

    fetch("{{ route('checkout.shippingRate') }}", {
        method: "POST",
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ state, city })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('shipping_amount').value = data.rate;
        document.getElementById('shippingDisplay').innerText =
            "Shipping Fee: ₦" + data.rate.toLocaleString();
    });
});
</script>
@endsection
