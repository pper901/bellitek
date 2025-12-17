@extends('admin.layout')

@section('title', 'API Cost Detail')

@section('content')
<div class="p-6">
    
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            {{-- Back Button to Accounting Dashboard --}}
            <a href="{{ route('admin.accounting.index') }}"
               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Back
            </a>
            <h1 class="text-3xl font-bold text-gray-800">API Call Cost Detail</h1>
        </div>
        {{-- Right side of the header is intentionally empty --}}
    </div>

    {{-- Filter Information & Total --}}
    <div class="bg-red-50 border-l-4 border-red-500 text-red-800 p-4 mb-6 rounded-lg shadow-md flex justify-between items-center">
        <div>
            <p class="font-semibold">Showing API Logs from: <span class="font-mono">{{ $start->format('F j, Y') }}</span> to <span class="font-mono">{{ $end->format('F j, Y') }}</span></p>
            <p class="text-sm">Total API Calls logged: {{ number_format($apiCalls->total()) }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium">TOTAL COST</p>
            <p class="text-2xl font-bold">₦{{ number_format($totalCost, 2) }}</p>
        </div>
    </div>

    {{-- API Calls Table --}}
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Endpoint</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Cost/Call</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Cost (₦)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($apiCalls as $call)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $call->provider->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <code class="bg-gray-100 p-1 rounded text-xs">{{ $call->endpoint }}</code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                {{ number_format($call->count) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-600">
                                ₦{{ number_format($call->provider->cost_per_call ?? 0, 4) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-red-600">
                                ₦{{ number_format($call->cost_cached, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $call->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No API calls recorded in this date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $apiCalls->links() }}
    </div>
</div>
@endsection