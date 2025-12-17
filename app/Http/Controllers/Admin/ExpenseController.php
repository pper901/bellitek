<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Expense;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        // 1. Get dates from query parameters, falling back to a default range
        $start = $request->get('start') ? Carbon::parse($request->get('start'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->get('end') ? Carbon::parse($request->get('end'))->endOfDay() : Carbon::now()->endOfDay();

        // 2. Filter Expenses
        $expenses = Expense::with('user')
            ->whereBetween('incurred_at', [$start, $end])
            ->orderBy('incurred_at', 'desc')
            ->paginate(20);

        // Calculate total amount for the displayed range
        $totalAmount = $expenses->sum('amount');

        return view('admin.expenses.index', compact('expenses', 'start', 'end', 'totalAmount'));
    }
}
