@extends('layout')

@section('content')
<h1 class="text-2xl font-bold mb-4">Issues for {{ $device }} → {{ $category }}</h1>

<div class="space-y-3">
@foreach($issues as $item)
    <a href="{{ route('guides.show', [$device, $category, $item->issue]) }}"
       class="block p-4 bg-gray-100 rounded shadow hover:bg-gray-200">
        {{ $item->issue }}
    </a>
@endforeach
</div>

<a href="{{ route('guides.categories', $device) }}" class="text-blue-600 mt-4 inline-block">← Back</a>
@endsection
