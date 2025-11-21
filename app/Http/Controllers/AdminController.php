<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function dashboard() { return view('admin.dashboard'); }
    // public function guides() { return view('admin.guides'); }
    // public function products() { return view('admin.products'); }
    public function repairs() { return view('admin.repairs'); }
    public function repairLogs() { return view('admin.repair-logs'); }
    public function sales() { return view('admin.sales'); }
}
