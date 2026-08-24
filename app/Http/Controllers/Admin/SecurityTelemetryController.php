<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityTelemetry;
use Illuminate\Http\JsonResponse;

class SecurityTelemetryController extends Controller
{
    /**
     * Security telemetry dashboard.
     */
    public function index()
    {
        return view(
            'admin.telemetry.security'
        );
    }

    /**
     * Security statistics.
     */
    public function stats(): JsonResponse
    {
        $now = now();

        $lastHour = $now->copy()->subHour();

        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | General
            |--------------------------------------------------------------------------
            */

            'total' =>
                SecurityTelemetry::count(),

            'last_hour' =>
                SecurityTelemetry::where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )->count(),


            /*
            |--------------------------------------------------------------------------
            | Laravel Authentication
            |--------------------------------------------------------------------------
            */

            'login_successes' =>
                SecurityTelemetry::where(
                    'event',
                    'login_success'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),

            'login_failures' =>
                SecurityTelemetry::where(
                    'event',
                    'login_failed'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),

            'logouts' =>
                SecurityTelemetry::where(
                    'event',
                    'logout'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),

            'account_lockouts' =>
                SecurityTelemetry::where(
                    'event',
                    'account_lockout'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Laravel Rate Limiting
            |--------------------------------------------------------------------------
            */

            'rate_limited' =>
                SecurityTelemetry::where(
                    'event',
                    'rate_limit_exceeded'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | HTTP Security
            |--------------------------------------------------------------------------
            */

            'unauthorized' =>
                SecurityTelemetry::where(
                    'event',
                    'unauthorized'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),

            'forbidden' =>
                SecurityTelemetry::where(
                    'event',
                    'forbidden'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Java / WebSocket Security
            |--------------------------------------------------------------------------
            */

            'websocket_rejections' =>
                SecurityTelemetry::where(
                    'event',
                    'websocket_rejected'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),


            /*
            |--------------------------------------------------------------------------
            | Source Breakdown
            |--------------------------------------------------------------------------
            */

            'laravel_events' =>
                SecurityTelemetry::where(
                    'source',
                    'laravel'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),

            'java_events' =>
                SecurityTelemetry::where(
                    'source',
                    'java'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    $lastHour
                )
                ->count(),
        ]);
    }

    /**
     * Recent security events.
     */
    public function events(): JsonResponse
    {
        $events = SecurityTelemetry::query()
            ->with('user:id,name,email')
            ->latest('occurred_at')
            ->limit(100)
            ->get();

        return response()->json([
            'events' => $events,
        ]);
    }
}