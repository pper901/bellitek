@extends('admin.layout')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="font-semibold">Product Name</label>
            <input type="text" name="name" class="w-full border p-2 rounded"
                   value="{{ old('name', $product->name) }}">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Purchased Price</label>
            <input type="number" name="purchase_price" class="w-full border p-2 rounded"
                   value="{{ old('purchase_price', $product->purchase_price) }}">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Price</label>
            <input type="number" name="price" class="w-full border p-2 rounded"
                   value="{{ old('price', $product->price) }}">
        </div>

        <div class="mb-4">
            <label class="font-semibold">Description</label>
            <textarea name="description" class="w-full border p-2 rounded">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="font-semibold">Status</label>
            <select name="status" class="w-full border p-2 rounded">
                <option value="available" @selected($product->status == 'available')>Available</option>
                <option value="in_cart" @selected($product->status == 'in_cart')>In Cart</option>
                <option value="sold" @selected($product->status == 'sold')>Sold</option>
            </select>
        </div>

        <h3 class="text-lg font-semibold mt-6 mb-2">Images</h3>

        <div class="grid grid-cols-3 gap-4 mb-4">
            @foreach($product->images as $image)
                <div class="relative p-1 border rounded">
                    <img src="{{ asset('storage/' . $image->path) }}" class="w-full h-32 object-cover rounded">

                    <label class="absolute top-1 left-1 bg-white p-1 rounded shadow text-xs">
                        <input type="checkbox" name="remove_images[]" value="{{ $image->id }}">
                        Remove
                    </label>
                </div>
            @endforeach
        </div>

        <div class="mb-4">
            <label class="font-semibold">Add New Images</label>
            <input type="file" name="images[]" multiple class="w-full border p-2 rounded">
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update Product
        </button>

    </form>
</div>
@endsection
