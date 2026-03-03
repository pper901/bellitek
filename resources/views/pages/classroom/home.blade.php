@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-16 px-6 text-center">

    {{-- Logo --}}
    <div class="mb-12">
        <a href="/">
            <img src="{{ asset('images/generalClass.png') }}"
                 alt="GeneralClass"
                 class="mx-auto h-24">
        </a>
    </div>

    {{-- Main panel --}}
    <div class="bg-white shadow-lg rounded-xl p-10">

        <h1 class="text-2xl font-bold mb-8">
            Welcome to GeneralClass
        </h1>

        <div class="flex flex-col sm:flex-row justify-center gap-6">

            {{-- Lecturer --}}
            <a href="{{ route('lecturer.login') }}"
               class="px-8 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                Lecturer Login
            </a>

            {{-- Student --}}
            <a href="{{ route('student.join') }}"
               class="px-8 py-3 rounded-lg bg-gray-800 text-white font-semibold hover:bg-gray-900 transition">
                Student Login
            </a>

        </div>
    </div>

</div>
@endsection
