<?php

namespace App\Http\Controllers\Admin;

use App\Models\ErrorTelemetry;
use App\Http\Controllers\Controller;
use App\Models\ActiveWebSocketConnection;
use App\Models\WebSocketEvent;
use App\Services\GeneralClassServerService;
use App\Services\SystemTelemetryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminTelemetryController extends Controller
{
    /**
     * Display the telemetry dashboard.
     */
    public function index()
    {
        return view('admin.telemetry.index');
    }

    /*
    |--------------------------------------------------------------------------
    | JAVA SERVER
    |--------------------------------------------------------------------------
    */

    /**
     * Check whether the Java server is operational.
     */
    public function javaHealth(
        GeneralClassServerService $server
    ): JsonResponse {

        return response()->json(
            $server->health()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | WEBSOCKET CONFIGURATION
    |--------------------------------------------------------------------------
    */

    /**
     * Return the WebSocket health-check URL.
     */
    public function websocketConfig(): JsonResponse
    {
        return response()->json([
            'websocket_url' =>
                rtrim(
                    config(
                        'services.generalclass.websocket_url'
                    ),
                    '/'
                ) . '/ws/health',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | WEBSOCKET STATISTICS
    |--------------------------------------------------------------------------
    */

    /**
     * Get current WebSocket statistics.
     */
    public function websocketStats(): JsonResponse
    {
        $activeConnections =
            ActiveWebSocketConnection::query();

        return response()->json([
            'active_connections' =>
                $activeConnections->count(),

            'students' =>
                (clone $activeConnections)
                    ->where('role', 'student')
                    ->count(),

            'lecturers' =>
                (clone $activeConnections)
                    ->where('role', 'lecturer')
                    ->count(),

            'active_classrooms' =>
                (clone $activeConnections)
                    ->whereNotNull('classroom_id')
                    ->distinct('classroom_id')
                    ->count('classroom_id'),

            'rejected_today' =>
                WebSocketEvent::query()
                    ->where(
                        'event',
                        'rejected'
                    )
                    ->whereDate(
                        'occurred_at',
                        today()
                    )
                    ->count(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVE CONNECTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get currently active WebSocket connections.
     */
    public function activeConnections(): JsonResponse
    {
        $connections =
            ActiveWebSocketConnection::query()
                ->with('user:id,name,email')
                ->latest('connected_at')
                ->get();

        return response()->json([
            'connections' => $connections,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | WEBSOCKET EVENTS
    |--------------------------------------------------------------------------
    */

    /**
     * Get recent WebSocket events.
     */
    public function websocketEvents(): JsonResponse
    {
        $events =
            WebSocketEvent::query()
                ->with('user:id,name,email')
                ->latest('occurred_at')
                ->limit(50)
                ->get();

        return response()->json([
            'events' => $events,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | JAVA → LARAVEL TELEMETRY
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | COMPLETE SYSTEM TELEMETRY
    |--------------------------------------------------------------------------
    */

    /**
     * Return complete telemetry information
     * for the admin dashboard.
     *
     * Includes:
     *
     * - Laravel
     * - Host CPU
     * - Host RAM
     * - Docker Laravel CPU/RAM
     * - Docker Java CPU/RAM
     * - Active connections
     * - Students
     * - Lecturers
     * - Classrooms
     * - Connection rate
     * - Errors
     */
    public function systemTelemetry(
        SystemTelemetryService $systemTelemetry
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | ACTIVE CONNECTIONS
        |--------------------------------------------------------------------------
        */

        $activeConnections =
            ActiveWebSocketConnection::query();

        $students =
            (clone $activeConnections)
                ->where('role', 'student')
                ->count();

        $lecturers =
            (clone $activeConnections)
                ->where('role', 'lecturer')
                ->count();

        $totalConnections =
            (clone $activeConnections)
                ->count();

        $activeClassrooms =
            (clone $activeConnections)
                ->whereNotNull('classroom_id')
                ->distinct('classroom_id')
                ->count('classroom_id');

        /*
        |--------------------------------------------------------------------------
        | CONNECTION RATE
        |--------------------------------------------------------------------------
        |
        | Number of successful connections during
        | the previous 60 seconds.
        |
        */

        $connectionsLastMinute =
            WebSocketEvent::query()
                ->where(
                    'event',
                    'connected'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    now()->subMinute()
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | ERRORS
        |--------------------------------------------------------------------------
        |
        | For now "errors" means rejected WebSocket
        | connection attempts.
        |
        */

        $errorsLastHour =
            WebSocketEvent::query()
                ->where(
                    'event',
                    'rejected'
                )
                ->where(
                    'occurred_at',
                    '>=',
                    now()->subHour()
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | SYSTEM METRICS
        |--------------------------------------------------------------------------
        */

        $metrics =
            $systemTelemetry->metrics();

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | LARAVEL
            |--------------------------------------------------------------------------
            */

            'laravel' => [
                'status' =>
                    'operational',

                'environment' =>
                    app()->environment(),
            ],

            /*
            |--------------------------------------------------------------------------
            | HOST + DOCKER
            |--------------------------------------------------------------------------
            */

            'system' =>
                $metrics,

            /*
            |--------------------------------------------------------------------------
            | WEBSOCKET CONNECTIONS
            |--------------------------------------------------------------------------
            */

            'connections' => [

                'total' =>
                    $totalConnections,

                'students' =>
                    $students,

                'lecturers' =>
                    $lecturers,

                'active_classrooms' =>
                    $activeClassrooms,

                'rate_per_minute' =>
                    $connectionsLastMinute,
            ],

            /*
            |--------------------------------------------------------------------------
            | ERRORS
            |--------------------------------------------------------------------------
            */

            'errors' => [

                'last_hour' =>
                    $errorsLastHour,
            ],
        ]);
    }

    /**
     * Get recent application errors.
     */
    public function errors(): JsonResponse
    {
        $errors = ErrorTelemetry::query()
            ->with('user:id,name,email')
            ->latest('occurred_at')
            ->limit(50)
            ->get();

        return response()->json([
            'errors' => $errors,
        ]);
    }

    /**
     * Get error telemetry statistics.
     */
    public function errorStats(): JsonResponse
    {
        return response()->json([

            'last_hour' =>
                ErrorTelemetry::query()
                    ->where(
                        'occurred_at',
                        '>=',
                        now()->subHour()
                    )
                    ->count(),

            'today' =>
                ErrorTelemetry::query()
                    ->whereDate(
                        'occurred_at',
                        today()
                    )
                    ->count(),

            'critical' =>
                ErrorTelemetry::query()
                    ->where('level', 'critical')
                    ->where(
                        'occurred_at',
                        '>=',
                        now()->subHour()
                    )
                    ->count(),

            'errors' =>
                ErrorTelemetry::query()
                    ->where('level', 'error')
                    ->where(
                        'occurred_at',
                        '>=',
                        now()->subHour()
                    )
                    ->count(),

            'warnings' =>
                ErrorTelemetry::query()
                    ->where('level', 'warning')
                    ->where(
                        'occurred_at',
                        '>=',
                        now()->subHour()
                    )
                    ->count(),
        ]);
    }
}