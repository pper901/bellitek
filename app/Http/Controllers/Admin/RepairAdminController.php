<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\RepairTracking;
use App\Models\RepairTrackingImage;
use Illuminate\Http\Request;

class RepairAdminController extends Controller
{
    public function index()
    {
        $repairs = Repair::latest()->paginate(20);
        return view('admin.repairs.index', compact('repairs'));
    }

    public function addStep(Request $request, Repair $repair)
    {
        $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
            'images.*'    => 'image|max:2048',
        ]);

        $step = RepairTracking::create([
            'repair_id'  => $repair->id,
            'title'      => $request->title,
            'description'=> $request->description,
            'status'     => 'in_progress',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('repairs', 'public');

                RepairTrackingImage::create([
                    'repair_tracking_id' => $step->id,
                    'image_path' => $path,
                ]);
            }
        }

        $repair->update(['status' => 'in_progress']);

        return back()->with('success', 'Repair step added');
    }
}
