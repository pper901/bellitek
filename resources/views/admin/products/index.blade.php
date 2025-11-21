@extends('admin.layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Products</h1>

<div class="flex justify-between items-center mb-4">
    <a href="{{ route('admin.products.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded">
        + Create New Product
    </a>

    {{-- Search --}}
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search product..."
               class="border px-3 py-2 rounded w-64">

        <select name="type" class="border px-3 py-2 rounded">
            <option value="">All Types</option>
            <option value="tool" {{ request('type')=='tool'?'selected':'' }}>Tools</option>
            <option value="part" {{ request('type')=='part'?'selected':'' }}>Parts</option>
            <option value="device" {{ request('type')=='device'?'selected':'' }}>Devices</option>
        </select>

        <select name="status" class="border px-3 py-2 rounded">
            <option value="">All Status</option>
            <option value="available" {{ request('status')=='available'?'selected':'' }}>Available</option>
            <option value="in_cart" {{ request('status')=='in_cart'?'selected':'' }}>In Cart</option>
            <option value="sold" {{ request('status')=='sold'?'selected':'' }}>Sold</option>
        </select>

        <button class="bg-gray-700 text-white px-4 rounded">Filter</button>
    </form>
</div>

<table class="w-full border-collapse">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-2">Image</th>
            <th class="p-2">Name</th>
            <th class="p-2">Price</th>
            <th class="p-2">Qty</th>
            <th class="p-2">Type</th>
            <th class="p-2">Status</th>
            <th class="p-2">User</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($products as $product)
        <tr class="border-b">

            <td class="p-2">
                @if ($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                         class="w-16 h-16 object-cover rounded">
                @endif
            </td>

            <td class="p-2 font-semibold">{{ $product->name }}</td>
            <td class="p-2">₦{{ number_format($product->price) }}</td>
            <td class="p-2">{{ $product->quantity }}</td>

            <td class="p-2">{{ ucfirst($product->type) }}</td>

            <td class="p-2">
                @php
                    $statusClasses = [
                        'available' => 'bg-green-100 text-green-800',
                        'in_cart'   => 'bg-yellow-100 text-yellow-800',
                        'sold_out'  => 'bg-red-100 text-red-800',
                    ];

                    $badgeClass = $statusClasses[$product->status] ?? 'bg-gray-100 text-gray-800';
                @endphp

                <span class="px-2 py-1 rounded text-sm {{ $badgeClass }}">
                    {{ $product->status }}
                </span>
            </td>

            <td class="p-2">
                @if ($product->user)
                    {{ $product->user->email }}
                @else
                    <span class="text-gray-500">None</span>
                @endif
            </td>

            <td class="p-2 flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}"
                   class="text-blue-600">Edit</a>

                @if (!$product->deleted_at)
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="text-red-600">Delete</button>
                </form>
                @else
                <form action="{{ route('admin.products.restore', $product->id) }}" method="POST">
                    @csrf
                    <button class="text-green-600">Restore</button>
                </form>
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

<div class="mt-4">
    {{ $products->links() }}
</div>

@endsection
