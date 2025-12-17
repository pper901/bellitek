@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6">Account Details</h1>

    <div class="bg-white p-6 rounded-lg shadow border">
        <h2 class="font-semibold text-lg mb-4">Personal Information</h2>

        <p><strong>Name: </strong>{{ $user->name }}</p>
        <p><strong>Email: </strong>{{ $user->email }}</p>

        <hr class="my-4">

        <h2 class="font-semibold text-lg mb-4">Address Details</h2>

        @if($address)
            <p><strong>Street:</strong> {{ $address->street }}</p>
            <p><strong>City:</strong> {{ $address->city }}</p>
            <p><strong>State:</strong> {{ $address->state }}</p>
            <p><strong>Country:</strong> {{ $address->country }}</p>
            <p><strong>Postal Code:</strong> {{ $address->postal_code }}</p>
            <p><strong>Phone Number:</strong> {{ $address->phonenumber }}</p>

            <button id="editBtn" 
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Edit Address
            </button>
        @else
            <p class="text-gray-600">No address added yet.</p>
            <button id="editBtn" 
                class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Add Address
            </button>
        @endif

        {{-- Update Form (Hidden by Default) --}}
        <form id="editForm" 
              action="{{ route('account.updateAddress') }}" 
              method="POST"
              class="mt-6 hidden bg-gray-50 p-4 border rounded">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Street</label>
                    <input type="text" name="street" value="{{ $address->street ?? '' }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-medium">City</label>
                    <input type="text" name="city" value="{{ $address->city ?? '' }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-medium">State</label>
                    <input type="text" name="state" value="{{ $address->state ?? '' }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-medium">Country</label>
                    <input type="text" name="country" value="{{ $address->country ?? '' }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-medium">Postal Code</label>
                    <input type="text" name="postal_code" value="{{ $address->postal_code ?? '' }}"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-medium">Phone Number</label>
                    <input type="text" name="phonenumber" value="{{ $address->phonenumber ?? '' }}"
                        class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <button type="submit" 
                class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                Save Changes
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('editBtn').addEventListener('click', () => {
        document.getElementById('editForm').classList.toggle('hidden');
    });
</script>
@endsection
