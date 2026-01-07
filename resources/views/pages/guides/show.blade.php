@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <header class="mb-10">
        <a href="{{ route('guides.issues', [$device, $category]) }}"
           class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-150 mb-6">
            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L6.414 9H17a1 1 0 110 2H6.414l3.293 3.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Back to All Issues
        </a>

        <nav class="flex mb-4 text-sm text-gray-500" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li>
                    <a href="{{ route('guides.devices') }}" class="hover:text-red-600 transition">
                        {{ $device }}
                    </a>
                </li>

                <li class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 11 7.293 7.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <a href="{{ route('guides.categories', $device) }}" class="ml-1 hover:text-red-600 transition">
                        {{ $category }}
                    </a>
                </li>

                <li class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 11 7.293 7.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <span class="ml-1 font-semibold text-gray-700">{{ $issue }}</span>
                </li>
            </ol>
        </nav>

        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">
            {{ $issue }} Guide
        </h1>
        <p class="text-xl text-gray-600 mt-2">
            Comprehensive diagnostic and repair walkthrough.
        </p>
    </header>

    {{-- GUIDE CONTENT --}}
    @if ($guide)

        <div class="mb-12 bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-red-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">
                    {{ $guide->brand ?? 'Generic' }} {{ $guide->model ?? 'Model' }}
                </h2>
            </div>

            <div class="p-8 space-y-8">
                @foreach ($guide->resources as $res)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pb-8 border-b border-gray-100 last:border-0 last:pb-0">

                        <div class="md:col-span-4">
                            <span class="inline-block px-3 py-1 text-xs font-bold uppercase text-red-600 bg-red-50 rounded-full mb-2">
                                The Cause
                            </span>
                            <p class="text-gray-900 font-semibold">
                                {{ $res->cause }}
                            </p>
                        </div>

                        <div class="md:col-span-8">
                            <span class="inline-block px-3 py-1 text-xs font-bold uppercase text-green-600 bg-green-50 rounded-full mb-2">
                                The Solution
                            </span>
                            <p class="text-gray-800 font-medium mb-4">
                                {{ $res->solution }}
                            </p>

                            @if (!empty($res->details))
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">
                                        Repair Steps
                                    </h4>
                                    <p class="text-gray-700 whitespace-pre-line text-sm">
                                        {{ $res->details }}
                                    </p>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

    @else

        <div class="text-center p-16 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Guide in Progress</h3>
            <p class="text-gray-500 mt-2">
                We're currently documenting the fix for
                <span class="text-red-600">{{ $issue }}</span>.
            </p>
        </div>

    @endif

    {{-- REVIEWS --}}
    @if ($guide)
    <section id="reviews" class="mt-20 border-t border-gray-200 pt-12">

        @php
            $avgRating   = $guide->reviews->avg('rating') ?? 0;
            $reviewCount = $guide->reviews->count();
        @endphp

        <h2 class="text-3xl font-extrabold text-gray-900 mb-6">
            Community Feedback
        </h2>

        @forelse ($guide->reviews as $review)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-4">
                <strong>{{ $review->user->name }}</strong>
                <p class="text-sm text-gray-500">
                    {{ $review->created_at->diffForHumans() }}
                </p>
                <p class="mt-2 italic text-gray-700">
                    "{{ $review->comment }}"
                </p>
            </div>
        @empty
            <p class="text-gray-500">No reviews yet.</p>
        @endforelse

        {{-- Added Login Logic below --}}
        <div class="mt-10">
            @auth
                <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="guide_id" value="{{ $guide->id }}">

                    <select name="rating" class="w-full border rounded-lg p-3">
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} Stars</option>
                        @endfor
                    </select>

                    <textarea name="comment" required rows="4" class="w-full border rounded-lg p-3"
                              placeholder="Share your experience..."></textarea>

                    <button class="bg-red-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-red-700 transition">
                        Submit Review
                    </button>
                </form>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Want to share your results?</h3>
                    <p class="text-gray-600 mb-6">Log in to leave a review and help others fix their devices.</p>
                    <a href="{{ route('login') }}" 
                       class="inline-block bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-black transition">
                        Log In to Review
                    </a>
                    <p class="mt-4 text-sm text-gray-500">
                        New here? <a href="{{ route('register') }}" class="text-red-600 font-semibold hover:underline">Create an account</a>
                    </p>
                </div>
            @endauth
        </div>

    </section>
    @endif

</div>
@endsection