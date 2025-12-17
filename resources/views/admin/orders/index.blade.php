@extends('admin.layout')

@section('title', 'Revenue Detail (Paid Orders)')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Revenue Detail</h1>
        
        {{-- Back Button Added Here --}}
        <a href="{{ route('admin.accounting.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Back to Accounting
        </a>
    </div>

    {{-- Top 5 Selling Products Card --}}
    <div class="bg-white rounded-xl shadow-2xl p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
            Top 5 Bestsellers ({{ $start->format('M j') }} - {{ $end->format('M j, Y') }})
        </h2>
        
        @if ($topProducts->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach ($topProducts as $index => $item)
                    <div class="p-4 rounded-lg bg-gray-50 border border-gray-200 hover:shadow-md transition duration-150">
                        <p class="text-xs font-bold uppercase text-gray-500">#{{ $index + 1 }} SOLD: {{ number_format($item->total_quantity) }}</p>
                        <p class="text-lg font-extrabold text-indigo-700 truncate mt-1">
                            {{ $item->product->name ?? 'Unknown Product' }}
                        </p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 italic">No products sold in this date range.</p>
        @endif
    </div>

    {{-- Filter Information --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 mb-6 rounded-lg shadow-md">
        <p class="font-semibold">Showing Paid Orders from: <span class="font-mono">{{ $start->format('F j, Y') }}</span> to <span class="font-mono">{{ $end->format('F j, Y') }}</span></p>
        <p class="text-sm">Total Orders in this range: {{ number_format($orders->total()) }}</p>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order Items</th> 
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total (₦)</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order->user->name ?? 'Guest User' }}
                                <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                            </td>
                            
                            {{-- Item Details Column --}}
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if ($order->items->count())
                                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                                        {{-- Note: We now access item->product->name since we eager loaded 'items.product' in the controller --}}
                                        @foreach ($order->items->take(2) as $item)
                                            <li>{{ $item->quantity }} x {{ $item->product->name ?? 'Product Deleted' }}</li>
                                        @endforeach
                                    </ul>
                                    @if ($order->items->count() > 2)
                                        <p class="text-xs text-gray-500 mt-1">
                                            + {{ $order->items->count() - 2 }} more items...
                                        </p>
                                    @endif
                                @else
                                    <span class="text-xs text-red-500 italic">No items recorded</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-green-600">
                                ₦{{ number_format($order->grand_total, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No paid orders found in this date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection