@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-20 text-center">

    <h1 class="text-2xl font-bold mb-4">
        Become a Lecturer
    </h1>

    <p class="text-gray-600 mb-8">
        You’re about to enable lecturer access and create classes.
    </p>

    <form method="POST" action="{{ route('lecturer.become') }}">
        @csrf
        <button class="px-8 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700">
            Continue as Lecturer
        </button>
    </form>

</div>
@endsection
