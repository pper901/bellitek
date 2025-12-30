<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    // Show cart page
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        return view('pages.store.cart', compact('cartItems'));
    }

    // Add product to cart
    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $quantity = max(1, (int) $request->input('qty', 1));
        $userId = auth()->id();

        // 1. Find if this product is already in the DB cart for this user
        $cartItem = CartItem::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

        $currentQty = $cartItem ? $cartItem->qty : 0;
        $newQty = $currentQty + $quantity;

        // 2. Stock Check
        if ($newQty > $product->stock) {
            return back()->with('error', "Only $product->stock units available.");
        }

        // 3. Update or Create in Database
        if ($cartItem) {
            $cartItem->update(['qty' => $newQty]);
        } else {
            CartItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'qty' => $quantity,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    // Remove product from cart
    public function remove(Request $request, $id)
    {
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('id', $id)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return back()->with('success', 'Product removed from cart.');
    }

    // Checkout (for now just clears cart)
    public function checkout(Request $request)
    {
        CartItem::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Checkout complete!');
    }


    public function update(Request $request, $id)
    {
        // Find the cart item
        $cartItem = CartItem::findOrFail($id);
        $product = $cartItem->product;

        // Check action
        $action = $request->input('action');

        if ($action === 'increment') {
            // Prevent exceeding stock
            if ($cartItem->qty + 1 > $product->stock) {
                return back()->with('error', "Only {$product->stock} units available.");
            }
            $cartItem->qty += 1;
        }

        if ($action === 'decrement') {
            // Avoid going below 1
            if ($cartItem->qty <= 1) {
                return back()->with('error', 'Minimum quantity is 1.');
            }
            $cartItem->qty -= 1;
        }

        $cartItem->save();

        return back()->with('success', 'Cart updated successfully.');
    }


}
