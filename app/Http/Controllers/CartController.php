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

        // Requested quantity (default 1)
        $quantity = $request->input('qty', 1);

        // Check stock
        if ($quantity > $product->stock) {
            return back()->with('error', 'You cannot add more than the available stock.');
        }

        // If product exists in cart, check combined quantity
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $newQty = $cart[$productId]['quantity'] + $quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Only ' . $product->stock . ' units left in stock.');
            }
            $cart[$productId]['quantity'] = $newQty;
        } else {
            $cart[$productId] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => optional($product->images->first())->path ?? null,
            ];
        }

        session()->put('cart', $cart);

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
