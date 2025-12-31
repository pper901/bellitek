<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WarehouseService;
use App\Models\WarehouseSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    protected $service;

    public function __construct(WarehouseService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a list of user warehouses and remote options
     */
    public function index()
    {
        return view('admin.warehouse.index', [
            'warehouses' => WarehouseSetting::where('user_id', Auth::id())->get()
        ]);
    }

    /**
     * Show the create form
     */
    public function create()
    {
        // Fetch the very first (or latest) warehouse record in the table
        $current = Warehouse::first(); 

        // Pass it to the view using compact()
        return view('admin.warehouse.create', compact('current'));
    }

    /**
     * Process the validation and storage
     */
    public function storeAgain(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'phone'     => 'required|string|max:20',
            'address'   => 'required|string|max:500',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        try {
            $this->service->validateAndSaveForUser(Auth::id(), $validated);
            
            return redirect()->route('admin.warehouse.index')
                             ->with('success', 'Warehouse validated and saved successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors($e->getMessage());
        }
    }
}