<!-- Assumes you are extending a main admin layout -->
@extends('admin.layout')

@section('title', 'Expense Detail')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-3">
            {{-- Back Button to Accounting Dashboard --}}
            <a href="{{ route('admin.accounting.index') }}"
               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Back
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Expense Detail</h1>
        </div>

        {{-- Link to a form to add a new expense --}}
        <a href="{{ route('admin.accounting.index') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-indigo-700 transition duration-150">
            + Add New Expense
        </a>
    </div>

    {{-- Filter Information & Total --}}
    <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-4 mb-6 rounded-lg shadow-md flex justify-between items-center">
        <div>
            <p class="font-semibold">Showing Expenses incurred from: <span class="font-mono">{{ $start->format('F j, Y') }}</span> to <span class="font-mono">{{ $end->format('F j, Y') }}</span></p>
            <p class="text-sm">Total Expenses logged: {{ number_format($expenses->total()) }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-medium">TOTAL AMOUNT</p>
            <p class="text-2xl font-bold">₦{{ number_format($totalAmount, 2) }}</p>
        </div>
    </div>

    {{-- Expenses Table --}}
    <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₦)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($expenses as $expense)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $expense->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $expense->category->name ?? 'Uncategorized' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right text-red-600">
                                ₦{{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $expense->date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
                                <a href="{{ route('admin.expenses.edit', $expense->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                {{-- Assuming a delete form for demonstration --}}
                                <form action="{{ route('admin.expenses.destroy', $expense->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">No expenses logged in this date range.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $expenses->links() }}
    </div>
</div>
@endsection