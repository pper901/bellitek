@extends('admin.layout')

@section('content')

<div class="mb-6 flex items-center gap-4">
<a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">← Back to Products</a>
<h1 class="text-3xl font-bold text-gray-800">Reviews for "{{ $product->name }}"</h1>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center">
<div class="text-sm text-gray-500 uppercase font-bold">Average Rating</div>
<div class="text-4xl font-bold text-gray-900 mt-2">
{{ number_format($product->reviews->avg('rating'), 1) ?? '0.0' }} / 5.0
</div>
<div class="flex justify-center text-yellow-400 mt-1">
@for($i = 1; $i <= 5; $i++)
<span class="text-xl">{{ $i <= round($product->reviews->avg('rating')) ? '★' : '☆' }}</span>
@endfor
</div>
</div>
<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-center">
<div class="text-sm text-gray-500 uppercase font-bold">Total Reviews</div>
<div class="text-4xl font-bold text-gray-900 mt-2">{{ $reviews->total() }}</div>
</div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
<table class="w-full border-collapse">
<thead class="bg-gray-50 border-b">
<tr>
<th class="p-4 text-left text-xs font-bold text-gray-600 uppercase">User</th>
<th class="p-4 text-left text-xs font-bold text-gray-600 uppercase">Rating</th>
<th class="p-4 text-left text-xs font-bold text-gray-600 uppercase">Comment</th>
<th class="p-4 text-left text-xs font-bold text-gray-600 uppercase">Date</th>
<th class="p-4 text-left text-xs font-bold text-gray-600 uppercase">Action</th>
</tr>
</thead>
<tbody class="divide-y divide-gray-100">
@forelse ($reviews as $review)
<tr class="hover:bg-gray-50 transition">
<td class="p-4">
<div class="font-medium text-gray-900">{{ $review->user->name ?? 'Anonymous' }}</div>
<div class="text-xs text-gray-500">{{ $review->user->email ?? '' }}</div>
</td>
<td class="p-4 text-yellow-500 font-bold">
{{ $review->rating }} ★
</td>
<td class="p-4 text-gray-600 max-w-md">
<p class="text-sm leading-relaxed">{{ $review->comment }}</p>
</td>
<td class="p-4 text-sm text-gray-500">
{{ $review->created_at->format('M d, Y') }}
</td>
<td class="p-4">
<form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Remove this review permanently?')">
@csrf @method('DELETE')
<button class="text-red-500 hover:text-red-700 font-medium text-sm">Delete</button>
</form>
</td>
</tr>
@empty
<tr>
<td colspan="5" class="p-8 text-center text-gray-400 italic">No reviews found for this product.</td>
</tr>
@endforelse
</tbody>
</table>
</div>

<div class="mt-6">
{{ $reviews->links() }}
</div>
@endsection