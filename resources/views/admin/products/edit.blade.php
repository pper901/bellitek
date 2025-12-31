@extends('admin.layout')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-xl rounded-xl">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-bold text-gray-800">Edit Product</h1>
        <a href="{{ route('admin.warehouse.index') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {{-- Type --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Product Type</label>
                <select name="type" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="tool" @selected($product->type == 'tool')>Tool</option>
                    <option value="part" @selected($product->type == 'part')>Part</option>
                    <option value="device" @selected($product->type == 'device')>Device</option>
                </select>
            </div>

            {{-- Category --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Category</label>
                <input name="category" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('category', $product->category) }}">
            </div>

            {{-- Brand --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Brand</label>
                <input name="brand" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('brand', $product->brand) }}">
            </div>

            {{-- Condition --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Condition</label>
                <select name="condition" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="new" @selected($product->condition == 'new')>New</option>
                    <option value="fairly_used" @selected($product->condition == 'fairly_used')>Fairly Used</option>
                </select>
            </div>

            {{-- Quantity (The field you specifically asked for) --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Quantity in Stock</label>
                <input type="number" name="quantity" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('quantity', $product->quantity) }}">
            </div>

            {{-- Weight --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Weight (kg)</label>
                <input type="number" step="0.01" name="weight" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('weight', $product->weight) }}" required>
            </div>

            {{-- Purchase Price --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Purchase Price (₦)</label>
                <input type="number" name="purchase_price" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('purchase_price', $product->purchase_price) }}">
            </div>

            {{-- Selling Price --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Selling Price (₦)</label>
                <input type="number" name="price" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('price', $product->price) }}">
            </div>

            {{-- Status --}}
            <div>
                <label class="block font-semibold text-gray-700 mb-1">Display Status</label>
                <select name="status" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="available" @selected($product->status == 'available')>Available</option>
                    <option value="in_cart" @selected($product->status == 'in_cart')>In Cart</option>
                    <option value="sold" @selected($product->status == 'sold')>Sold</option>
                </select>
            </div>

            {{-- Full Width Fields --}}
            <div class="md:col-span-2 lg:col-span-3">
                <label class="block font-semibold text-gray-700 mb-1">Product Name</label>
                <input name="name" class="w-full border rounded-lg p-2.5 outline-none focus:ring-2 focus:ring-blue-500" 
                       value="{{ old('name', $product->name) }}">
            </div>

            <div class="md:col-span-1 lg:col-span-1">
                <label class="block font-semibold text-gray-700 mb-1">Description</label>
                <textarea name="description" class="w-full border rounded-lg p-2.5 h-32 focus:ring-2 focus:ring-blue-500">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="md:col-span-1 lg:col-span-1">
                <label class="block font-semibold text-gray-700 mb-1">Specifications</label>
                <textarea name="specification" class="w-full border rounded-lg p-2.5 h-32 focus:ring-2 focus:ring-blue-500">{{ old('specification', $product->specification) }}</textarea>
            </div>

            <div class="md:col-span-1 lg:col-span-1">
                <label class="block font-semibold text-gray-700 mb-1">Package Content</label>
                <textarea name="content" class="w-full border rounded-lg p-2.5 h-32 focus:ring-2 focus:ring-blue-500">{{ old('content', $product->content) }}</textarea>
            </div>
        </div>

        {{-- Image Gallery Logic --}}
        <div class="mt-8 bg-gray-50 p-6 rounded-xl border">
            <h3 class="text-lg font-bold mb-4">Existing Images (Check to Remove)</h3>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                @foreach($product->images as $image)
                    <div class="relative group">
                        <img src="{{ $image->path }}" class="w-full h-24 object-cover rounded-lg border shadow-sm">
                        <div class="absolute inset-0 bg-red-500 bg-opacity-20 opacity-0 group-hover:opacity-100 flex items-center justify-center rounded-lg">
                            <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="w-5 h-5 accent-red-600">
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                <label class="block font-semibold text-gray-700 mb-2">Upload More Images</label>
                <input type="file" name="images[]" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700">
            </div>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-lg font-bold text-lg hover:bg-indigo-700 transition shadow-lg">
            Update All Product Information
        </button>
    </form>
</div>
@endsection