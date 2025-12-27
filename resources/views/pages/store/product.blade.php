@extends('layouts.app')

@section('content')

@if (session('error'))
    <x-message-alert type="error" :message="session('error')" />
@elseif (session('success'))
    <x-message-alert type="success" :message="session('success')" />
@endif

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">

    <!-- Breadcrumb -->
    <nav class="text-sm font-medium text-gray-500 mb-6 flex items-center space-x-2">
        <a href="{{ route('store.index') }}" class="hover:text-gray-700">Home</a>
        <span class="text-gray-400">/</span>
        <a href="{{ route('store.category', $product->category) }}" class="hover:text-gray-700 capitalize">
            {{ ucwords(str_replace('_', ' ', $product->category)) }}
        </a>
        <span class="text-gray-400">/</span>
        <span class="text-gray-900 line-clamp-1">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-xl p-6 lg:p-10 mb-12 border border-gray-100">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            <!-- Left: Image Gallery -->
            <div class="lg:w-1/2">
                <div class="w-full h-[400px] sm:h-[500px] overflow-hidden rounded-xl shadow-lg mb-4 bg-gray-100 flex items-center justify-center">
                    @php
                        $mainImagePath = optional($product->images->first())->path ?? 'placeholders/default-product.png';
                        $mainImageUrl = $mainImagePath;
                    @endphp
                    <img id="main-image" 
                         src="{{ $mainImageUrl }}"
                         onerror="this.onerror=null; this.src='https://placehold.co/800x600/eeeeee/333333?text=No+Image';" 
                         alt="{{ $product->name }}"
                         class="w-full h-full object-contain transition-all duration-500 transform hover:scale-105">
                </div>

                <div class="flex space-x-3 overflow-x-auto pb-2 px-1">
                    @forelse($product->images as $image)
                        <img src="{{ $image->path }}"
                             alt="Thumbnail"
                             class="thumbnail w-24 h-24 object-cover rounded-lg border-2 border-transparent cursor-pointer hover:border-blue-600 transition flex-shrink-0"
                             onclick="document.getElementById('main-image').src=this.src; 
                                      document.querySelectorAll('.thumbnail').forEach(el => el.classList.remove('border-blue-600')); 
                                      this.classList.add('border-blue-600');">
                    @empty
                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">No Pics</div>
                    @endforelse
                </div>
            </div>

            <!-- Right: Details -->
            <div class="lg:w-1/2 flex flex-col">
                <div class="flex-grow">
                    <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">{{ $product->brand ?? 'Unbranded' }}</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-1 mb-4 leading-tight">
                        {{ $product->name }}
                    </h1>

                    <div class="flex items-center justify-between border-y border-gray-100 py-6 mb-6">
                        <div>
                            <p class="text-4xl text-green-700 font-black">₦{{ number_format($product->price) }}</p>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center text-yellow-400 text-xl justify-end">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= round($ratingAvg) ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                                @endfor
                            </div>
                            <p class="text-gray-400 text-xs mt-1 font-bold uppercase tracking-tighter">
                                {{ number_format($ratingAvg, 1) }} / 5 ({{ $ratingCount }} Reviews)
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 mb-8">
                        <span @class([
                            'px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wide',
                            'bg-green-100 text-green-700' => $product->stock > 5,
                            'bg-yellow-100 text-yellow-700' => $product->stock > 0 && $product->stock <= 5,
                            'bg-red-100 text-red-700' => $product->stock <= 0,
                        ])>
                            {{ $product->stock > 0 ? "In Stock ($product->stock)" : "Out of Stock" }}
                        </span>
                        <span class="px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase">Condition: {{ $product->condition }}</span>
                    </div>

                    <div class="prose prose-sm text-gray-600 line-clamp-3 mb-8">
                        {{ $product->description }}
                    </div>
                </div>
                
                <div class="pt-6 border-t border-gray-100">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center space-x-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <span>Add to Cart</span>
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full bg-gray-100 text-gray-400 py-4 rounded-xl font-bold cursor-not-allowed uppercase tracking-widest">Currently Unavailable</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Unified Tabs Section -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 px-6 lg:px-10">
            <nav class="flex space-x-8 overflow-x-auto" role="tablist">
                <button id="tab-description" data-tab="description" class="tab-button py-5 text-sm font-bold uppercase tracking-widest border-b-2 border-blue-600 text-blue-600 transition">Description</button>
                @if($product->specification)
                    <button id="tab-specs" data-tab="specs" class="tab-button py-5 text-sm font-bold uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition">Specifications</button>
                @endif
                <button id="tab-reviews" data-tab="reviews" class="tab-button py-5 text-sm font-bold uppercase tracking-widest border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition">
                    Reviews ({{ $ratingCount }})
                </button>
            </nav>
        </div>

        <div class="p-6 lg:p-10">
            <!-- Description Panel -->
            <div id="panel-description" class="tab-panel active-panel">
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Specs Panel -->
            @if($product->specification)
                <div id="panel-specs" class="tab-panel hidden">
                    <div class="prose max-w-none text-gray-700">
                        <h3 class="text-xl font-bold mb-4">Technical Details</h3>
                        {!! nl2br(e($product->specification)) !!}
                    </div>
                </div>
            @endif

            <!-- Unified Reviews Panel -->
            <div id="panel-reviews" class="tab-panel hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Review List -->
                    <div class="lg:col-span-2 space-y-8">
                        @forelse($product->reviews as $review)
                            <div class="border-b border-gray-50 pb-8 last:border-0">
                                <div class="flex items-center mb-2">
                                    <div class="flex text-yellow-400 mr-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                        @endfor
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $review->user->name }}</span>
                                    <span class="mx-2 text-gray-300">•</span>
                                    <span class="text-gray-400 text-xs uppercase">{{ $review->created_at->format('M d, Y') }}</span>
                                </div>
                                <p class="text-gray-600 italic">"{{ $review->comment }}"</p>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-gray-50 rounded-xl">
                                <p class="text-gray-500 font-medium">No reviews yet. Be the first to rate this product!</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Review Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                            @auth
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Write a Review</h3>
                                <form method="POST" action="{{ route('product.review.store', $product->id) }}" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Rating</label>
                                        <select name="rating" required class="w-full bg-white border-gray-200 rounded-lg text-sm focus:ring-blue-500">
                                            <option value="5">5 Stars - Excellent</option>
                                            <option value="4">4 Stars - Good</option>
                                            <option value="3">3 Stars - Average</option>
                                            <option value="2">2 Stars - Poor</option>
                                            <option value="1">1 Star - Terrible</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Comment</label>
                                        <textarea name="comment" rows="4" required placeholder="Share your thoughts about this product..." 
                                                  class="w-full bg-white border-gray-200 rounded-lg text-sm focus:ring-blue-500"></textarea>
                                    </div>
                                    <button class="w-full bg-gray-900 text-white font-bold py-3 rounded-lg hover:bg-black transition">Submit Review</button>
                                </form>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-sm text-gray-600 mb-4 font-medium">Please login to share your experience with others.</p>
                                    <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-bold text-sm shadow-md hover:bg-blue-700 transition">Login Now</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    function activateTab(tabId) {
        tabButtons.forEach(button => {
            button.classList.remove('text-blue-600', 'border-blue-600');
            button.classList.add('text-gray-400', 'border-transparent');
        });

        tabPanels.forEach(panel => panel.classList.add('hidden'));

        const activeButton = document.getElementById(`tab-${tabId}`);
        const activePanel = document.getElementById(`panel-${tabId}`);

        if (activeButton && activePanel) {
            activeButton.classList.add('text-blue-600', 'border-blue-600');
            activeButton.classList.remove('text-gray-400', 'border-transparent');
            activePanel.classList.remove('hidden');
        }
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', (e) => activateTab(e.currentTarget.dataset.tab));
    });

    // Handle initial state and deep linking
    const hash = window.location.hash.substring(1);
    activateTab(hash && document.getElementById(`tab-${hash}`) ? hash : 'description');
});
</script>

@endsection