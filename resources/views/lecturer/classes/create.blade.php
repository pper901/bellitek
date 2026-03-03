@extends('lecturer.layout')

@section('header_title', 'Create New Class')

@section('content')

    {{-- Page intro --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-800 mb-1">
            Create a Live Classroom
        </h2>
        <p class="text-slate-500 text-sm">
            Set up a new class. A unique class ID will be generated and the WebSocket server will be activated if needed.
        </p>
    </div>

    {{-- Create class form --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('lecturer.classes.store') }}">
            @csrf

            <div class="p-6 space-y-6">

                {{-- Class title --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Class Title
                    </label>
                    <input
                        type="text"
                        name="title"
                        required
                        placeholder="e.g. Introduction to Laptop Repair"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        value="{{ old('title') }}"
                    >
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Class description --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Class Description (optional)
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        placeholder="Briefly describe what this class is about"
                        class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                    >{{ old('description') }}</textarea>
                </div>

                {{-- WebSocket notice --}}
                <div class="flex items-start gap-3 bg-slate-50 border border-slate-200 rounded-xl p-4 text-sm text-slate-600">
                    <i class="fas fa-info-circle text-indigo-500 mt-0.5"></i>
                    <p>
                        When you create this class, the system will check the Java WebSocket service.
                        If it’s inactive, it will be started automatically.
                    </p>
                </div>

            </div>

            {{-- Actions --}}
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                <a href="{{ route('lecturer.dashboard') }}"
                   class="text-sm font-semibold text-slate-500 hover:text-slate-700">
                    ← Back to Dashboard
                </a>

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm hover:bg-indigo-700 transition inline-flex items-center"
                >
                    <i class="fas fa-plus mr-2 text-xs"></i>
                    Create Class
                </button>
            </div>
        </form>
    </div>

@endsection
