<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ApiProvider;
use App\Models\ApiCall;
use App\Models\Expense;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->get('start') ? Carbon::parse($request->get('start')) : Carbon::now()->startOfMonth();
        $end = $request->get('end') ? Carbon::parse($request->get('end')) : Carbon::now()->endOfMonth();

        // 1. Total Revenue (What customers paid you)
        $revenue = Order::whereBetween('created_at', [$start, $end])
                    ->where('payment_status', 'paid')
                    ->sum('grand_total');

        // 2. Cost of Goods Sold (COGS)
        // We get all items from paid orders in this date range
        $totalProductPurchaseCost = OrderItem::whereHas('order', function ($query) use ($start, $end) {
                        $query->whereBetween('created_at', [$start, $end])
                            ->where('payment_status', 'paid');
                    })
                    ->join('products', 'order_items.product_id', '=', 'products.id')
                    ->selectRaw('SUM(order_items.quantity * products.purchase_price) as total_cost')
                    ->value('total_cost') ?? 0;

        // 3. API cost aggregated
        $apiCost = ApiCall::whereBetween('created_at', [$start, $end])
                    ->sum('cost_cached');

        // 4. Expenses
        $expenses = Expense::whereBetween('incurred_at', [$start, $end])->sum('amount');

        // 5. Final Profit Calculation
        // Profit = Revenue - (Cost of Buying Products + API Fees + Other Expenses)
        $profit = $revenue - ($totalProductPurchaseCost + $apiCost + $expenses);

        $providers = ApiProvider::all();

        return view('admin.accounting.index', compact(
            'revenue',
            'totalProductPurchaseCost', 
            'apiCost',
            'expenses',
            'profit',
            'providers',
            'start',
            'end'
        ));
    }

    // API: update provider costs
    public function updateProvider(Request $request, ApiProvider $provider)
    {
        $request->validate(['cost_per_call' => 'required|numeric|min:0']);
        $provider->update(['cost_per_call' => $request->cost_per_call]);
        return back()->with('success', 'Provider cost updated.');
    }


    // Add expense
    public function addExpense(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric',
            'incurred_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        Expense::create(array_merge($data, ['user_id' => auth()->id()]));
        return back()->with('success', 'Expense recorded');
    }

    // Chart data for dashboard (monthly revenue, api cost, expenses)
    public function chartData(Request $request)
    {
        $months = collect();
        $labels = [];
        $revenue = [];
        $apicost = [];
        $expenses = [];

        for ($i = 11; $i >= 0; $i--) {
            $m = Carbon::now()->subMonths($i);
            $labels[] = $m->format('M Y');

            $start = $m->copy()->startOfMonth();
            $end = $m->copy()->endOfMonth();

            $revenue[] = (float) Order::whereBetween('created_at', [$start, $end])->where('payment_status','paid')->sum('grand_total');
            $apicost[] = (float) ApiCall::whereBetween('created_at', [$start, $end])->sum('cost_cached');
            $expenses[] = (float) Expense::whereBetween('incurred_at', [$start, $end])->sum('amount');
        }

        return response()->json(compact('labels','revenue','apicost','expenses'));
    }
}