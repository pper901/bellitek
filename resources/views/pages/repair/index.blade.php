@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">

    <div class="flex justify-between items-center mb-6 border-b pb-2">
        <h1 class="text-3xl font-bold text-gray-800">My Repair Tracking</h1>
        
        {{-- BACK BUTTON / NEW SUBMISSION BUTTON --}}
        <a href="{{ route('repair.create') }}" 
           class="px-5 py-2 bg-green-600 text-white font-semibold rounded-lg shadow-lg hover:bg-green-700 transition duration-150 transform hover:scale-105">
            + Submit New Repair
        </a>
    </div>

    {{-- Repair Search Form --}}
    <div class="bg-white p-6 rounded-xl shadow-lg mb-8 border-t-4 border-indigo-500">
        <h2 class="text-xl font-semibold mb-3 text-gray-700">Track Specific Repair by Code</h2>
        <form action="{{ route('repair.index') }}" method="GET" class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
            <input type="text" name="tracking_code" placeholder="Enter Tracking Code (e.g., REP-ABC123XYZ)" required
                    class="flex-grow border border-gray-300 p-3 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                    value="{{ old('tracking_code', request('tracking_code')) }}">
            
            <button type="submit" class="flex-shrink-0 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition duration-150">
                Search
            </button>
        </form>

        {{-- Safely check for the variable using the null coalescing operator (??) --}}
        @if (request('tracking_code') && !( $searchedRepair ?? false )) 
            <p class="mt-3 text-red-600 font-medium">
                Repair with code "{{ request('tracking_code') }}" not found. Please verify the code and try again.
            </p>
        @endif
    </div>

    {{-- User's Repairs List --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Your Recent Repair Submissions</h2>

    <div class="space-y-4">
        @forelse ($userRepairs as $repair)
            @php
                $statusColor = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'in progress' => 'bg-blue-100 text-blue-800',
                    'completed' => 'bg-green-100 text-green-800',
                    'cancelled' => 'bg-red-100 text-red-800',
                    'rate_selected' => 'bg-purple-100 text-purple-800', // Added status for visibility
                    'awaiting_payment' => 'bg-orange-100 text-orange-800',
                    'shipment_created' => 'bg-teal-100 text-teal-800',
                ][strtolower($repair->status)] ?? 'bg-gray-100 text-gray-800';
            @endphp
            
            <div class="bg-white p-5 rounded-xl shadow-md flex justify-between items-center transition duration-200 hover:shadow-lg">
                <div>
                    <p class="text-lg font-semibold text-gray-900">{{ $repair->brand }} {{ $repair->device_type }}</p>
                    <p class="text-sm text-gray-600">Issue: {{ Str::limit($repair->issue, 50) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Submitted: {{ $repair->created_at->format('M d, Y') }}</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-700">Code:</p>
                        <p class="font-mono text-indigo-600 font-bold">{{ $repair->tracking_code }}</p>
                        <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full mt-1 {{ $statusColor }}">
                            {{ ucfirst(str_replace('_', ' ', $repair->status)) }}
                        </span>
                    </div>

                    <a href="{{ route('repair.track', $repair->tracking_code) }}" 
                        class="px-4 py-2 bg-indigo-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-600 transition">
                        View Steps
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-xl shadow-md text-gray-500 italic border border-dashed">
                You currently have no repair submissions.
            </div>
        @endforelse
    </div>

</div>
@endsection