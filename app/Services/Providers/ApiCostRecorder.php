<?php

namespace App\Services\Providers;

use App\Models\ApiCall;
use App\Models\ApiProvider;

class ApiCostRecorder
{
    public static function record(
        string $providerSlug,
        ?int $orderId,
        string $endpoint,
        int $count = 1
    ): void {
        $provider = ApiProvider::where('identifier', $providerSlug)->first();

        if (!$provider) {
            return;
        }

        ApiCall::create([
            'api_provider_id' => $provider->id,
            'order_id'        => $orderId,
            'endpoint'        => $endpoint,
            'count'           => $count,
            'cost_cached'     => $provider->cost_per_call * $count,
        ]);
    }
}
