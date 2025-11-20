@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

<!-- Header with Back Link and Breadcrumb -->
<header class="mb-10">
    
    {{-- Back Link to Categories --}}
    <a href="{{ route('guides.categories', $device) }}" 
       class="text-blue-600 hover:text-blue-800 flex items-center mb-4 transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0l-4 4a1 1 0 001.414 1.414L10 11.414l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0z" clip-rule="evenodd" />
        </svg>
        Back to Categories
    </a>
    
    {{-- Main Title with Breadcrumb Effect --}}
    <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
        Issues for 
        <span class="text-gray-500 font-medium text-3xl">
            {{ $device }} 
            <span class="text-gray-400">→</span> 
            <span class="text-red-600">{{ $category }}</span>
        </span>
    </h1>
    <p class="text-lg text-gray-600">Please select the specific issue you are experiencing to view the step-by-step guide.</p>
</header>

<!-- Issues List -->
<div class="space-y-4">
    
    @forelse($issues as $item)
        <a href="{{ route('guides.show', [$device, $category, $item->issue]) }}"
            class="
                w-full p-5 bg-white rounded-xl border-l-4 border-red-500 
                shadow-lg transition duration-300 ease-in-out 
                hover:shadow-xl hover:translate-x-1
                flex justify-between items-center
            ">
            
            <div class="flex items-center">
                {{-- Icon representing an issue or guide --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>

                {{-- Issue Name --}}
                <span class="text-xl font-semibold text-gray-800">{{ $item->issue }}</span>
            </div>
            
            {{-- Arrow Icon for flow --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
        </a>
    @empty
        <div class="text-center p-12 bg-white rounded-xl mt-10 border border-dashed">
            <p class="text-2xl text-gray-700 font-semibold">No issues found for {{ $category }} on {{ $device }}.</p>
            <p class="text-gray-500 mt-2">The guides for this combination may not have been created yet. Please try another category.</p>
        </div>
    @endforelse
</div>


</div>
@endsection