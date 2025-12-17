@extends('admin.layout')

@section('title', 'Financial Accounting')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">💰 Financial Accounting Dashboard</h1>

    {{-- Session Messages --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>Please fix the following errors:</p>
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Date Range Filter --}}
    <div class="bg-white p-4 rounded-lg shadow-md mb-6">
        <form method="GET" action="{{ route('admin.accounting.index') }}" class="flex items-end space-x-4">
            <div class="flex-1">
                <label for="start" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start" id="start" value="{{ $start->format('Y-m-d') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex-1">
                <label for="end" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end" id="end" value="{{ $end->format('Y-m-d') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700">
                Filter
            </button>
        </form>
    </div>

    {{-- --- Accounting Summary Cards --- --}}
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Summary ({{ $start->format('M j, Y') }} - {{ $end->format('M j, Y') }})</h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        
        {{-- Date range parameters for links --}}
        @php
            $dateParams = [
                'start' => $start->toDateString(), 
                'end' => $end->toDateString()
            ];
        @endphp

        {{-- Card 1: Revenue (Links to Orders List) --}}
        <a href="{{ route('admin.orders.index', $dateParams) }}" 
        class="block bg-white p-5 rounded-lg shadow-xl border-l-4 border-green-500 transition duration-300 transform hover:scale-[1.02] hover:shadow-2xl cursor-pointer">
            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
            <p class="text-3xl font-bold text-green-700">₦{{ number_format($revenue, 2) }}</p>
        </a>

        {{-- Card 2: API Cost (Links to API Calls List) --}}
        <a href="{{ route('admin.accounting.apicalls.index', $dateParams) }}"
        class="block bg-white p-5 rounded-lg shadow-xl border-l-4 border-red-500 transition duration-300 transform hover:scale-[1.02] hover:shadow-2xl cursor-pointer">
            <p class="text-sm font-medium text-gray-500">Total API Cost</p>
            <p class="text-3xl font-bold text-red-700">₦{{ number_format($apiCost, 2) }}</p>
        </a>

        {{-- Card 3: Expenses (Links to Expenses List) --}}
        <a href="{{ route('admin.expenses.index', $dateParams) }}"
        class="block bg-white p-5 rounded-lg shadow-xl border-l-4 border-yellow-500 transition duration-300 transform hover:scale-[1.02] hover:shadow-2xl cursor-pointer">
            <p class="text-sm font-medium text-gray-500">Total Expenses</p>
            <p class="text-3xl font-bold text-yellow-700">₦{{ number_format($expenses, 2) }}</p>
        </a>

        {{-- Card 4: Profit (Not usually linked to a detail page, but visually indicates status) --}}
        <div class="bg-white p-5 rounded-lg shadow-xl border-l-4 {{ $profit >= 0 ? 'border-blue-500' : 'border-red-500' }}">
            <p class="text-sm font-medium text-gray-500">Net Profit</p>
            <p class="text-3xl font-bold {{ $profit >= 0 ? 'text-blue-700' : 'text-red-700' }}">₦{{ number_format($profit, 2) }}</p>
        </div>
    </div>
    
    <hr class="my-6">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- --- Section A: Add New Expense --- --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-xl">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">✍️ Record New Expense</h3>
            <form method="POST" action="{{ route('admin.accounting.expense.add') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" required value="{{ old('title') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (₦)</label>
                        <input type="number" step="0.01" name="amount" id="amount" required value="{{ old('amount') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="incurred_at" class="block text-sm font-medium text-gray-700">Date Incurred</label>
                        <input type="date" name="incurred_at" id="incurred_at" value="{{ old('incurred_at', \Carbon\Carbon::now()->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <button type="submit" class="mt-6 w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700">
                    Add Expense
                </button>
            </form>
        </div>

        {{-- --- Section B: API Provider Cost Configuration --- --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-xl">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">⚙️ API Provider Cost Configuration</h3>
            <div class="space-y-4">
                @foreach ($providers as $provider)
                    <div class="p-4 border rounded-md flex justify-between items-center bg-gray-50">
                        <span class="font-bold text-gray-900 flex-1">{{ $provider->name }}</span>
                        
                        <form method="POST" action="{{ route('admin.accounting.provider.update', $provider) }}" class="flex items-center space-x-3 w-2/3">
                            @csrf
                            <input type="hidden" name="_method" value="POST"> {{-- Required for POST route --}}
                            
                            <label for="cost_{{ $provider->id }}" class="text-sm text-gray-600">Cost/Call (₦)</label>
                            <input type="number" step="0.0001" name="cost_per_call" id="cost_{{ $provider->id }}" required 
                                   value="{{ $provider->cost_per_call }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm text-right">
                            
                            <button type="submit" class="px-3 py-1 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">
                                Update
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>
    
    <hr class="my-6">

    {{-- --- Section C: Chart Data (Requires JavaScript implementation) --- --}}
    <div class="bg-white p-6 rounded-lg shadow-xl mb-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">📈 Last 12 Months Performance</h3>
        <canvas id="accountingChart"></canvas>
    </div>

</div>

@push('scripts')
{{-- Include Chart.js library --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script> 

<script>
document.addEventListener('DOMContentLoaded', function () {
    fetch('{{ route('admin.accounting.chart.data') }}')
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('accountingChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [
                        {
                            label: 'Revenue (₦)',
                            data: data.revenue,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            tension: 0.3
                        },
                        {
                            label: 'API Cost (₦)',
                            data: data.apicost,
                            borderColor: 'rgb(255, 99, 132)',
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            tension: 0.3
                        },
                        {
                            label: 'Expenses (₦)',
                            data: data.expenses,
                            borderColor: 'rgb(255, 205, 86)',
                            backgroundColor: 'rgba(255, 205, 86, 0.5)',
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
});
</script>
@endpush

@endsection