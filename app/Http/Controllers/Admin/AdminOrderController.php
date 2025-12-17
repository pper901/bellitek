<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a list of paid orders within a specified date range (for Revenue).
     */
    public function index(Request $request)
    {
        // 1. Get dates from query parameters, falling back to a default range (e.g., last 30 days)
        $start = $request->get('start') ? Carbon::parse($request->get('start'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->get('end') ? Carbon::parse($request->get('end'))->endOfDay() : Carbon::now()->endOfDay();

        // --- 2. Calculation for Top 5 Selling Products ---
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(qty) as total_quantity'))
            // Filter by orders that are paid and within the date range
            ->whereHas('order', function ($query) use ($start, $end) {
                $query->where('payment_status', 'paid')
                      ->whereBetween('created_at', [$start, $end]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->with('product') // Eager load the product details
            ->get();


        // --- 3. Filter orders for pagination (as before) ---
        $orders = Order::with(['user', 'items.product']) // Eager load product on items for the table
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->paginate(6); 

        return view('admin.orders.index', compact('orders', 'start', 'end', 'topProducts'));
    }
}
