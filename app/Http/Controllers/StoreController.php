<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        // Get the latest 3 products per category
        $tools = Product::where('type', 'tool')
            ->latest()
            ->take(3)
            ->get();

        $parts = Product::where('type', 'part')
            ->latest()
            ->take(3)
            ->get();

        $devices = Product::where('type', 'device')
            ->latest()
            ->take(3)
            ->get();

        return view('pages.store.index', compact('tools', 'parts', 'devices'));
    }


    public function category($category)
    {
        $products = Product::where('type', $category)->paginate(12);

        return view('pages.store.category', compact('products', 'category'));
    }


    public function search(Request $request)
    {
        $query = $request->search;

        $products = Product::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->paginate(20);

        return view('pages.store.search', compact('products', 'query'));
    }

    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);

        // Related products from same type, exclude current
        $related = Product::where('type', $product->type)
                        ->where('id', '!=', $product->id)
                        ->take(4)
                        ->get();

        return view('pages.store.product', compact('product', 'related'));
    }

}

