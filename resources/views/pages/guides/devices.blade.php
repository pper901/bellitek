@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Select a Device</h1>

<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
@foreach($devices as $item)
    <a href="{{ route('guides.categories', $item->device) }}"
       class="p-4 bg-gray-100 rounded shadow hover:bg-gray-200">
        {{ $item->device }}
    </a>
@endforeach
</div>
@endsection
