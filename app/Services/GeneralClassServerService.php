<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeneralClassServerService
{
    public function health(): array
    {
        $url = rtrim(config('services.generalclass.url'), '/') . '/api/health';

        // Log::info('Checking Java server health', [
        //     'url' => $url,
        // ]);

        try {

            $response = Http::timeout(5)
                ->acceptJson()
                ->get($url);

            // Log::info('Java server health response', [
            //     'url' => $url,
            //     'status' => $response->status(),
            //     'body' => $response->body(),
            // ]);

            if ($response->successful()) {
                return [
                    'connected' => true,
                    'status' => 'operational',
                    'message' => 'Connected to Java Microservice',
                    'http_status' => $response->status(),
                ];
            }

            Log::error('Java server returned an error', [
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'connected' => false,
                'status' => 'down',
                'message' => 'Java server returned an error',
                'http_status' => $response->status(),
            ];

        } catch (\Throwable $e) {

            Log::error('Java server health check failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return [
                'connected' => false,
                'status' => 'down',
                'message' => 'Unable to connect to Java server',
                'http_status' => null,
            ];
        }
    }
}