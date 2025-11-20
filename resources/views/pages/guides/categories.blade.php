@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

<!-- Header with Back Link -->
<header class="mb-10">
    <a href="{{ route('guides.devices') }}" 
       class="text-blue-600 hover:text-blue-800 flex items-center mb-4 transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0l-4 4a1 1 0 001.414 1.414L10 11.414l3.293 3.293a1 1 0 001.414-1.414l-4-4a1 1 0 00-1.414 0z" clip-rule="evenodd" />
        </svg>
        Back to All Devices
    </a>
    <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
        Categories for <span class="text-red-600">{{ $device }}</span>
    </h1>
    <p class="text-lg text-gray-600">Select the operating system or specific category of your {{ strtolower($device) }}.</p>
</header>

<!-- Categories Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    @foreach($categories as $item)
        @php
            $category = $item->category;
            $iconPath = 'M10 2a8 8 0 100 16 8 8 0 000-16z'; // Default icon (Cog/Gear)
            $colorClass = 'border-gray-300 bg-white hover:bg-gray-50';

            // Dynamic styling based on common category names
            switch (strtolower($category)) {
                case 'ios':
                case 'apple':
                    $iconPath = 'M10 2a8 8 0 100 16 8 8 0 000-16zM6 10a1 1 0 112 0 1 1 0 01-2 0zm4 0a1 1 0 112 0 1 1 0 01-2 0zm4 0a1 1 0 112 0 1 1 0 01-2 0z'; // Apple/iOS icon representation
                    $colorClass = 'border-gray-800 bg-gray-50 hover:bg-gray-100';
                    break;
                case 'android':
                case 'samsung':
                case 'google':
                    $iconPath = 'M10 2a8 8 0 100 16 8 8 0 000-16zM6 10a1 1 0 112 0 1 1 0 01-2 0zm8 0a1 1 0 112 0 1 1 0 01-2 0z'; // Android icon representation
                    $colorClass = 'border-green-500 bg-green-50 hover:bg-green-100';
                    break;
                case 'windows':
                case 'microsoft':
                    $iconPath = 'M2 4h6v6H2zM2 12h6v6H2zM12 4h6v6h-6zM12 12h6v6h-6z'; // Windows icon representation
                    $colorClass = 'border-blue-500 bg-blue-50 hover:bg-blue-100';
                    break;
            }
        @endphp

        <a href="{{ route('guides.issues', [$device, $category]) }}"
            class="
                flex items-center p-5 rounded-xl border-2
                shadow-md transition duration-300 transform hover:scale-[1.02]
                text-gray-800 font-semibold
                {{ $colorClass }}
            ">
            
            {{-- Dynamic Icon (Simplified SVG) --}}
            <div class="p-2 mr-4 rounded-full border-2 border-current">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path d="{{ $iconPath }}"/>
                </svg>
            </div>

            {{-- Category Name --}}
            <span class="text-lg">{{ $category }}</span>
            
            {{-- Arrow Icon for flow --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    @endforeach
    
</div>

@if($categories->isEmpty())
    <div class="text-center p-12 bg-white rounded-xl mt-10 border border-dashed">
        <p class="text-2xl text-gray-700 font-semibold">No categories found for {{ $device }}.</p>
        <p class="text-gray-500 mt-2">Please ensure guides have been created for this device type in the admin panel.</p>
    </div>
@endif


</div>
@endsection