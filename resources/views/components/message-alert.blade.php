@props(['type' => 'success', 'message' => null])

@php
    // Define classes based on the message type (error, success)
    $color = match($type) {
        'error' => 'bg-red-500 border-red-700',
        'success' => 'bg-green-500 border-green-700',
        default => 'bg-gray-500 border-gray-700',
    };
    $icon = match($type) {
        'error' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.394 17c-.77 1.333.192 3 1.732 3z"></path></svg>',
        'success' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
        default => '',
    };
@endphp

<div 
    x-data="{ show: @js((bool)$message) }" {{-- Initialize visibility based on if a message exists --}}
    x-init="setTimeout(() => { show = false }, 5000)" {{-- Hide after 5 seconds --}}
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-full"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-full"
    class="fixed bottom-0 right-0 m-6 max-w-sm w-full z-50 p-4 rounded-lg text-white shadow-xl transition-all {{ $color }}"
>
    <div class="flex items-start justify-between">
        <div class="flex items-center">
            {!! $icon !!}
            <span class="ml-3 font-semibold text-sm">{{ $message }}</span>
        </div>
        <button @click="show = false" class="text-white opacity-75 hover:opacity-100 ml-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>