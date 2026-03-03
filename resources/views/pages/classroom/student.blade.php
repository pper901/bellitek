@extends('layouts.app')

@section('content')
<div class="main-panel1 min-h-screen flex items-center justify-center p-6">

    <div class="s-form w-full max-w-2xl shadow-2xl border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-emerald-900 tracking-tight">Join an Active Class</h2>
            <p class="text-gray-500 mt-2 text-sm">Select a session below to enter the virtual classroom.</p>
        </div>

        @forelse ($classes as $class)
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 hover:border-emerald-500 transition-colors duration-300">
                <form action="{{ route('student.class.enter', $class->uuid) }}" method="GET">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        {{-- Class Info --}}
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-800 leading-tight">
                                {{ $class->title }}
                            </h3>

                            <div class="flex items-center mt-1 text-gray-500 text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                <span>Lecturer: <strong>{{ $class->lecturer->name }}</strong></span>
                            </div>

                            @if(!empty($class->description))
                                <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                    {{ $class->description }}
                                </p>
                            @endif
                        </div>

                        {{-- Input Section --}}
                        <div class="flex flex-col gap-2 w-full md:w-64">
                            <label class="text-xs font-bold uppercase tracking-wider text-gray-400">Student Identity</label>
                            <input
                                type="text"
                                name="name"
                                class="in-box chat w-full border-gray-300 focus:ring-2 focus:ring-emerald-500"
                                placeholder="Enter your full name"
                                required
                            >
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end border-t pt-4">
                        <button type="submit" class="s-btn flex items-center gap-2">
                            <span>Join Classroom</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div id="no-class" class="flex flex-col items-center justify-center py-12 text-center">
                <div class="bg-gray-100 p-4 rounded-full mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-gray-600 font-medium">There are no active classes at the moment.</p>
                <p class="text-sm text-gray-400">Please check back later or contact your instructor.</p>
            </div>
        @endforelse

    </div>

</div>
@endsection