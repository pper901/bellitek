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

        <!-- Left: Image Gallery (50% width on large screens) -->
        <div class="lg:w-1/2">
            
            <!-- Main Image Container -->
            <div class="w-full h-[400px] sm:h-[500px] overflow-hidden rounded-xl shadow-lg mb-4 bg-gray-100 flex items-center justify-center">
                @php
                    $mainImagePath = optional($product->images->first())->path ?? 'placeholders/default-product.png';
                    $mainImageUrl = asset('storage/' . $mainImagePath);
                @endphp
                <img id="main-image" 
                     src="{{ $mainImageUrl }}"
                     onerror="this.onerror=null; this.src='https://placehold.co/800x600/eeeeee/333333?text=No+Image';" 
                     alt="{{ $product->name }} Main Image"
                     class="w-full h-full object-contain transition-all duration-500 transform hover:scale-105"
                >
            </div>

            <!-- Thumbnails Carousel -->
            <div class="flex space-x-3 overflow-x-auto pb-2 px-1">
                @forelse($product->images as $image)
                    <img src="{{ asset('storage/'.$image->path) }}"
                         alt="{{ $product->name }} Thumbnail"
                         class="w-24 h-24 object-cover rounded-lg border-2 border-transparent 
                                 cursor-pointer hover:border-blue-600 transition flex-shrink-0"
                         onclick="document.getElementById('main-image').src=this.src; 
                                  document.querySelectorAll('.thumbnail').forEach(el => el.classList.remove('active-thumbnail')); 
                                  this.classList.add('active-thumbnail');"
                         onmouseover="this.click();"
                    >
                @empty
                    <!-- Placeholder for no thumbnails -->
                    <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-xs text-gray-500">
                        No Pics
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Product Details & Purchase (50% width on large screens) -->
        <div class="lg:w-1/2 flex flex-col">

            <div class="flex-grow">
                <!-- Title and Status -->
                <div class="mb-4">
                    <span class="text-sm font-semibold text-blue-600 uppercase">{{ $product->brand ?? 'Unbranded' }}</span>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mt-1 mb-2 leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                <!-- Price & Rating -->
                <div class="flex items-end justify-between border-b border-gray-100 pb-4 mb-4">
                    <p class="text-3xl sm:text-4xl text-green-700 font-extrabold">
                        ₦{{ number_format($product->price) }}
                    </p>
                    
                    <!-- Rating (Static Example) -->
                    <div class="flex items-center text-yellow-500 text-lg">
                        @for($i = 0; $i < 4; $i++) ★ @endfor
                        <span class="text-gray-400 text-sm ml-2">(4.0 / 5)</span>
                    </div>
                </div>

                <!-- Key Attributes -->
                <div class="flex flex-wrap gap-4 mb-6 text-sm font-medium">
                    <span @class([
                        'px-3 py-1 rounded-full text-white font-medium',
                        'bg-green-600' => $product->stock > 5,
                        'bg-yellow-600' => $product->stock > 0 && $product->stock <= 5,
                        'bg-red-600' => $product->stock <= 0,
                        ])>
                        @if($product->stock > 0)
                            In Stock ({{ $product->stock }})
                        @else
                            Out of Stock
                        @endif
                    </span>
                    <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 capitalize">
                        Condition: {{ $product->condition }}
                    </span>
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 capitalize">
                        Type: {{ $product->type }}
                    </span>
                </div>
            </div>
            
            <!-- Add to Cart / Login CTA -->
            <div class="mt-6 pt-4 border-t border-gray-100">
                <h3 class="text-lg font-semibold mb-3">Ready to Buy?</h3>
                
                @if($product->stock > 0)
                <!-- Add to Cart Form -->
               <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg 
                                shadow-md hover:bg-blue-700 transition duration-150 ease-in-out 
                                flex items-center justify-center space-x-2">
                        <svg width="30px" height="30px" viewBox="0 0 1024 1024" fill="#ffffff" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M343.376 717.726a7.984 7.984 0 0 1-7.966-7.388 7.99 7.99 0 0 1 7.374-8.576L945 656.472l61.52-384.42H224.056c-4.422 0-8-3.576-8-7.998a7.994 7.994 0 0 1 8-7.998h791.836a8.01 8.01 0 0 1 7.904 9.264l-63.986 399.918a8.022 8.022 0 0 1-7.312 6.716l-608.514 45.756c-0.202 0.016-0.406 0.016-0.608 0.016zM312.03 719.96a7.988 7.988 0 0 1-7.716-5.922L128.35 58.168a7.99 7.99 0 0 1 5.654-9.794c4.266-1.124 8.654 1.376 9.794 5.654l175.962 655.874a7.994 7.994 0 0 1-5.654 9.794 7.988 7.988 0 0 1-2.076 0.264z" fill="" /><path d="M343.382 717.758a7.992 7.992 0 0 1-7.716-5.92L151.078 26.182a8 8 0 0 1 15.45-4.154l184.586 685.654a8.012 8.012 0 0 1-5.646 9.81 7.97 7.97 0 0 1-2.086 0.266zM136.074 64.096H24.098a7.992 7.992 0 0 1-7.998-7.998 7.994 7.994 0 0 1 7.998-7.998h111.976a7.994 7.994 0 0 1 7.998 7.998 7.994 7.994 0 0 1-7.998 7.998z" fill="" /><path d="M158.804 32.104H24.098a7.994 7.994 0 0 1-7.998-7.998c0-4.422 3.576-8 7.998-8h134.708a7.994 7.994 0 0 1 7.998 8 7.998 7.998 0 0 1-8 7.998z" fill="" /><path d="M24.098 64.096C10.866 64.096 0.102 53.332 0.102 40.102c0-13.232 10.764-23.996 23.996-23.996a7.994 7.994 0 0 1 7.998 8 7.994 7.994 0 0 1-7.998 7.998 8.002 8.002 0 0 0-7.998 7.998 8.004 8.004 0 0 0 7.998 7.998 7.994 7.994 0 0 1 7.998 7.998 7.994 7.994 0 0 1-7.998 7.998zM951.904 368.034H301.508a7.994 7.994 0 0 1-7.998-7.998 7.994 7.994 0 0 1 7.998-7.998h650.396c4.422 0 8 3.576 8 7.998a7.994 7.994 0 0 1-8 7.998zM919.912 480.01H349.498c-4.42 0-7.998-3.576-7.998-7.998s3.578-7.998 7.998-7.998h570.414c4.422 0 7.998 3.576 7.998 7.998s-3.576 7.998-7.998 7.998zM887.918 591.988H381.492a7.994 7.994 0 0 1-7.998-8 7.994 7.994 0 0 1 7.998-7.998h506.426a7.994 7.994 0 0 1 7.998 7.998c0 4.422-3.576 8-7.998 8zM296.034 815.942a7.9 7.9 0 0 1-3.468-0.796 7.982 7.982 0 0 1-3.724-10.67l47.35-98.184c1.912-3.966 6.694-5.624 10.678-3.732a8.004 8.004 0 0 1 3.724 10.684l-47.35 98.184a7.986 7.986 0 0 1-7.21 4.514zM264.36 815.942a7.89 7.89 0 0 1-3.466-0.796 7.98 7.98 0 0 1-3.726-10.67l47.35-98.184c1.914-3.966 6.694-5.624 10.678-3.732a8.006 8.006 0 0 1 3.726 10.684l-47.35 98.184a7.988 7.988 0 0 1-7.212 4.514zM919.912 879.928h-575.88a7.994 7.994 0 0 1-7.998-7.998c0-4.422 3.576-8 7.998-8h575.88a7.994 7.994 0 0 1 7.998 8 7.994 7.994 0 0 1-7.998 7.998zM919.912 847.934h-575.88a7.992 7.992 0 0 1-7.998-7.998 7.994 7.994 0 0 1 7.998-7.998h575.88a7.994 7.994 0 0 1 7.998 7.998 7.992 7.992 0 0 1-7.998 7.998z" fill="" /><path d="M344.032 879.928c-48.514 0-87.982-32.292-87.982-71.986a7.994 7.994 0 0 1 7.998-7.998 7.994 7.994 0 0 1 7.998 7.998c0 30.87 32.292 55.988 71.986 55.988a7.994 7.994 0 0 1 7.998 8 7.994 7.994 0 0 1-7.998 7.998z" fill="" /><path d="M344.032 847.934c-26.432 0-55.988-17.106-55.988-39.992a7.994 7.994 0 0 1 7.998-7.998 7.994 7.994 0 0 1 7.998 7.998c0 11.67 20.558 23.996 39.992 23.996a7.994 7.994 0 0 1 7.998 7.998 7.994 7.994 0 0 1-7.998 7.998zM919.912 879.928a7.994 7.994 0 0 1-7.998-7.998c0-4.422 3.576-8 7.998-8a8 8 0 0 0 7.998-7.996c0-4.406-3.592-8-7.998-8a7.992 7.992 0 0 1-7.998-7.998 7.994 7.994 0 0 1 7.998-7.998c13.23 0 23.994 10.764 23.994 23.996 0 13.23-10.764 23.994-23.994 23.994zM855.926 623.98a7.994 7.994 0 0 1-7.998-7.998v-287.94a7.994 7.994 0 0 1 7.998-7.998 7.994 7.994 0 0 1 7.998 7.998v287.942a7.994 7.994 0 0 1-7.998 7.996zM743.948 623.98a7.992 7.992 0 0 1-7.996-7.998v-287.94a7.992 7.992 0 0 1 7.996-7.998c4.422 0 8 3.578 8 7.998v287.942a7.994 7.994 0 0 1-8 7.996zM631.97 623.98a7.994 7.994 0 0 1-7.998-7.998v-287.94a7.994 7.994 0 0 1 7.998-7.998c4.422 0 8 3.578 8 7.998v287.942a7.994 7.994 0 0 1-8 7.996zM519.994 623.98a7.994 7.994 0 0 1-7.998-7.998v-287.94a7.994 7.994 0 0 1 7.998-7.998c4.422 0 8 3.578 8 7.998v287.942a7.994 7.994 0 0 1-8 7.996zM408.018 623.98a7.994 7.994 0 0 1-7.998-7.998v-287.94a7.994 7.994 0 0 1 7.998-7.998 7.994 7.994 0 0 1 7.998 7.998v287.942a7.992 7.992 0 0 1-7.998 7.996zM400.02 1007.9c-35.282 0-63.986-28.698-63.986-63.986 0-6.216 0.89-12.374 2.654-18.278a8 8 0 0 1 9.944-5.376 7.978 7.978 0 0 1 5.382 9.938 48.29 48.29 0 0 0-1.984 13.714c0 26.462 21.528 47.99 47.99 47.99s47.99-21.528 47.99-47.99c0-4.672-0.672-9.28-1.984-13.714a7.978 7.978 0 0 1 5.38-9.938 7.972 7.972 0 0 1 9.944 5.376 63.764 63.764 0 0 1 2.656 18.278c0 35.29-28.706 63.986-63.986 63.986z" fill="" /><path d="M400.02 967.91c-17.988 0-35.804-42.118-47.576-77.454a7.988 7.988 0 0 1 5.052-10.108 7.968 7.968 0 0 1 10.116 5.044c11.022 33.056 26.442 64.176 32.986 66.596 5.388-2.42 20.808-33.54 31.828-66.596a7.968 7.968 0 0 1 10.116-5.044 7.984 7.984 0 0 1 5.054 10.108c-11.772 35.336-29.588 77.454-47.576 77.454zM815.934 1007.9c-35.274 0-63.986-28.698-63.986-63.986 0-6.232 0.906-12.388 2.656-18.292a8.026 8.026 0 0 1 9.964-5.36 8.024 8.024 0 0 1 5.376 9.954c-1.328 4.42-2 9.028-2 13.7 0 26.462 21.528 47.99 47.99 47.99s47.99-21.528 47.99-47.99c0-4.672-0.656-9.28-1.984-13.714a8.032 8.032 0 0 1 5.39-9.954 8.006 8.006 0 0 1 9.95 5.406 64.284 64.284 0 0 1 2.64 18.262c0 35.288-28.712 63.984-63.986 63.984z" fill="" /><path d="M815.934 967.91c-17.996 0-35.804-42.118-47.584-77.454a8 8 0 0 1 5.062-10.108 7.988 7.988 0 0 1 10.122 5.044c11.014 33.056 26.434 64.176 32.978 66.596 5.388-2.42 20.806-33.54 31.822-66.596a7.982 7.982 0 0 1 10.122-5.044 7.994 7.994 0 0 1 5.062 10.108c-11.78 35.336-29.588 77.454-47.584 77.454z" fill="" /></svg>

                        <span>Add to Cart</span>
                    </button>
                </form>
                @else
                    <!-- Out of Stock Message -->
                    <button disabled class="w-full bg-gray-400 text-white py-4 rounded-xl text-xl font-bold cursor-not-allowed">
                        Currently Out of Stock
                    </button>
                @endif
            </div>
        </div>

    </div>
</div>


<!-- Product Tabs: Description, Specs, Content, Reviews -->
<div class="bg-white rounded-2xl shadow-xl p-6 lg:p-10">
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex space-x-6 overflow-x-auto pb-1" role="tablist">
            <button id="tab-description" data-tab="description" class="tab-button text-lg font-semibold pb-3 text-blue-600 border-b-2 border-blue-600 transition duration-150 flex-shrink-0" role="tab" aria-selected="true">
                Description
            </button>
            @if($product->specification)
            <button id="tab-specs" data-tab="specs" class="tab-button text-lg font-semibold pb-3 text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition duration-150 flex-shrink-0" role="tab" aria-selected="false">
                Specifications
            </button>
            @endif
            @if($product->content)
            <button id="tab-content" data-tab="content" class="tab-button text-lg font-semibold pb-3 text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition duration-150 flex-shrink-0" role="tab" aria-selected="false">
                In the Box
            </button>
            @endif
            <!-- New Reviews Tab -->
            <button id="tab-reviews" data-tab="reviews" class="tab-button text-lg font-semibold pb-3 text-gray-500 hover:text-gray-700 border-b-2 border-transparent transition duration-150 flex-shrink-0" role="tab" aria-selected="false">
                Reviews (0)
            </button>
        </nav>
    </div>

    <!-- Tab Content Containers -->
    <div id="tab-content-container">
        <!-- Description Panel (Default active) -->
        <div id="panel-description" class="tab-panel active-panel" role="tabpanel">
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($product->description ?? 'No detailed description available for this product.')) !!}
            </div>
        </div>

        <!-- Specifications Panel -->
        @if($product->specification)
        <div id="panel-specs" class="tab-panel hidden" role="tabpanel">
            <div class="prose max-w-none text-gray-700">
                <h3 class="font-bold text-xl mb-3">Technical Specifications</h3>
                {!! nl2br(e($product->specification)) !!}
            </div>
        </div>
        @endif

        <!-- Content Panel -->
        @if($product->content)
        <div id="panel-content" class="tab-panel hidden" role="tabpanel">
            <div class="prose max-w-none text-gray-700">
                <h3 class="font-bold text-xl mb-3">What's Included in the Package:</h3>
                {!! nl2br(e($product->content)) !!}
            </div>
        </div>
        @endif

        <!-- Reviews Panel (Placeholder) -->
        <div id="panel-reviews" class="tab-panel hidden" role="tabpanel">
            <div class="p-4 border rounded-lg bg-gray-50">
                <h3 class="text-xl font-bold mb-3">Customer Reviews</h3>
                <p class="text-gray-600">No reviews have been submitted for this product yet. Be the first!</p>
                <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Write a Review</button>
            </div>
        </div>
    </div>
</div>


<!-- Related Products Horizontal Scroll -->
@if(isset($related) && $related->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">You Might Also Need</h2>
        <div class="flex space-x-6 overflow-x-auto pb-4 -mx-2 sm:mx-0">
            @foreach($related as $item)
                <a href="{{ route('store.product', $item->slug) }}" 
                   class="bg-white p-4 rounded-xl shadow-md min-w-[200px] hover:shadow-lg transition flex-shrink-0 border border-gray-100 transform hover:scale-[1.02]">
                    
                    <img src="{{ asset('storage/'.optional($item->images->first())->path) }}" 
                         onerror="this.onerror=null; this.src='https://placehold.co/400x300/f3f4f6/333333?text=Related';"
                         class="w-full h-32 object-cover rounded-lg mb-3" alt="{{ $item->name }}">
                    
                    <h3 class="font-semibold text-gray-800 text-base line-clamp-2">{{ $item->name }}</h3>
                    <p class="text-lg text-green-700 font-extrabold mt-1">₦{{ number_format($item->price) }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endif


</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanels = document.querySelectorAll('.tab-panel');

    function activateTab(tabId) {
        // Deactivate all buttons
        tabButtons.forEach(button => {
            button.classList.remove('text-blue-600', 'border-blue-600');
            button.classList.add('text-gray-500', 'hover:text-gray-700', 'border-transparent');
            button.setAttribute('aria-selected', 'false');
        });

        // Hide all panels
        tabPanels.forEach(panel => {
            panel.classList.add('hidden');
        });

        // Activate selected button & panel
        const activeButton = document.getElementById(`tab-${tabId}`);
        const activePanel = document.getElementById(`panel-${tabId}`);

        if (activeButton && activePanel) {
            activeButton.classList.add('text-blue-600', 'border-blue-600');
            activeButton.classList.remove('text-gray-500', 'hover:text-gray-700', 'border-transparent');
            activeButton.setAttribute('aria-selected', 'true');

            activePanel.classList.remove('hidden');
        }
    }

    // Setup event listener on each tab button
    tabButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            activateTab(e.currentTarget.dataset.tab);
        });
    });

    // Initialize default or URL-tab
    if (tabButtons.length > 0) {
        const hash = window.location.hash.substring(1);
        if (hash && document.getElementById(`tab-${hash}`)) {
            activateTab(hash);
        } else {
            activateTab('description');
        }
    }

    // Mark first thumbnail as active
    const firstThumbnail = document.querySelector('.thumbnail');
    if (firstThumbnail) {
        firstThumbnail.classList.add('border-blue-600');
    }
});
</script>


@endsection