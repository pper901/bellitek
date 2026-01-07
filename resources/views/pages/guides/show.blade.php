@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <!-- Header and Breadcrumb -->
    <header class="mb-10">
        <a href="{{ route('guides.issues', [$device, $category]) }}" 
           class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-150 mb-6">
            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L6.414 9H17a1 1 0 110 2H6.414l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Back to All Issues
        </a>
        
        <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('guides.devices') }}" class="hover:text-red-600 transition">{{ $device }}</a></li>
                <li class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 11 7.293 7.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('guides.categories', $device) }}" class="ml-1 hover:text-red-600 transition">{{ $category }}</a>
                </li>
                <li class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 11 7.293 7.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 font-semibold text-gray-700">{{ $issue }}</span>
                </li>
            </ol>
        </nav>

        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">{{ $issue }} Guide</h1>
        <p class="text-xl text-gray-600 mt-2">Comprehensive diagnostic and repair walkthrough.</p>
    </header>

    @if ($guide)
        <div class="mb-12 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-red-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    {{ $guide->brand ?? 'Generic' }} {{ $guide->model ?? 'Model' }}
                </h2>
            </div>

            <div class="p-8 space-y-8">
                @foreach($guide->resources as $res)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pb-8 border-b border-gray-100 last:border-0 last:pb-0">
                        <div class="md:col-span-4">
                            <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider text-red-600 bg-red-50 rounded-full mb-2">The Cause</span>
                            <p class="text-gray-900 font-semibold leading-snug">{{ $res->cause }}</p>
                        </div>
                        <div class="md:col-span-8">
                            <span class="inline-block px-3 py-1 text-xs font-bold uppercase tracking-wider text-green-600 bg-green-50 rounded-full mb-2">The Solution</span>
                            <p class="text-gray-800 font-medium mb-4">{{ $res->solution }}</p>
                            
                            @if (!empty($res->details))
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Repair Steps</h4>
                                    <p class="text-gray-700 whitespace-pre-line text-sm leading-relaxed">{{ $res->details }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center p-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900">Guide in Progress</h3>
            <p class="text-gray-500 mt-2 max-w-sm mx-auto">We're currently documenting the fix for <span class="text-red-600">{{ $issue }}</span>. Please check back later.</p>
        </div>
    @endif

    {{-- REVIEWS SECTION --}}
    <section id="reviews" class="mt-20 border-t border-gray-200 pt-12">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-10">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900">Community Feedback</h2>
                <p class="text-gray-500 mt-1">See how this guide helped others fix their {{ $device }}.</p>
            </div>
            
            @php
                $avgRating = $guide->reviews->avg('rating') ?? 0;
                $reviewCount = $guide->reviews->count();
            @endphp
            
            <div class="mt-6 md:mt-0 flex items-center bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="text-center pr-6 mr-6 border-r border-gray-100">
                    <div class="text-4xl font-black text-gray-900">{{ number_format($avgRating, 1) }}</div>
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Average</div>
                </div>
                <div>
                    <div class="flex text-yellow-400 mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <div class="text-sm font-medium text-gray-500">{{ $reviewCount }} verified reviews</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @forelse($guide->reviews as $review)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 transition hover:shadow-md">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold mr-3 uppercase">
                                {{ substr($review->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $review->user->name }}</h4>
                                <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed italic">"{{ $review->comment }}"</p>
                </div>
            @empty
                <div class="text-center py-10 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-gray-500">No reviews yet. Be the first to share your experience!</p>
                </div>
            @endforelse
        </div>

        @auth
            <div class="mt-12 bg-gray-900 rounded-3xl p-8 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-2xl font-bold mb-2">Help the Community</h3>
                    <p class="text-gray-400 mb-8">Did this guide work for you? Your feedback helps us improve our repair accuracy.</p>

                    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="guide_id" value="{{ $guide->id ?? '' }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold uppercase tracking-widest text-gray-400 mb-2">Service Rating</label>
                                <div class="relative">
                                    <select name="rating" class="w-full bg-gray-800 border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-transparent transition appearance-none">
                                        <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                        <option value="4">⭐⭐⭐⭐ (Very Good)</option>
                                        <option value="3">⭐⭐⭐ (Average)</option>
                                        <option value="2">⭐⭐ (Poor)</option>
                                        <option value="1">⭐ (Awful)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold uppercase tracking-widest text-gray-400 mb-2">Your Experience</label>
                            <textarea required name="comment" rows="4" placeholder="Mention if the steps were clear or if you encountered any specific hurdles..."
                                      class="w-full bg-gray-800 border-gray-700 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-transparent transition placeholder-gray-600"></textarea>
                        </div>

                        <button type="submit"
                                class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition duration-150 transform hover:-translate-y-1 shadow-lg">
                            Post My Review
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </form>
                </div>
                
                {{-- Decorative background element --}}
                <div class="absolute top-0 right-0 -mt-10 -mr-10 bg-red-600 w-40 h-40 rounded-full blur-3xl opacity-20"></div>
            </div>
        @else
            <div class="mt-12 text-center p-8 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-gray-600">
                    Enjoying our free guides? 
                    <a class="text-red-600 font-bold hover:underline" href="{{ route('login') }}">Sign in</a> 
                    to leave a review and support the community.
                </p>
            </div>
        @endauth
    </section>
</div>

@endsection