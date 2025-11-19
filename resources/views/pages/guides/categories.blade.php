@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Categories for {{ $device }}</h1>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
@foreach($categories as $item)
    <a href="{{ route('guides.issues', [$device, $item->category]) }}"
       class="p-4 bg-gray-100 rounded shadow hover:bg-gray-200">
        {{ $item->category }}
    </a>
@endforeach
</div>

<a href="{{ route('guides.devices') }}" class="text-blue-600 mt-4 inline-block">← Back to Devices</a>
@endsection
