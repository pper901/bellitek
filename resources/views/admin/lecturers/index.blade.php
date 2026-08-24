@extends('admin.layout')

@section('title', 'Lecturers')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Lecturers
            </h2>

            <p class="text-sm text-gray-500">
                Manage and review all lecturer accounts.
            </p>
        </div>

        <a href="{{ route('admin.lecturers.pending') }}"
           class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
            Pending Approval
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white rounded-lg shadow p-5">

        <form method="GET"
              action="{{ route('admin.lecturers') }}"
              class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Search
                </label>

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Name or email..."
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500"
                >
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Approval Status
                </label>

                <select
                    name="status"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500"
                >
                    <option value="">All</option>

                    <option value="approved"
                        {{ request('status') === 'approved' ? 'selected' : '' }}>
                        Approved
                    </option>

                    <option value="pending"
                        {{ request('status') === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex items-end gap-2">

                <button
                    type="submit"
                    class="px-5 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                >
                    Search
                </button>

                <a
                    href="{{ route('admin.lecturers') }}"
                    class="px-5 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                >
                    Reset
                </a>

            </div>

        </form>

    </div>

    <!-- Lecturers Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Lecturer
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Email
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Status
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Joined
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                            Action
                        </th>
                    </tr>

                </thead>

                <tbody class="bg-white divide-y divide-gray-200">

                    @forelse ($lecturers as $lecturer)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $lecturer->name }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $lecturer->email }}
                            </td>

                            <td class="px-6 py-4">

                                @if ($lecturer->is_approve)

                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                        Approved
                                    </span>

                                @else

                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $lecturer->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.lecturers.show', $lecturer) }}"
                                    class="text-red-600 hover:text-red-800 font-medium"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No lecturers found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($lecturers->hasPages())

            <div class="p-4 border-t">
                {{ $lecturers->links() }}
            </div>

        @endif

    </div>

</div>

@endsection