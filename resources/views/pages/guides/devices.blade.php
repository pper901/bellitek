@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

<!-- Hero Header -->
<header class="text-center mb-12">
    <h1 class="text-5xl font-extrabold text-gray-900 mb-3">Find Your Repair Guide</h1>
    <p class="text-xl text-gray-600">Select the type of device you need help with to proceed to our categories and issues.</p>
</header>

<!-- Devices Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    
    @foreach($devices as $item)
        @php
            $device = $item->device;
            // Assign dynamic style based on device name
            $icon = 'M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z'; // Default icon (Circle)
            $color = 'bg-gray-500 hover:bg-gray-600';
            $shadow = 'shadow-gray-300';

            switch (strtolower($device)) {
                case 'phone':
                case 'smartphone':
                case 'mobile':
                    $icon = 'M12 1.5a.5.5 0 0 0-.5.5v12a.5.5 0 0 0 1 0V2a.5.5 0 0 0-.5-.5zM8 2a.5.5 0 0 0-.5.5v11a.5.5 0 0 0 1 0V2.5a.5.5 0 0 0-.5-.5zM4 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 1 0v-9A.5.5 0 0 0 4 3zM2 4a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 1 0v-7A.5.5 0 0 0 2 4z'; // Phone icon representation
                    $color = 'bg-red-600 hover:bg-red-700';
                    $shadow = 'shadow-red-300';
                    break;
                case 'laptop':
                case 'pc':
                case 'computer':
                    $icon = 'M2 18h20v2H2zM4 4h16v12H4z'; // Laptop icon representation
                    $color = 'bg-blue-600 hover:bg-blue-700';
                    $shadow = 'shadow-blue-300';
                    break;
                case 'tablet':
                    $icon = 'M2 2h20v20H2z'; // Square/Tablet icon representation
                    $color = 'bg-green-600 hover:bg-green-700';
                    $shadow = 'shadow-green-300';
                    break;
                case 'watch':
                case 'wearable':
                    $icon = 'M14 6a2 2 0 100 4 2 2 0 000-4zM12 2v2M12 20v-2M5 12H3M21 12h-2'; // Watch icon representation
                    $color = 'bg-purple-600 hover:bg-purple-700';
                    $shadow = 'shadow-purple-300';
                    break;
            }
        @endphp

        <a href="{{ route('guides.categories', $device) }}"
            class="
                flex flex-col items-center justify-center text-center
                p-6 rounded-xl transition duration-300 transform
                text-white font-bold text-lg
                shadow-xl hover:shadow-2xl
                {{ $color }}
            ">
            
            {{-- Dynamic Icon (Simplified SVG) --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mb-3" viewBox="0 0 24 24" fill="currentColor">
                <path d="{{ $icon }}"/>
            </svg>

            {{-- Device Name --}}
            <span class="text-2xl tracking-wide">{{ $device }}</span>
            
            {{-- Invisible element for better visual padding --}}
            <span class="text-xs mt-1 opacity-80">View all categories</span>
        </a>
    @endforeach
</div>

@if($devices->isEmpty())
    <div class="text-center p-12 bg-gray-100 rounded-xl mt-10">
        <p class="text-2xl text-gray-700 font-semibold">No guides available yet.</p>
        <p class="text-gray-500 mt-2">Check back soon as we continuously add new troubleshooting resources!</p>
    </div>
@endif


</div>
@endsection