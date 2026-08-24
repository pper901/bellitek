@extends('admin.layout')

@section('title', 'Pending Lecturer Approvals')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Pending Lecturer Approvals
            </h2>

            <p class="text-sm text-gray-500">
                These lecturers are waiting for administrator approval.
            </p>
        </div>

        <a
            href="{{ route('admin.lecturers') }}"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
        >
            All Lecturers
        </a>

    </div>

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
                            Applied
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

                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $lecturer->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('admin.lecturers.show', $lecturer) }}"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                                >
                                    Review
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="px-6 py-10 text-center">

                                <div class="text-green-600 text-lg font-semibold">
                                    No pending lecturers
                                </div>

                                <p class="text-gray-500 mt-1">
                                    All lecturer applications have been reviewed.
                                </p>

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