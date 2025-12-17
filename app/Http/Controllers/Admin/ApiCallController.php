<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ApiCall;

class ApiCallController extends Controller
{
        /**
     * Display a list of API calls within a specified date range (for API Cost).
     */
    public function index(Request $request)
    {
        // 1. Get dates from query parameters, falling back to a default range
        $start = $request->get('start') ? Carbon::parse($request->get('start'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->get('end') ? Carbon::parse($request->get('end'))->endOfDay() : Carbon::now()->endOfDay();

        // 2. Filter API Calls
        $apiCalls = ApiCall::with('provider')
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate total cost for the displayed range
        $totalCost = $apiCalls->sum('cost_cached');

        return view('admin.accounting.apicalls.index', compact('apiCalls', 'start', 'end', 'totalCost'));
    }
}
