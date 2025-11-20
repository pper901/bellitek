@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

<!-- Header and Breadcrumb -->
<header class="mb-10">
    
    {{-- Back Link to Issues List --}}
    <a href="{{ route('guides.issues', [$device, $category]) }}" 
       class="text-blue-600 hover:text-blue-800 flex items-center mb-4 transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0l-4 4a1 1 0 001.414 1.414L10 11.414l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0z" clip-rule="evenodd" />
        </svg>
        Back to All Issues
    </a>
    
    {{-- Breadcrumb Navigation --}}
    <p class="text-sm text-gray-500 mb-2">
        <a href="{{ route('guides.devices') }}" class="hover:text-red-600">{{ $device }}</a> 
        &gt; 
        <a href="{{ route('guides.categories', $device) }}" class="hover:text-red-600">{{ $category }}</a> 
        &gt; 
        <span class="font-semibold text-gray-700">{{ $issue }}</span>
    </p>

    <h1 class="text-4xl font-extrabold text-gray-900">{{ $issue }} Guide</h1>
    <p class="text-xl text-gray-600 mt-2">Detailed diagnostic and repair steps for this issue.</p>
</header>

@forelse ($guides as $guide)
    <div class="mb-10 bg-white p-6 rounded-xl shadow-2xl border-t-4 border-red-600">

        {{-- Model Title --}}
        <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 21h7a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414a1 1 0 00-.707-.293H7a2 2 0 00-2 2v11m0 5l4.879-4.879m0 0a3 3 0 114.243 4.243c0 .878-.34 1.715-.989 2.364-.649.649-1.496.989-2.364.989z" />
            </svg>
            {{ $guide->brand ?? 'Generic' }} {{ $guide->model ?? 'Model' }}
        </h2>

        @foreach($guide->resources as $res)
            <div class="p-6 border-l-4 border-blue-100 mb-6 bg-blue-50 rounded-lg shadow-inner space-y-4">
                
                {{-- Cause --}}
                <div>
                    <p class="text-sm font-semibold text-red-600 uppercase tracking-wider mb-1">
                        Diagnostic/Cause
                    </p>
                    <p class="text-gray-800 font-medium">
                        {{ $res->cause }}
                    </p>
                </div>
                
                {{-- Solution --}}
                <div>
                    <p class="text-sm font-semibold text-green-600 uppercase tracking-wider mb-1">
                        Primary Solution
                    </p>
                    <p class="text-gray-800 font-medium">
                        {{ $res->solution }}
                    </p>
                </div>

                {{-- Details/Steps --}}
                @if (!empty($res->details))
                    <div class="pt-4 border-t border-blue-200">
                        <p class="text-sm font-semibold text-blue-600 uppercase tracking-wider mb-2">
                            Step-by-Step Instructions
                        </p>
                        <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                            {{ $res->details }}
                        </p>
                    </div>
                @endif

            </div>
        @endforeach

    </div>
@empty
    <div class="text-center p-12 bg-gray-100 rounded-xl mt-10 border border-dashed">
        <p class="text-2xl text-gray-700 font-semibold">Detailed guide not yet available.</p>
        <p class="text-gray-500 mt-2">We are working on adding the repair guide for 
            <span class="font-semibold">{{ $issue }}</span> 
            on {{ $device }} {{ $category }}.
        </p>
    </div>
@endforelse


</div>

@endsection