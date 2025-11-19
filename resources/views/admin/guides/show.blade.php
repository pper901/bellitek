@extends('admin.layout')

@section('title', 'View Guide: ' . $guide->model)

@section('content')

<div class="bg-white p-8 rounded-xl shadow-2xl space-y-8">

<!-- Header and Actions -->
<div class="flex justify-between items-center border-b pb-4">
    <h1 class="text-3xl font-extrabold text-gray-900">
        Guide: {{ $guide->brand }} {{ $guide->series }} ({{ $guide->model }})
    </h1>
    <a href="{{ route('admin.guides.edit', $guide) }}" 
       class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-150 shadow-md">
        Edit Guide
    </a>
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

<!-- Causes & Solutions (Resources) Section -->
<div class="pt-6 border-t">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Troubleshooting Resources ({{ $guide->resources->count() }})</h2>

    @forelse ($guide->resources as $resource)
        <div class="mb-6 p-5 border border-gray-200 rounded-lg bg-gray-50 shadow-sm transition duration-150 hover:shadow-md">
            
            <!-- Cause and Solution on the same row -->
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

            <!-- Detailed Steps Section -->
            <div class="pt-4 border-t border-gray-200">
                <p class="font-bold text-lg text-blue-600 mb-2">Detailed Steps</p>
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {{-- Use nl2br to render line breaks correctly from the textarea --}}
                    {!! nl2br(e($resource->details ?? 'No detailed steps provided for this resource.')) !!}
                </div>
            </div>
        </div>
    @empty
        <p class="text-gray-500 italic p-4 border border-dashed rounded-lg">No causes or solutions have been added to this guide yet.</p>
    @endforelse
</div>


</div>

@endsection