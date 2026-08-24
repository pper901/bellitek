<?php

namespace App\Services;

use App\Models\ErrorTelemetry;
use Illuminate\Http\Request;
use Throwable;

class ErrorTelemetryService
{
    /**
     * Record a Laravel exception.
     */
    public function recordException(
        Throwable $exception,
        ?Request $request = null
    ): ErrorTelemetry {

        return ErrorTelemetry::create([

            'source' =>
                'laravel',

            'level' =>
                $this->resolveLevel($exception),

            'type' =>
                get_class($exception),

            'message' =>
                mb_substr(
                    $exception->getMessage(),
                    0,
                    1000
                ),

            'method' =>
                $request?->method(),

            'url' =>
                $request?->fullUrl(),

            'route' =>
                $request?->route()?->getName(),

            'user_id' =>
                $request?->user()?->id,

            'ip_address' =>
                $request?->ip(),

            'file' =>
                $exception->getFile(),

            'line' =>
                $exception->getLine(),

            'trace' =>
                $exception->getTraceAsString(),

            'context' =>
                $this->requestContext($request),

            'occurred_at' =>
                now(),
        ]);
    }

    /**
     * Record a generic telemetry error.
     */
    public function record(
        string $source,
        string $message,
        string $level = 'error',
        array $context = []
    ): ErrorTelemetry {

        return ErrorTelemetry::create([

            'source' =>
                $source,

            'level' =>
                $level,

            'message' =>
                mb_substr(
                    $message,
                    0,
                    1000
                ),

            'context' =>
                $context,

            'occurred_at' =>
                now(),
        ]);
    }

    /**
     * Determine severity.
     */
    private function resolveLevel(
        Throwable $exception
    ): string {

        if (
            $exception instanceof \Error
            || $exception instanceof \ErrorException
        ) {
            return 'critical';
        }

        return 'error';
    }

    /**
     * Capture useful request information.
     */
    private function requestContext(
        ?Request $request
    ): array {

        if (!$request) {
            return [];
        }

        return [
            'query' =>
                $request->query(),

            'has_session' =>
                $request->hasSession(),

            'user_agent' =>
                $request->userAgent(),
        ];
    }
}