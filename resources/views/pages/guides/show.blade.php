@extends('layout')

@section('content')

<h1 class="text-3xl font-bold mb-6">{{ $issue }}</h1>

@foreach ($guides as $guide)
    <div class="mb-6 bg-white p-6 rounded shadow">

        <h2 class="text-xl font-bold mb-2">
            {{ $guide->brand }} {{ $guide->model }}
        </h2>

        @foreach($guide->resources as $res)
        <div class="p-4 border rounded mb-4 bg-gray-50">
            <p><strong>Cause:</strong> {{ $res->cause }}</p>
            <p><strong>Solution:</strong> {{ $res->solution }}</p>
            <p class="mt-2 whitespace-pre-line">{{ $res->details }}</p>
        </div>
        @endforeach

    </div>
@endforeach

<a href="{{ route('guides.issues', [$device, $category]) }}" class="text-blue-600">← Back</a>

@endsection
