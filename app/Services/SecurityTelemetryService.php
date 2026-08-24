<?php

namespace App\Services;

use App\Models\SecurityTelemetry;
use Illuminate\Http\Request;

class SecurityTelemetryService
{
    /**
     * Record a Laravel security event.
     */
    public function record(
        string $event,
        ?int $userId = null,
        ?string $ipAddress = null,
        ?string $reason = null,
        array $context = [],
        ?Request $request = null
    ): SecurityTelemetry {

        $request ??= request();

        return SecurityTelemetry::create([
            'event' => $event,

            'source' => 'laravel',

            'user_id' => $userId,

            'ip_address' =>
                $ipAddress ?? $request?->ip(),

            'reason' => $reason,

            'context' =>
                $context ?: null,

            'occurred_at' => now(),
        ]);
    }

    /**
     * Record a Java/WebSocket security event.
     */
    public function recordWebSocket(
        string $event,
        ?string $connectionId = null,
        ?int $userId = null,
        ?string $role = null,
        ?string $classroomId = null,
        ?string $ipAddress = null,
        ?string $reason = null,
        array $context = [],
    ): SecurityTelemetry {

        return SecurityTelemetry::create([
            'event' => $event,

            'source' => 'java',

            'connection_id' =>
                $connectionId,

            'user_id' =>
                $userId,

            'role' =>
                $role,

            'classroom_id' =>
                $classroomId,

            'ip_address' =>
                $ipAddress,

            'reason' =>
                $reason,

            'context' =>
                $context ?: null,

            'occurred_at' =>
                now(),
        ]);
    }
}