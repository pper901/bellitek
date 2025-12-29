<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WarehouseSetting;
use App\Models\ApiCall;
use App\Models\ApiProvider;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * The dynamically resolved ID for the Shipbubble API provider.
     * Initialized in the constructor.
     *
     * @var int
     */
    private $shipbubbleProviderId;

    /**
     * Constructor: Resolve the Shipbubble provider ID dynamically.
     */
    public function __construct()
    {
        // Fetch the provider by name/slug. Adjust 'Shipbubble' if a different
        // identifier (like 'shipbubble_api') is used in your 'api_providers' table.
        $provider = ApiProvider::where('name', 'Shipbubble')->first(); 
        
        $this->shipbubbleProviderId = $provider ? $provider->id : 0; 

        if (!$this->shipbubbleProviderId) {
            // Log an error if the provider is not found, as API cost logging will fail.
            Log::error('Shipbubble API Provider not found in the database. API cost logging will be skipped for this provider.');
        }
    }

    /**
     * Fetches existing addresses from ShipBubble and displays the current warehouse setting.
     */
    public function index()
    {
        // Only proceed if the provider ID was successfully resolved
        if (!$this->shipbubbleProviderId) {
            return view('admin.warehouse.index', [
                'addresses' => [],
                'current' => WarehouseSetting::where('id', Auth::id())->first(),
            ])->withErrors('Shipbubble API provider is not configured in the database.');
        }

        $addressesResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SHIPBUBBLE_API_KEY'),
            'Content-Type' => 'application/json',
        ])->get('https://api.shipbubble.com/v1/shipping/address');

        Log::info('ShipBubble RAW RESPONSE', [
            'status' => $addressesResponse->status(),
            'headers' => $addressesResponse->headers(),
            'body' => $addressesResponse->json(),
        ]);
        
        if ($addressesResponse->successful()) {
            $this->recordApiCallCost(
                $this->shipbubbleProviderId,
                null, 
                'shipping/address/get', // Endpoint descriptor
                1
            );
        }

        $addresses = $addressesResponse->json('data') ?? [];
        $current = WarehouseSetting::where('id', Auth::id())->first();

        return view('admin.warehouse.index', compact('addresses', 'current'));
    }

    /**
     * Stores a selected address code as the primary warehouse setting.
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_code' => 'required'
        ]);

        WarehouseSetting::updateOrCreate(['id' => auth()->id], [
            'name' => 'Warehouse Address',
            'address_code' => $request->address_code,
        ]);

        return back()->with('success', 'Warehouse address code saved successfully!');
    }

    /**
     * Displays the view for manually creating/validating a new warehouse address.
     */
    public function create()
    {
        $current = WarehouseSetting::where('id', Auth::id())->first(); // current warehouse address if exists
        return view('admin.warehouse.create', compact('current'));
    }

    /**
     * Validates a new address using the ShipBubble API and saves it as the warehouse setting.
     */
    public function storeAgain(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Only proceed if the provider ID was successfully resolved
        if (!$this->shipbubbleProviderId) {
            return back()->withErrors('Shipbubble API provider is not configured. Cannot validate address or log cost.');
        }

        // Prepare payload
        $payload = [
            'name' => $this->normalizeName($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->latitude) $payload['latitude'] = $request->latitude;
        if ($request->longitude) $payload['longitude'] = $request->longitude;

        // Call ShipBubble address validation endpoint
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SHIPBUBBLE_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.shipbubble.com/v1/shipping/address/validate', $payload);

        Log::info('ShipBubble Validate Warehouse Address Response', $response->json());

        $data = $response->json('data');

        if (!$data || !isset($data['address_code'])) {
            return back()->withErrors('Failed to validate warehouse address. Check API key and data.');
        }

        // --- NEW: Record API Cost on SUCCESS ---
        if ($response->successful()) {
            $this->recordApiCallCost(
                $this->shipbubbleProviderId,
                null, // order_id is null as this is a setup/admin call
                'shipping/address/validate',
                1
            );
        }
        // ------------------------------------

        $addressCode = $data['address_code'] ?? null;
        $formattedAddress = $data['formatted_address'] ?? $request->address;

        // Save to DB
        $warehouse = WarehouseSetting::where('id', Auth::id())->first();;
        $warehouse->name = $request->name;
        $warehouse->email = $request->email;
        $warehouse->phone = $request->phone;
        $warehouse->address = $formattedAddress;
        $warehouse->latitude = $request->latitude;
        $warehouse->longitude = $request->longitude;
        $warehouse->address_code = $addressCode;
        $warehouse->save();

        return redirect()->route('admin.warehouse.create')->with('success', 'Warehouse address validated and saved with ShipBubble code: ' . $addressCode);
    }

    /**
     * Utility function to normalize the name.
     */
    function normalizeName($name)
    {
        $words = preg_split('/\s+/', trim($name));

        if (count($words) < 2) {
            return $name . ' BelliTek';
        }

        return $name;
    }

    /**
     * Helper to record API cost into the database directly.
     * This avoids making an internal HTTP request to the accounting route.
     */
    private function recordApiCallCost(int $apiProviderId, ?int $orderId, string $endpoint, int $count = 1)
    {
        try {
            // Fetch provider cost per call
            // Using findOrFail ensures we only proceed if the provider exists (which it should, 
            // since $apiProviderId was resolved in the constructor).
            $provider = ApiProvider::findOrFail($apiProviderId);
            
            // Calculate total cost (using bcmath for precision)
            $cost = bcmul((string)$provider->cost_per_call, (string)$count, 4);

            ApiCall::create([
                'api_provider_id' => $apiProviderId,
                'order_id' => $orderId,
                'endpoint' => $endpoint,
                'count' => $count,
                'cost_cached' => $cost,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('API Provider not found when attempting to log cost.', ['provider_id' => $apiProviderId, 'endpoint' => $endpoint]);
        } catch (\Exception $e) {
            Log::error('Error recording API cost.', ['exception' => $e->getMessage(), 'provider_id' => $apiProviderId, 'endpoint' => $endpoint]);
        }
    }
}