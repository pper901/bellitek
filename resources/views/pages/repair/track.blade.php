@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">

    <div class="flex justify-between items-center mb-6 border-b pb-2">
        <h1 class="text-3xl font-bold text-gray-800">Tracking: {{ $repair->tracking_code }}</h1>
        
        {{-- Back to List Button --}}
        <a href="{{ route('repair.index') }}" 
           class="px-5 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-lg hover:bg-gray-600 transition duration-150 transform hover:scale-105 flex items-center space-x-2">
            <span>&larr; Back to My Repairs</span>
        </a>
    </div>

    {{-- Single Repair Details Card (The content the track page should display) --}}
    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-indigo-500">
        <h2 class="text-2xl font-semibold mb-4 text-gray-700">Device & Status</h2>

        @php
            $statusColor = [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'in progress' => 'bg-blue-100 text-blue-800',
                'completed' => 'bg-green-100 text-green-800',
                'cancelled' => 'bg-red-100 text-red-800',
                'rate_selected' => 'bg-purple-100 text-purple-800',
                'awaiting_payment' => 'bg-orange-100 text-orange-800',
                'shipment_created' => 'bg-teal-100 text-teal-800',
            ][strtolower($repair->status)] ?? 'bg-gray-100 text-gray-800';
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Tracking Code and Status --}}
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-500">Tracking Code</p>
                <p class="text-xl font-mono text-indigo-600 font-bold">{{ $repair->tracking_code }}</p>
                <span class="inline-block px-3 py-1 text-sm font-medium rounded-full mt-2 {{ $statusColor }}">
                    Status: {{ ucfirst(str_replace('_', ' ', $repair->status)) }}
                </span>
            </div>

            {{-- Device Information --}}
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-500">Device</p>
                <p class="text-xl font-semibold text-gray-900">{{ $repair->brand }} {{ $repair->device_type }}</p>
                <p class="text-sm text-gray-600">Model: {{ $repair->model ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Issue Description --}}
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-700 border-b pb-1 mb-2">Issue Reported</h3>
            <p class="text-gray-600">{{ $repair->issue }}</p>
        </div>

        {{-- Repair Steps / Timeline --}}
        <div class="mt-8">
            <h3 class="text-xl font-bold text-indigo-700 border-b-2 border-indigo-200 pb-2 mb-4">Repair Timeline & Updates</h3>

            <div class="space-y-6">
                @php $stepCount = 1; @endphp

                {{-- 1. Initial Step: Submission (Always the first step) --}}
                <div class="flex items-start space-x-3 p-4 bg-green-50 rounded-lg shadow border border-green-200">
                    <div class="w-8 h-8 flex items-center justify-center rounded-full bg-green-600 text-white font-bold">{{ $stepCount++ }}</div>
                    <div class="flex-grow">
                        <p class="font-medium text-gray-900">Repair Request Submitted</p>
                        <p class="text-sm text-gray-500">{{ $repair->created_at->format('F d, Y H:i A') }}</p>
                        <p class="text-gray-700 mt-1">Your repair request has been successfully received and logged.</p>
                    </div>
                </div>

                {{-- 2. Dynamic Steps: Technician Updates (Sorted chronologically) --}}
                @forelse ($repair->steps->sortBy('created_at') as $step)
                    @php 
                        // Determine styling for dynamic steps
                        $bgColor = 'bg-white';
                        $ringColor = 'bg-indigo-600';
                    @endphp
                    <div class="flex items-start space-x-3 p-4 {{ $bgColor }} rounded-lg shadow-md border border-gray-200">
                        <div class="w-8 h-8 flex items-center justify-center rounded-full {{ $ringColor }} text-white font-bold">{{ $stepCount++ }}</div>
                        <div class="flex-grow">
                            <p class="font-medium text-gray-900">{{ $step->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $step->created_at->format('F d, Y H:i A') }} 
                            </p>
                            <p class="text-gray-700 mt-2">{{ $step->description }}</p>

                            {{-- Display images associated with this step --}}
                            @if ($step->images->count())
                                <div class="mt-4 pt-3 border-t border-gray-200 grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach ($step->images as $image)
                                        <a href="{{ Storage::url($image->image_path) }}" target="_blank" class="block aspect-video overflow-hidden rounded-lg shadow-md hover:opacity-90 transition">
                                            <img src="{{ Storage::url($image->image_path) }}" alt="Repair Progress Photo" class="w-full h-full object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    {{-- Message if no technician steps have been added yet --}}
                    <div class="text-center text-gray-500 p-6 border border-dashed rounded-lg">
                        No progress updates are available yet beyond the initial submission. Check back soon!
                    </div>
                @endforelse

            </div>
        </div>
    </div>
</div>
@endsection