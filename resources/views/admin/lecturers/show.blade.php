@extends('admin.layout')

@section('title', 'Lecturer Information')

@section('content')

@if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if (session('info'))
    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
        {{ session('info') }}
    </div>
@endif

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Lecturer Information
            </h2>

            <p class="text-sm text-gray-500">
                Review lecturer account details.
            </p>
        </div>

        <a
            href="{{ route('admin.lecturers') }}"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
        >
            Back to Lecturers
        </a>

    </div>

    <!-- Information -->
    <div class="bg-white rounded-lg shadow p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-500">
                    Name
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $lecturer->name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Email
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $lecturer->email }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Lecturer Status
                </p>

                <p class="mt-1">

                    @if ($lecturer->is_lecturer)

                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                            Lecturer
                        </span>

                    @else

                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                            Not Lecturer
                        </span>

                    @endif

                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Approval Status
                </p>

                <p class="mt-1">

                    @if ($lecturer->is_approve)

                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                            Approved
                        </span>

                    @else

                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                            Pending Approval
                        </span>

                    @endif

                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Account Created
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $lecturer->created_at->format('M d, Y H:i') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Last Updated
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $lecturer->updated_at->format('M d, Y H:i') }}
                </p>
            </div>

        </div>

    </div>

    <!-- Actions -->
    @if (!$lecturer->is_approve)

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">

            <h3 class="font-semibold text-yellow-800">
                Lecturer Approval Required
            </h3>

            <p class="text-sm text-yellow-700 mt-1">
                This lecturer is currently waiting for administrator approval.
            </p>

            <div class="mt-4">

                <form
                    method="POST"
                    action="{{ route('admin.lecturers.approve', $lecturer) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
                    >
                        Approve Lecturer
                    </button>

                </form>

            </div>

        </div>

    @else

        <div class="bg-green-50 border border-green-200 rounded-lg p-6">

            <h3 class="font-semibold text-green-800">
                Lecturer Approved
            </h3>

            <p class="text-sm text-green-700 mt-1">
                This lecturer currently has access to the lecturer dashboard.
            </p>

        </div>

    @endif

</div>

@endsection