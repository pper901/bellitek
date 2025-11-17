@extends('admin.layout')

@section('content')
<h2 class="text-2xl font-bold mb-4">Admin - Repair Requests</h2>

<table class="w-full border-collapse border">
  <thead>
    <tr class="bg-gray-200">
      <th class="border p-2">Customer</th>
      <th class="border p-2">Device</th>
      <th class="border p-2">Issue</th>
      <th class="border p-2">Contact</th>
      <th class="border p-2">Status</th>
      <th class="border p-2">Date</th>
    </tr>
  </thead>
  <tbody>
    @foreach($repairs as $r)
    <tr>
      <td class="border p-2">{{ $r->customer_name }}</td>
      <td class="border p-2">{{ $r->device_type }}</td>
      <td class="border p-2">{{ $r->issue }}</td>
      <td class="border p-2">{{ $r->contact }}</td>
      <td class="border p-2 text-red-600 font-semibold">{{ $r->status }}</td>
      <td class="border p-2">{{ $r->created_at->format('d M Y') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
