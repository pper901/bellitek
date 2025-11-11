<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repair;

class RepairController extends Controller
{
    public function trackForm() {
        return view('pages.track');
    }

    public function trackSubmit(Request $request) {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'device_type' => 'required|string|max:255',
            'issue' => 'required|string|max:500',
            'contact' => 'required|string|max:50',
        ]);

        $data['status'] = 'Pending';
        Repair::create($data);

        return redirect()->back()->with('success', 'Your repair request has been logged!');
    }

    public function adminView() {
        $repairs = Repair::latest()->get();
        return view('admin.repairs', compact('repairs'));
    }
}

