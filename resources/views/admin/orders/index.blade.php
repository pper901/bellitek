@extends('admin.layout')

@section('title', 'Revenue Detail (Paid Orders)')

@section('content')
<div class="p-6">
    {{-- ... Header and Top Products sections remain the same ... --}}

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
                        {{-- NEW COLUMN --}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipping / Action</th>
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
                            
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <div class="flex flex-col gap-2">
                                    @foreach ($order->items as $item)
                                        <div class="flex items-center space-x-2 bg-gray-50 p-1.5 rounded-md border border-gray-100">
                                            {{-- Product Image Thumbnail --}}
                                            @if($item->product && $item->product->images->first())
                                                <img src="{{ $item->product->images->first()->path }}" 
                                                    class="w-8 h-8 object-cover rounded shadow-sm border border-gray-200">
                                            @else
                                                <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center text-[10px] text-gray-400">
                                                    N/A
                                                </div>
                                            @endif

                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-800 leading-tight">
                                                    {{ $item->product->name ?? 'Deleted Product' }}
                                                </span>
                                                <span class="text-[10px] text-gray-500">
                                                    Qty: <span class="text-indigo-600 font-bold">{{ $item->quantity }}</span> 
                                                    × ₦{{ number_format($item->price, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    {{-- Show count if it's a long order --}}
                                    @if($order->items->count() > 3)
                                        <span class="text-[10px] text-gray-400 italic font-medium pl-1">
                                            + {{ $order->items->count() - 3 }} more items
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-green-600">
                                ₦{{ number_format($order->grand_total, 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            </td>

                            {{-- NEW: SHIPPING / RETRY ACTION COLUMN --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($order->tracking_code && $order->tracking_code !== 'Pending assignment')
                                    <div class="flex flex-col">
                                        <span class="text-xs font-mono bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-200">
                                            {{ $order->tracking_code }}
                                        </span>
                                        @if($order->label_url)
                                            <a href="{{ $order->label_url }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-xs mt-1 underline">
                                                Download Label
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    {{-- The "Retry" UI for Admin --}}
                                    <form action="{{ route('admin.orders.retry-shipping', $order->id) }}" method="POST" onsubmit="return confirm('Ensure ShipBubble wallet is funded. Proceed?')">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Generate Label
                                        </button>
                                    </form>
                                    <span class="text-[10px] text-orange-500 italic mt-1">Pending Balance/Retry</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        {{-- ... Empty state ... --}}
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- ... Pagination ... --}}
</div>
@endsection