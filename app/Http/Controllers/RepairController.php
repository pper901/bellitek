<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\RepairTracking;
use App\Models\Address;
use App\Models\User;
use App\Models\WarehouseSetting;
use App\Models\ApiProvider;
use App\Models\ApiCall;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Providers\ShipbubbleService;

class RepairController extends Controller
{
    private $shipbubbleProviderId;

    public function __construct()
    {
        $provider = ApiProvider::where('name', 'Shipbubble')->first();
        $this->shipbubbleProviderId = $provider?->id ?? 0;
    }

    public function index(Request $request)
    {
        // 1. Fetch all repairs belonging to the authenticated user (for the list)
        $userRepairs = Repair::where('user_id', auth()->id())
                            ->latest()
                            ->get();
        
        $searchedRepair = null;
        $trackingCode = $request->input('tracking_code');

        // 2. Handle the tracking code search submission
        if ($trackingCode) {
            // Find the repair by code (can be public or restricted, depending on your needs)
            $searchedRepair = Repair::where('tracking_code', $trackingCode)->first();

            // If found, immediately redirect to the detailed tracking page
            if ($searchedRepair) {
                return redirect()->route('repair.track', $trackingCode);
            }
        }
        
        // 3. Pass both the list and the search context to the view
        return view('pages.repair.index', compact('userRepairs', 'trackingCode', 'searchedRepair'));
    }
    
    // Step 1: Show booking form
    public function create()
    {
        $userAddresses = auth()->user()->addresses ?? [];
        return view('pages.repair.create', compact('userAddresses'));
    }

    // Step 1: Store repair booking
     public function store(Request $request, ShipbubbleService $shipbubble)
    {
        Log::info("Repair store function called.");

        // 1. Validation (must pass before starting the transaction)
        $request->validate([
            'device_type' => 'required|string',
            'brand'       => 'required|string',
            'issue'       => 'required|string',
            'delivery_method' => 'required|in:dropoff,shipbubble',
            'address_id'  => 'nullable|exists:addresses,id',
        ]);

        try {
            // Use DB::transaction to ensure all database changes are atomic (all-or-nothing)
            $repair = DB::transaction(function () use ($request, $shipbubble) {
                
                // 1. Initial Repair Record Creation
                $trackingCode = 'REP-' . strtoupper(Str::random(8));

                $repair = Repair::create([
                    'user_id'         => auth()->id(),
                    'customer_name'   => auth()->user()->name,
                    'device_type'     => $request->device_type,
                    'brand'           => $request->brand,
                    'issue'           => $request->issue,
                    'contact'         => auth()->user()->email,
                    'status'          => 'pending',
                    'tracking_code'   => $trackingCode,
                    'delivery_method' => $request->delivery_method,
                ]);

                Log::info("Repair record created: {$repair->id}. Checking delivery method.");

                // 2. Shipbubble Integration Logic
                if ($request->delivery_method === 'shipbubble') {
                    
                    // A. Validate/Fetch User Address Code (Sender)
                    if (!$request->address_id) {
                         // This should ideally be caught by validation, but serves as a fail-safe
                         throw new \Exception('Shipping address ID is missing for Shipbubble delivery.');
                    }
                    $userAddress = Address::findOrFail($request->address_id);

                    if (!$userAddress->address_code) {
                        Log::info("User address code missing, calling validateAddress.");
                        
                        $userAddress->address_code = $shipbubble->validateAddress([
                            'name'    => $this->normalizeName(auth()->user()->name),
                            'email'   => auth()->user()->email,
                            'phone'   => $userAddress->phonenumber,
                            'address' => "{$userAddress->street}, {$userAddress->city}, {$userAddress->state}, {$userAddress->country}",
                        ], null);

                        $userAddress->save();
                    }

                    // B. Fetch Admin/Warehouse Details (Receiver)
                    $admin = User::where('is_admin', true)->first();
                    $adminWarehouse = $admin?->warehouseSetting;
                    $adminAddressCode = $adminWarehouse?->address_code;

                    // C. CRITICAL CHECK: Ensure both codes are present
                    if (is_null($userAddress->address_code) || is_null($adminAddressCode)) {
                        
                        // Log detailed context
                        \Log::warning('Shipping Address Code Missing, triggering rollback.', [
                            'user_id' => auth()->id(),
                            'user_address_code_present' => !is_null($userAddress->address_code),
                            'admin_address_code_present' => !is_null($adminAddressCode),
                        ]);

                        // Throw an exception to trigger the transaction rollback
                        throw new \Exception('Missing required address code for shipping setup.');
                    }

                    // D. Final Update (Only runs if both codes are present)
                    $repair->update([
                        'sender_address_code'   => $userAddress->address_code,
                        'receiver_address_code' => $adminAddressCode,
                    ]);
                    
                    Log::info("Shipbubble setup complete and repair updated.");
                } else {
                    Log::info("Delivery method is Dropoff. Proceeding without shipping setup.");
                }

                return $repair; // Return the successfully created/updated repair object
            });

            // 3. Conditional Redirection (Success)
            if ($repair->delivery_method === 'shipbubble') {
                return redirect()->route('repair.rates', $repair->id)
                    ->with('success', 'Repair booked. Please proceed to select your shipping rate.');
            }

            // Default Flow (Drop-off):
            return redirect()->route('repair.track', $repair->tracking_code)
                ->with('success', 'Repair booked successfully.');

        } catch (\Exception $e) {
            // 4. Handle Transaction Failure (Rollback already performed by DB::transaction)
            Log::error('Repair booking failed and transaction rolled back.', ['exception' => $e->getMessage(), 'user_id' => auth()->id()]);
            
            // Provide a user-friendly message, potentially tailored to the error
            $errorMessage = Str::contains($e->getMessage(), 'Missing required address code')
                ? 'Shipping setup failed because required address information is incomplete. Please ensure your address is complete, or contact support.'
                : 'An unexpected error occurred while booking your repair. Please try again.';

            return back()->with('error', $errorMessage);
        }
    }

    // Step 2: Show shipping rates
    public function showRates(Repair $repair, ShipbubbleService $shipbubble)
    {
        if ($repair->delivery_method !== 'shipbubble') {
            return back()->with('error', 'Shipping rates not required for drop-off.');
        }

        $userAddress = auth()->user()->addresses()->first();
        $admin = User::where('is_admin', true)->first();
        $adminWarehouse = $admin?->warehouseSetting;

        $rates = $shipbubble->fetchRates(
            $userAddress->address_code,
            $adminWarehouse?->address_code,
            [
                [
                    'name' => $repair->device_type,
                    'quantity' => 1,
                    'price' => 100 // optional price for insurance/calc
                ]
            ]
        );

        return view('pages.repair.rates', compact('repair', 'rates'));
    }

    // Step 2: Select shipping rate
    public function selectRate(Request $request, Repair $repair)
    {
        $request->validate([
            'selected_rate' => 'required|string',
        ]);

        $repair->update([
            'selected_rate' => $request->selected_rate,
        ]);

        return redirect()->route('pages.repair.confirm', $repair->id)
            ->with('success', 'Shipping rate selected successfully.');
    }

    // Step 3: Confirm booking page
    public function confirm(Repair $repair)
    {
        return view('pages.repair.confirm', compact('repair'));
    }

    // Step 3: Pay and finalize booking
    public function pay(Request $request, Repair $repair, ShipbubbleService $shipbubble)
    {
        if ($repair->delivery_type === 'shipbubble') {
            $shipment = $shipbubble->createShipment($repair);
            if (!$shipment) {
                return back()->with('error', 'Failed to create shipment label.');
            }
            $repair->tracking_id = $shipment['order_id'] ?? $repair->tracking_code;
            $repair->save();
        }

        $repair->status = 'booked';
        $repair->save();

        return redirect()->route('repair.track', $repair->tracking_code)
            ->with('success', 'Repair booked and shipment created successfully.');
    }

    // Tracking
    public function track(string $tracking_code)
    {
        
        $repair = Repair::where('tracking_code', $tracking_code)
            ->with('steps.images')
            ->firstOrFail();

            
        Log::info("I got called in track2 ");
        return view('pages.repair.track', compact('repair'));
    }

    private function normalizeName($name)
    {
        $words = preg_split('/\s+/', trim($name));
        return count($words) < 2 ? $name . ' BelliTek' : $name;
    }
}
