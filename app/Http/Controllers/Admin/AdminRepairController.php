<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Repair;
use App\Models\RepairStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminRepairController extends Controller
{
    /**
     * Display a list of all repairs for admin review.
     */
    public function index()
    {
        // Load all repairs, sorted by creation date, with customer details
        $repairs = Repair::with('user')->latest()->paginate(15);

        // Define repair status options
        $statuses = [
            'pending', 'awaiting_pickup', 'in_progress', 'awaiting_parts',
            'ready_for_shipment', 'fixing_completed', 'cancelled', 'delivered'
        ];

        return view('admin.repairs.index', compact('repairs', 'statuses'));
    }

    /**
     * Show the details of a single repair and the forms for updates.
     */
    public function show(Repair $repair)
    {
        // Eager load steps and the associated user/engineer for the steps
        $repair->load(['steps.engineer', 'user']);

        // Define status options for the update form
        $statusOptions = [
            'pending' => 'Pending Review', 
            'awaiting_pickup' => 'Awaiting Pickup (ShipBubble)',
            'in_progress' => 'In Progress', 
            'awaiting_parts' => 'Awaiting Parts',
            'fixing_completed' => 'Fixing Completed',
            'ready_for_shipment' => 'Ready for Shipment/Pickup', 
            'delivered' => 'Delivered/Closed',
            'cancelled' => 'Cancelled'
        ];

        return view('admin.repairs.show', compact('repair', 'statusOptions'));
    }

    /**
     * Update the status of a specific repair.
     */
    public function updateStatus(Request $request, Repair $repair)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,awaiting_pickup,in_progress,awaiting_parts,fixing_completed,ready_for_shipment,delivered,cancelled',
        ]);

        $oldStatus = $repair->status;
        $repair->status = $validated['status'];
        $repair->save();

        Log::info("Admin updated repair status.", [
            'repair_id' => $repair->id,
            'admin_id' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $repair->status
        ]);
        
        // Add a step to the timeline automatically for status change
        RepairStep::create([
            'repair_id' => $repair->id,
            'title' => 'Status Updated',
            'description' => "Repair status changed from '{$oldStatus}' to '{$repair->status}'.",
            'engineer_id' => auth()->id(),
        ]);

        return back()->with('success', "Repair #{$repair->tracking_code} status updated to {$repair->status} and step added to timeline.");
    }

    /**
     * Add a new tracking step (update) to the repair process.
     */
    public function addStep(Request $request, Repair $repair)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // Note: Image upload logic is complex and omitted, but placeholder for structure
            // 'images' => 'nullable|array|max:3',
            // 'images.*' => 'image|max:2048',
        ]);

        RepairStep::create([
            'repair_id' => $repair->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'engineer_id' => auth()->id(), // Associate the step with the current admin/engineer
        ]);

        // [TODO: Add image saving logic here if implemented]

        return back()->with('success', 'Repair step added successfully to the customer timeline.');
    }
}