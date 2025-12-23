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
        $product = Product::with(['images','reviews.user'])->findOrFail($id);

        
        // calculate rating count + avg
        $ratingAvg  = round($product->averageRating(), 1);
        $ratingCount = $product->reviews->count();

        // SEO + OG
        $seo = [
            'title' => "{$product->name} | Buy Now",
            'description' => substr(strip_tags($product->description), 0, 160),
            'image' => optional($product->images->first())->url ?? asset('placeholder.png'),
            'url'   => url()->current(),
            'type'  => 'product'
        ];


        // Related products from same type, exclude current
        $related = Product::where('type', $product->type)
                        ->where('id', '!=', $product->id)
                        ->take(4)
                        ->get();

        return view('pages.store.product', compact('product', 'related','seo','ratingAvg','ratingCount'));
    }

    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
        ]);

        ProductReview::create([
            'product_id' => $product->id,
            'user_id'    => auth()->id(),
            'rating'     => $request->rating,
            'comment'    => $request->comment
        ]);

        return redirect()->back()->with('success', 'Thanks for your review.');
    }

}

