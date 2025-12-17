@props(['size' => 'md'])

@php
    // Define size classes for the spinner for reuse
    $spinnerSize = match($size) {
        'sm' => 'w-4 h-4',
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
        default => 'w-8 h-8',
    };
@endphp

<div
    {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center']) }}
    style="display: none;" 
    x-cloak
>
    {{-- Backdrop Overlay --}}
    <div class="absolute inset-0 bg-gray-900 opacity-60"></div>

    {{-- Spinner Container --}}
    <div class="relative flex flex-col items-center p-6 bg-white rounded-lg shadow-2xl">
        
        {{-- The Spinner Animation --}}
        <div class="animate-spin rounded-full border-4 border-t-4 border-blue-200 border-t-blue-600 {{ $spinnerSize }}"></div>
        
        {{-- Optional Loading Text Slot --}}
        <p class="mt-4 text-lg font-semibold text-gray-700">
            {{ $slot }}
            @if($slot->isEmpty())
                Loading...
            @endif
        </p>
    </div>
</div>