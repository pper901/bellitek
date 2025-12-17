@extends('admin.layout')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manage Repair: ') }} {{ $repair->tracking_code }}
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <p>Please fix the following errors:</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Details & Status Update -->
            <div class="lg:col-span-1 bg-white shadow-xl sm:rounded-lg p-6 h-full">
                <h3 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Repair Details</h3>

                <p class="mb-2"><span class="font-semibold">Device:</span> {{ $repair->brand }} {{ $repair->device_type }}</p>
                <p class="mb-2"><span class="font-semibold">Customer:</span> {{ $repair->customer_name }} ({{ $repair->user->email ?? 'N/A' }})</p>
                <p class="mb-2"><span class="font-semibold">Delivery:</span> {{ ucwords($repair->delivery_method) }}</p>
                <p class="mb-4"><span class="font-semibold">Issue:</span> {{ $repair->issue }}</p>

                <h4 class="text-lg font-semibold mt-6 mb-4 text-gray-700 border-t pt-2">Update Repair Status</h4>
                <form action="{{ route('admin.repairs.updateStatus', $repair->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Current Status: <span class="font-bold text-lg text-indigo-600">{{ ucwords(str_replace('_', ' ', $repair->status)) }}</span></label>
                        <select name="status" id="status" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ $repair->status === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Right Column: Timeline & Add Step Form -->
            <div class="lg:col-span-2 bg-white shadow-xl sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Repair Timeline</h3>
                
                <!-- Timeline Display -->
                <div class="space-y-6 mb-8 max-h-96 overflow-y-auto pr-4">
                    @forelse ($repair->steps->sortByDesc('created_at') as $step)
                        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                            <p class="text-sm font-semibold text-gray-900">{{ $step->title }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $step->created_at->format('M d, Y H:i A') }} by {{ $step->engineer->name ?? 'System' }}
                            </p>
                            <p class="text-gray-700 mt-2">{{ $step->description }}</p>
                            
                            {{-- Display images associated with this step --}}
                            @if ($step->images->count())
                                <div class="mt-3 pt-3 border-t border-gray-300 grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach ($step->images as $image)
                                        <a href="{{ Storage::url($image->image_path) }}" target="_blank" class="block aspect-video overflow-hidden rounded-lg shadow hover:opacity-80 transition">
                                            <img src="{{ Storage::url($image->image_path) }}" alt="Repair Photo" class="w-full h-full object-cover">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No steps have been added to this repair yet.</p>
                    @endforelse
                </div>

                <!-- Add Step Form (Updated to handle files) -->
                <h4 class="text-lg font-semibold mt-6 mb-4 text-gray-700 border-t pt-4">Add New Timeline Step</h4>
                <form action="{{ route('admin.repairs.addStep', $repair->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title (e.g., Diagnostics Complete)</label>
                        <input type="text" name="title" id="title" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Detailed Update for Customer)</label>
                        <textarea name="description" id="description" rows="3" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>
                    
                    {{-- File Input for Image Upload --}}
                    <div class="mb-6">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Upload Progress Photos (Max 5)</label>
                        <input type="file" name="images[]" id="images" multiple 
                                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-lg file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100"
                                accept="image/jpeg,image/png,image/jpg,image/webp">
                        <p class="mt-1 text-xs text-gray-500">JPEG, PNG, or WebP up to 5MB each.</p>
                    </div>

                    <button type="submit" class="py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Add Step to Timeline
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection