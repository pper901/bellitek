<?php

namespace App\Http\Controllers;

use App\Models\Product;


class PageController extends Controller
{
    public function home()
    {
        // Fetch top 3 items per category with Eager Loading to prevent N+1 image queries
        $tools = Product::with('images')
            ->where('type', 'tool')
            ->latest()
            ->take(3)
            ->get();

        $parts = Product::with('images')
            ->where('type', 'part')
            ->latest()
            ->take(3)
            ->get();

        $devices = Product::with('images')
            ->where('type', 'device')
            ->latest()
            ->take(3)
            ->get();

        return view('pages.home', compact('tools', 'parts', 'devices'));
    }

    public function services() {
        return view('pages.services');
    }

    public function contact() {
        return view('pages.contact');
    }
}
