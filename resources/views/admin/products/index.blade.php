@extends('admin.layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">Products</h1>

<div class="flex justify-between items-center mb-4">
<a href="{{ route('admin.products.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
+ Create New Product
</a>

{{-- Search & Filters --}}
<form method="GET" class="flex gap-2">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Search product..."
           class="border px-3 py-2 rounded w-64 focus:ring-2 focus:ring-blue-500 outline-none">

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

    <button class="bg-gray-700 text-white px-4 rounded hover:bg-gray-800 transition">Filter</button>
</form>


</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
<table class="w-full border-collapse">
<thead class="bg-gray-100 border-b">
<tr>
<th class="p-3 text-left text-xs font-bold text-gray-600 uppercase">Image</th>
<th class="p-3 text-left text-xs font-bold text-gray-600 uppercase">Name & Rating</th>
<th class="p-3 text-left text-xs font-bold text-gray-600 uppercase">Price</th>
<th class="p-3 text-left text-xs font-bold text-gray-600 uppercase">Qty</th>
<th class="p-3 text-left text-xs font-bold text-gray-600 uppercase">Type</th>
<th class="p-3 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
<th class="p-3 text-left text-xs font-bold text-gray-600 uppercase">Actions</th>
</tr>
</thead>

    <tbody class="divide-y divide-gray-200">
        @foreach ($products as $product)
        <tr class="hover:bg-gray-50 transition">

            <td class="p-3">
                @if ($product->images->first())
                    <img src="{{ $product->images->first()->path }}"
                         class="w-12 h-12 object-cover rounded shadow-sm border">
                @else
                    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">No Img</div>
                @endif
            </td>

            <td class="p-3">
                <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                
                {{-- Rating Summary --}}
                @if($product->reviews_count > 0 || $product->reviews->count() > 0)
                    @php 
                        $avg = $product->reviews->avg('rating');
                        $count = $product->reviews->count();
                    @endphp
                    <div class="flex items-center mt-1">
                        <div class="flex text-yellow-400 text-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <span>{{ $i <= round($avg) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-500 ml-2">({{ $count }})</span>
                    </div>
                @else
                    <span class="text-xs text-gray-400 italic">No reviews</span>
                @endif
            </td>

            <td class="p-3 text-gray-700 font-medium">₦{{ number_format($product->price) }}</td>
            
            <td class="p-3">
                <span class="{{ $product->stock <= 5 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                    {{ $product->stock }}
                </span>
            </td>

            <td class="p-3">
                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 rounded text-gray-600">
                    {{ ucfirst($product->type) }}
                </span>
            </td>

            <td class="p-3">
                @php
                    $statusClasses = [
                        'available' => 'bg-green-100 text-green-800 border-green-200',
                        'in_cart'   => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'sold_out'  => 'bg-red-100 text-red-800 border-red-200',
                        'sold'      => 'bg-blue-100 text-blue-800 border-blue-200',
                    ];
                    $badgeClass = $statusClasses[$product->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                @endphp
                <span class="px-2 py-0.5 rounded-full text-xs font-medium border {{ $badgeClass }}">
                    {{ str_replace('_', ' ', ucfirst($product->status)) }}
                </span>
            </td>

            <td class="p-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="text-blue-600 hover:text-blue-900 font-medium text-sm">Edit</a>

                    <a href="{{ route('admin.products.reviews', $product) }}" 
                       class="text-purple-600 hover:text-purple-900 font-medium text-sm">Reviews</a>

                    {{-- Share Button --}}
                    <button type="button" 
                            onclick="openShareModal('{{ $product->name }}', '{{ url('/products/' . $product->id) }}')"
                            class="text-orange-600 hover:text-orange-900 font-medium text-sm">
                        Share
                    </button>

                    @if (!$product->deleted_at)
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:text-red-900 font-medium text-sm">Delete</button>
                    </form>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


</div>

{{-- Share Modal Structure --}}
<div id="shareModal"
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-2xl max-w-sm w-full overflow-hidden">
<div class="p-6">
<div class="flex justify-between items-center mb-4">
<h3 class="text-lg font-bold text-gray-900">Share Product</h3>
<button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
</div>

        <p id="shareProductName" class="text-sm text-gray-600 mb-6 truncate font-medium"></p>

        <div class="grid grid-cols-3 gap-4 mb-6 text-center">
            {{-- WhatsApp --}}
            <a id="shareWhatsApp" target="_blank" class="flex flex-col items-center gap-2 group cursor-pointer">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center group-hover:bg-green-200 transition">
                    <span class="text-green-600 text-xl font-bold">W</span>
                </div>
                <span class="text-xs text-gray-500">WhatsApp</span>
            </a>
            {{-- Facebook --}}
            <a id="shareFacebook" target="_blank" class="flex flex-col items-center gap-2 group cursor-pointer">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition">
                    <span class="text-blue-600 text-xl font-bold">F</span>
                </div>
                <span class="text-xs text-gray-500">Facebook</span>
            </a>
            {{-- X (Twitter) --}}
            <a id="shareTwitter" target="_blank" class="flex flex-col items-center gap-2 group cursor-pointer">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center group-hover:bg-gray-200 transition">
                    <span class="text-gray-900 text-xl font-bold">X</span>
                </div>
                <span class="text-xs text-gray-500">X</span>
            </a>
        </div>

        <div class="relative">
            <input type="text" id="shareUrlInput" readonly 
                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 pl-3 pr-20 text-xs focus:outline-none">
            <button onclick="copyShareUrl()" 
                    class="absolute right-1 top-1 bottom-1 bg-indigo-600 text-white px-3 rounded text-xs font-bold hover:bg-indigo-700 transition">
                Copy
            </button>
        </div>
        <p id="copyFeedback" class="text-green-600 text-[10px] mt-1 hidden text-center">Link copied to clipboard!</p>
    </div>
</div>


</div>

<div class="mt-6">
{{ $products->appends(request()->query())->links() }}
</div>
<script>
function openShareModal(name, url) {
    const modal = document.getElementById('shareModal');
    const nameEl = document.getElementById('shareProductName');
    const inputEl = document.getElementById('shareUrlInput');

    modal.classList.remove('hidden');
    modal.classList.add('flex');   // apply flex ONLY when visible
    document.body.style.overflow = 'hidden';

    nameEl.innerText = name;
    inputEl.value = url;

    const encodedUrl  = encodeURIComponent(url);
    const encodedText = encodeURIComponent("Check out " + name + " on our store!");

    document.getElementById('shareWhatsApp').href =
        `https://api.whatsapp.com/send?text=${encodedText} ${encodedUrl}`;

    document.getElementById('shareFacebook').href =
        `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;

    document.getElementById('shareTwitter').href =
        `https://twitter.com/intent/tweet?text=${encodedText}&url=${encodedUrl}`;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function copyShareUrl() {
    const input = document.getElementById('shareUrlInput');
    input.select();
    input.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(input.value).then(() => {
        const feedback = document.getElementById('copyFeedback');
        feedback.classList.remove('hidden');
        setTimeout(() => feedback.classList.add('hidden'), 2000);
    }).catch(err => {
        console.error('Copy failed', err);
    });
}

// Close modal on outside click
window.addEventListener('click', function (e) {
    const modal = document.getElementById('shareModal');
    if (e.target === modal) closeShareModal();
});
</script>


@endsection