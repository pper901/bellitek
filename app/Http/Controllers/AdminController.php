<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repair;

class AdminController extends Controller
{
    public function index()
    {
        $repairs = Repair::latest()->get();
        return view('admin.dashboard', compact('repairs'));
    }
}

