@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-20 text-center">

    <h1 class="text-2xl font-bold mb-4">
        Lecturer Account Pending Approval
    </h1>

    <p class="text-gray-600 mb-8">
        Your lecturer account has been created successfully, but it has not yet been approved by an administrator.
        Please wait while your application is reviewed. You will be able to access the lecturer dashboard once your account has been approved.
    </p>

    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg p-4 mb-8">
        <p class="font-semibold">
            Approval Pending
        </p>

        <p class="text-sm mt-1">
            You will gain access to your lecturer dashboard after approval.
        </p>
    </div>
    <p>For faster approval</p>
    <a href="{{ route('contact') }}"
       class="inline-block px-8 py-3 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700">
        Contact Admin
    </a>

</div>
@endsection