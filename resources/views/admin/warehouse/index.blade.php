@extends('admin.layout')

@section('content')

{{-- 1. MESSAGE HANDLING --}}
<div class="mb-4">
    @if (session('error'))
        <x-message-alert type="error" :message="session('error')" />
    @elseif (session('success'))
        <x-message-alert type="success" :message="session('success')" />
    @endif
</div>

<div x-data="{ loading: false }">

    {{-- 2. LOADING SPINNER --}}
    <x-loading-spinner x-show="loading" x-cloak size="lg">
        Processing...
    </x-loading-spinner>

    <div class="container mx-auto mt-4 px-4">
        
        {{-- 3. HEADER SECTION --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Warehouse Settings</h2>
                <p class="text-sm text-gray-500">Manage your ShipBubble sender locations</p>
            </div>
            <a href="{{ route('admin.warehouse.create') }}" 
               @click="loading = true"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-200 shadow-sm">
                + Add New Warehouse
            </a>
        </div>

        {{-- 4. WAREHOUSE TABLE --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Warehouse Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Address Code</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact Info</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Full Address</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($warehouses as $warehouse)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-5 py-4 text-sm">
                                <p class="text-gray-900 font-medium">{{ $warehouse->name }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <span class="inline-block bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-mono border">
                                    {{ $warehouse->address_code }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <div class="text-gray-600">{{ $warehouse->email }}</div>
                                <div class="text-gray-400 text-xs">{{ $warehouse->phone }}</div>
                            </td>
                            <td class="px-5 py-4 text-sm">
                                <p class="text-gray-600 line-clamp-2" title="{{ $warehouse->address }}">
                                    {{ $warehouse->address }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-sm text-center">
                                <span class="px-2 py-1 text-xs font-bold leading-tight text-green-700 bg-green-100 rounded-full">
                                    Active
                                </span>
                            </td>
                        </tr>
                    @empty
                        {{-- 5. EMPTY STATE --}}
                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center bg-white text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <p>No warehouses found.</p>
                                    <a href="{{ route('admin.warehouse.create') }}" class="text-blue-500 hover:underline mt-2 text-sm">Click here to add your first one.</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection