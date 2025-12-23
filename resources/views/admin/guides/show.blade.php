@extends('admin.layout')

@section('title', 'View Guide: ' . $guide->model)

@section('content')

<div class="bg-white p-8 rounded-xl shadow-2xl space-y-8">

    <!-- Header and Actions -->
    <div class="flex justify-between items-center border-b pb-4">
        <h1 class="text-3xl font-extrabold text-gray-900">
            Guide: {{ $guide->brand }} {{ $guide->series }} ({{ $guide->model }})
        </h1>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.guides.edit', $guide) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 shadow-md">
                Edit Guide
            </a>

            {{-- SHARE BUTTONS --}}
            <div class="share-btns flex items-center space-x-3 bg-gray-50 p-2 rounded-lg border border-gray-200">
                <span class="text-xs font-bold text-gray-400 uppercase mr-1">Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($seo['url']) }}" target="_blank" class="text-blue-600 hover:text-blue-800"><i class="fab fa-facebook"></i> FB</a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($seo['url']) }}&text={{ urlencode($seo['title']) }}" target="_blank" class="text-blue-400 hover:text-blue-600"><i class="fab fa-twitter"></i> X</a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($seo['title'].' '.$seo['url']) }}" target="_blank" class="text-green-600 hover:text-green-800"><i class="fab fa-whatsapp"></i> WA</a>
            </div>
        </div>
    </div>


    <!-- Main Guide Details -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-gray-700">
        <div class="border-l-4 border-blue-500 pl-4">
            <p class="font-semibold text-sm uppercase text-gray-500">Device / Category</p>
            <p class="text-lg">{{ $guide->device }} / {{ $guide->category }}</p>
        </div>
        
        <div class="border-l-4 border-blue-500 pl-4">
            <p class="font-semibold text-sm uppercase text-gray-500">Brand / Series</p>
            <p class="text-lg">{{ $guide->brand }} / {{ $guide->series }}</p>
        </div>
        
        <div class="border-l-4 border-blue-500 pl-4">
            <p class="font-semibold text-sm uppercase text-gray-500">Reported Issue</p>
            <p class="text-lg">{{ $guide->issue }}</p>
        </div>
    </div>

    <!-- Troubleshooting Resources Section -->
    <div class="pt-6 border-t">
        <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Troubleshooting Resources ({{ $guide->resources->count() }})
        </h2>

        <div class="space-y-6">
            @forelse ($guide->resources as $resource)
                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50 shadow-sm transition duration-150 hover:shadow-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="font-bold text-lg text-red-600">Cause</p>
                            <p class="text-base text-gray-800">{{ $resource->cause ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-green-600">Solution</p>
                            <p class="text-base text-gray-800">{{ $resource->solution ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <p class="font-bold text-lg text-blue-600 mb-2">Detailed Steps</p>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($resource->details ?? 'No detailed steps provided.')) !!}
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic p-4 border border-dashed rounded-lg text-center">No causes or solutions added.</p>
            @endforelse
        </div>
    </div>

    <!-- NEW: Customer Reviews Section -->
    <div class="pt-8 border-t">
        <div class="flex justify-between items-end mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">User Reviews ({{ $guide->reviews->count() }})</h2>
                <p class="text-sm text-gray-500">Feedback submitted by users who followed this guide.</p>
            </div>
            @if($guide->reviews->count() > 0)
                <div class="text-right">
                    <div class="flex text-yellow-400 text-xl justify-end">
                        @php $avg = $guide->reviews->avg('rating'); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($avg) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase">Average: {{ number_format($avg, 1) }} / 5.0</span>
                </div>
            @endif
        </div>

        <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
            @forelse ($guide->reviews as $review)
                <div class="p-6 border-b border-gray-200 last:border-0 hover:bg-white transition">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-3 text-sm">
                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 leading-none">{{ $review->user->name }}</p>
                                <p class="text-xs text-gray-400 mt-1 uppercase">{{ $review->created_at->format('M d, Y @ h:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-lg">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                    </div>
                    <div class="pl-13 ml-13">
                        <p class="text-gray-700 italic bg-white p-3 rounded-lg border border-gray-100">
                            "{{ $review->comment }}"
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <p class="text-gray-500 italic">No reviews have been submitted for this guide yet.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection