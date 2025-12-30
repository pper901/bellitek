<?php

namespace App\Services;

use App\Models\WarehouseSetting;
use App\Models\ApiProvider;
use App\Models\ApiCall;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WarehouseService
{
    protected $apiKey;
    protected $provider;

    public function __construct()
    {
        $this->apiKey = config('services.shipbubble.key');
        $this->provider = ApiProvider::where('name', 'Shipbubble')->first();
    }

    /**
     * Validate and save warehouse for a specific user with automatic geocoding
     */
    public function validateAndSaveForUser(int $userId, array $data)
    {
        $data['name'] = $this->normalizeName($data['name']);

        // 1. Prepare the ShipBubble Payload
        $payload = [
            'name'    => $data['name'],
            'email'   => $data['email'],
            'phone'   => $data['phone'],
            'address' => $data['address'],
        ];

        // 2. Only add coordinates if they are actually provided
        if (!empty($data['latitude'])) {
            $payload['latitude'] = $data['latitude'];
        }
        if (!empty($data['longitude'])) {
            $payload['longitude'] = $data['longitude'];
        }

        // 3. Call ShipBubble API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.shipbubble.com/v1/shipping/address/validate', $payload);

        if (!$response->successful()) {
            Log::error('ShipBubble Validation Error', ['response' => $response->json()]);
            throw new \Exception('ShipBubble validation failed: ' . ($response->json('message') ?? 'Unknown Error'));
        }

        $apiData = $response->json('data');
        $this->logApiCost('shipping/address/validate');

        // 4. Save locally (using null if they weren't provided)
        return WarehouseSetting::updateOrCreate(
            [
                'user_id' => $userId, 
                'address_code' => $apiData['address_code']
            ],
            [
                'name'      => $data['name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'],
                'address'   => $apiData['formatted_address'] ?? $data['address'],
                'latitude'  => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ]
        );
    }

    /**
     * Geocode an address string into Latitude/Longitude using Nominatim (OSM)
     */
    private function getCoordinatesFromAddress($address)
    {
        // Add a User-Agent header as required by Nominatim's Policy
        $response = Http::withHeaders([
            'User-Agent' => 'BelliTek-App'
        ])->get("https://nominatim.openstreetmap.org/search", [
            'q' => $address,
            'format' => 'json',
            'limit' => 1
        ]);

        if ($response->successful() && isset($response->json()[0])) {
            return [
                'lat' => $response->json()[0]['lat'],
                'lng' => $response->json()[0]['lon'],
            ];
        }

        return null;
    }

    private function normalizeName($name)
    {
        $words = preg_split('/\s+/', trim($name));
        return (count($words) < 2) ? $name . ' BelliTek' : $name;
    }

    private function logApiCost($endpoint)
    {
        if (!$this->provider) return;
        ApiCall::create([
            'api_provider_id' => $this->provider->id,
            'endpoint' => $endpoint,
            'count' => 1,
            'cost_cached' => $this->provider->cost_per_call,
        ]);
    }
}