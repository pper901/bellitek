<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $address = $user->addresses()->latest()->first();

        return view('pages.users.account', compact('user', 'address'));
    }

    public function updateAddress(Request $request)
    {
        $request->validate([
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            'phonenumber' => 'required',
        ]);

        $address = auth()->user()->addresses()->updateOrCreate(
            ['user_id' => auth()->id()],
            $request->only('street', 'city', 'state', 'country', 'postal_code', 'phonenumber')
        );

        return back()->with('success', 'Address updated successfully.');
    }

}

