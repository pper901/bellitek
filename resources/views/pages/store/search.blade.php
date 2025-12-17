@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

<!-- Search Header & Summary -->
<div class="mb-8 border-b border-gray-200 pb-4">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
        Search Results
    </h1>
    <p class="text-xl text-gray-600">
        Showing {{ $products->total() }} results for: <span class="font-semibold text-blue-600">"{{ $query }}"</span>
    </p>
</div>

@if($products->count() == 0)
    <!-- Sophisticated Empty State -->
    <div class="flex flex-col items-center justify-center p-12 bg-gray-50 rounded-2xl border border-gray-200 shadow-inner">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20 h-20 text-gray-400 mb-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            No Exact Match Found
        </h2>
        <p class="text-gray-600 text-center max-w-lg">
            We couldn't find any products matching your search term. Please try refining your query or check for spelling errors.
        </p>
        <!-- Optional CTA -->
        <a href="{{ route('store.index') }}" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
            Explore the Store
        </a>
    </div>
@else
    <!-- Product Grid -->
    <div class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 lg:grid-cols-4 xl:gap-x-8">
        @foreach($products as $product)
        
        <a href="{{ route('store.product', $product->id) }}" 
           class="group block bg-white rounded-xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-[1.02] border border-gray-100 overflow-hidden">
            
            <!-- Product Image -->
            <div class="relative w-full h-48 bg-gray-100 overflow-hidden rounded-t-xl">
                @php
                    $imagePath = optional($product->images->first())->path;
                    $imageUrl = $imagePath ? asset('storage/'.$imagePath) : 'https://placehold.co/400x300/f3f4f6/333333?text=No+Image';
                @endphp

                <img src="{{ $imageUrl }}"
                     onerror="this.onerror=null; this.src='https://placehold.co/400x300/f3f4f6/333333?text=No+Image';"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover transition-all duration-300 group-hover:opacity-75 group-hover:scale-110">
            </div>
            
            <!-- Product Details -->
            <div class="p-4">
                <span class="text-xs font-semibold uppercase text-blue-500 line-clamp-1 mb-1">
                    {{ $product->brand ?? 'Unbranded' }}
                </span>
                
                <h3 class="text-lg font-bold text-gray-800 line-clamp-2 mb-2">
                    {{ $product->name }}
                </h3>
                
                <div class="flex items-center justify-between">
                    <p class="text-xl text-green-700 font-extrabold">
                        ₦{{ number_format($product->price) }}
                    </p>

                    <!-- Stock Status Tag -->
                    <span @class([
                        'text-xs font-medium px-2 py-0.5 rounded-full text-white',
                        'bg-green-600' => $product->stock > 5,
                        'bg-yellow-600' => $product->stock > 0 && $product->stock <= 5,
                        'bg-red-600' => $product->stock <= 0,
                    ])>
                        @if($product->stock > 0)
                            In Stock
                        @else
                            Sold Out
                        @endif
                    </span>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->links() }}
    </div>
@endif


</div>

@endsection