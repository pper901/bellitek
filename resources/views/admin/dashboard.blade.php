@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-10">
  <h2 class="text-2xl font-bold mb-4">Repair Requests</h2>
  <table class="w-full bg-white shadow rounded-lg">
    <thead class="bg-gray-100 text-left">
      <tr>
        <th class="p-3">Customer</th>
        <th class="p-3">Device</th>
        <th class="p-3">Issue</th>
        <th class="p-3">Tracking ID</th>
        <th class="p-3">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach(\App\Models\Repair::all() as $repair)
      <tr class="border-b">
        <td class="p-3">{{ $repair->customer_name }}</td>
        <td class="p-3">{{ $repair->device_type }}</td>
        <td class="p-3">{{ $repair->issue_description }}</td>
        <td class="p-3">{{ $repair->tracking_id }}</td>
        <td class="p-3">{{ $repair->status }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
