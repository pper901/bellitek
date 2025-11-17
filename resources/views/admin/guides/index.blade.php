@extends('admin.layout')

@section('title', 'Guides Management')

@section('content')

<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Guides</h2>
        <a href="{{ route('admin.guides.create') }}"
           class="bg-blue-500 text-white px-4 py-2 rounded">Create Guide</a>
    </div>

    <table class="w-full border">
        <tr class="bg-gray-100">
            <th class="p-2">Device</th>
            <th class="p-2">Model</th>
            <th class="p-2">Issue</th>
            <th></th>
        </tr>
        @foreach ($guides as $g)
        <tr class="border-b">
            <td class="p-2">{{ $g->device }}</td>
            <td class="p-2">{{ $g->model }}</td>
            <td class="p-2">{{ $g->issue }}</td>
            <td class="p-2">
                <a href="{{ route('admin.guides.show',$g) }}" class="text-blue-600">View</a>
                |
                <a href="{{ route('admin.guides.edit',$g) }}" class="text-green-600">Edit</a>
            </td>
        </tr>
        @endforeach
    </table>

    {{ $guides->links() }}
</div>

@endsection
