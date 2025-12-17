<?php

namespace App\Services\Providers;

use App\Models\ApiProvider;
use App\Models\ApiCall;
use App\Models\Address;
use App\Models\Repair;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ShipbubbleService
{
    public const SHIPBUBBLE_IDENTIFIER = 'Shipbubble';

    protected string $baseUrl = 'https://api.shipbubble.com/v1/';
    protected string $apiKeyEnv = 'SHIPBUBBLE_API_KEY';
    protected int $providerId;

    public function __construct()
    {
        $provider = ApiProvider::where('name', self::SHIPBUBBLE_IDENTIFIER)->first();
        $this->providerId = $provider?->id ?? 0;

        if (!$this->providerId) {
            Log::error('Shipbubble API provider not found. API calls will not be logged.');
        }
    }

    /* -----------------------------------------------------------------
     |  CORE API CALL WRAPPER (Logs cost automatically)
     |-----------------------------------------------------------------*/
    protected function makeApiCall(
        string $method,
        string $endpoint,
        array $payload = [],
        ?int $orderId = null,
        int $count = 1
    ) {
        try {
            $response = Http::withToken(env($this->apiKeyEnv))
                ->acceptJson()
                ->{$method}($this->baseUrl . $endpoint, $payload);

            if ($response->successful()) {
                $this->logApiCall($endpoint, $orderId, $count);
                return $response;
            }

            Log::error('Shipbubble API failed', [
                'endpoint' => $endpoint,
                'payload' => $payload,
                'response' => $response->json(),
            ]);

            return null;

        } catch (\Throwable $e) {
            Log::error('Shipbubble API exception', [
                'endpoint' => $endpoint,
                'exception' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /* -----------------------------------------------------------------
     |  ADDRESS VALIDATION (CACHED)
     |-----------------------------------------------------------------*/
    public function validateAddress(array $payload, ?int $orderId = null): ?string
    {
        $cacheKey = 'shipbubble_address_' . md5(json_encode($payload));

        return Cache::remember($cacheKey, 604800, function () use ($payload, $orderId) {

            $response = $this->makeApiCall(
                'post',
                'shipping/address/validate',
                $payload,
                $orderId
            );

            if (!$response) {
                return null;
            }

            return $response->json('data.address_code');
        });
    }

    /* -----------------------------------------------------------------
     |  FETCH SHIPPING RATES
     |-----------------------------------------------------------------*/
    public function fetchRates(
        int $senderAddressCode,
        int $receiverAddressCode,
        array $packageItems
    ): array {

        $payload = [
            "sender_address_code"   => $senderAddressCode,
            "reciever_address_code" => $receiverAddressCode,
            "pickup_date"           => now()->addDay()->format('Y-m-d'),
            "category_id"           => $this->getElectronicsCategoryId(),
            "package_items"         => $packageItems,
            "package_dimension"     => [
                "length" => 10,
                "width"  => 10,
                "height" => 10,
            ],
        ];

        $response = $this->makeApiCall(
            'post',
            'shipping/fetch_rates',
            $payload
        );

        return $response?->json('data') ?? [];
    }

    /* -----------------------------------------------------------------
     |  CREATE SHIPMENT / LABEL
     |-----------------------------------------------------------------*/
    public function createShipment(Repair $repair): ?array
    {
        if (!$repair->selected_rate->request_token || !$repair->selected_rate->courier_code || !$repair->selected_rate->service_code) {
            Log::error('Missing shipment data', ['repair_id' => $repair->id]);
            return null;
        }

        $payload = [
            "request_token" => $repair->selected_rate->request_token,
            "courier_id"    => $repair->selected_rate->courier_code,
            "service_code"  => $repair->selected_rate->service_code,
        ];

        $response = $this->makeApiCall(
            'post',
            'shipping/labels',
            $payload,
            $repair->id
        );

        return $response?->json('data');
    }

    /* -----------------------------------------------------------------
     |  GET ELECTRONICS CATEGORY ID (CACHED)
     |-----------------------------------------------------------------*/
    protected function getElectronicsCategoryId(): ?int
    {
        $cacheKey = 'shipbubble_electronics_category_id';

        return Cache::remember($cacheKey, 604800, function () {

            $response = $this->makeApiCall(
                'get',
                'shipping/labels/categories'
            );

            if (!$response) {
                Log::error('Shipbubble category fetch failed');
                return null;
            }

            $categories = $response->json('data') ?? [];

            $category = collect($categories)
                ->firstWhere('category', 'Electronics and gadgets');

            if (!$category) {
                Log::warning('Electronics category not found in Shipbubble');
            }

            return $category['category_id'] ?? null;
        });
    }

    /* -----------------------------------------------------------------
     |  API COST LOGGER
     |-----------------------------------------------------------------*/
    protected function logApiCall(string $endpoint, ?int $orderId, int $count = 1): void
    {
        if (!$this->providerId) {
            return;
        }

        try {
            $provider = ApiProvider::findOrFail($this->providerId);
            $cost = bcmul((string)$provider->cost_per_call, (string)$count, 4);

            ApiCall::create([
                'api_provider_id' => $this->providerId,
                'order_id'        => $orderId,
                'endpoint'        => $endpoint,
                'count'           => $count,
                'cost_cached'     => $cost,
            ]);
        } catch (\Throwable $e) {
            Log::error('API cost logging failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
