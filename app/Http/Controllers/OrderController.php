<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
        ->with([
            // Eager load the items in the order
            'items' => function ($query) {
                // Eager load the product and its images for the items
                $query->with('product.images');
            }
        ])
        ->latest()
        ->get();

        return view('pages.users.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->with([
                // Load items (OrderItems)
                'items' => function ($query) {
                    // For each item, load its product, and for each product, load its images.
                    $query->with('product.images'); 
                }
            ])
            ->firstOrFail();

        return view('pages.users.orders.show', compact('order'));
    }
}
