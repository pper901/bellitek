<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\ActiveWebSocketConnection;
use App\Models\WebSocketEvent;
use App\Services\SecurityTelemetryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebSocketTelemetryController extends Controller
{
    public function event(
        Request $request,
        SecurityTelemetryService $securityTelemetry
    ): JsonResponse
    {
        $validated = $request->validate([
            'event' => [
                'required',
                'string',
                'in:connected,disconnected,rejected',
            ],

            'connection_id' => [
                'required',
                'string',
                'max:255',
            ],

            'user_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'role' => [
                'nullable',
                'string',
                'in:student,lecturer',
            ],

            'classroom_id' => [
                'nullable',
                'string',
                'max:255',
            ],

            'ip_address' => [
                'nullable',
                'ip',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:255',
            ],

            'occurred_at' => [
                'nullable',
                'date',
            ],
        ]);

        $event = $validated['event'];

        /*
        |--------------------------------------------------------------------------
        | CONNECTED
        |--------------------------------------------------------------------------
        */

        if ($event === 'connected') {

            ActiveWebSocketConnection::updateOrCreate(
                [
                    'connection_id' =>
                        $validated['connection_id'],
                ],
                [
                    'user_id' =>
                        $validated['user_id'] ?? null,

                    'role' =>
                        $validated['role'] ?? null,

                    'classroom_id' =>
                        $validated['classroom_id'] ?? null,

                    'ip_address' =>
                        $validated['ip_address'] ?? null,

                    'connected_at' =>
                        $validated['occurred_at']
                        ?? now(),
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DISCONNECTED
        |--------------------------------------------------------------------------
        */

        elseif ($event === 'disconnected') {

            ActiveWebSocketConnection::where(
                'connection_id',
                $validated['connection_id']
            )->delete();
        }

        /*
        |--------------------------------------------------------------------------
        | REJECTED
        |--------------------------------------------------------------------------
        */

        elseif ($event === 'rejected') {
            $securityTelemetry->recordWebSocket(
                    event: 'websocket_rejected',

                    connectionId:
                        $validated['connection_id'],

                    userId:
                        $validated['user_id'] ?? null,

                    role:
                        $validated['role'] ?? null,

                    classroomId:
                        $validated['classroom_id'] ?? null,

                    ipAddress:
                        $validated['ip_address'] ?? null,

                    reason:
                        $validated['reason'] ?? null,
                );
        }

        /*
        |--------------------------------------------------------------------------
        | EVENT HISTORY
        |--------------------------------------------------------------------------
        */

        WebSocketEvent::create([
            'event' =>
                $event,

            'connection_id' =>
                $validated['connection_id'],

            'user_id' =>
                $validated['user_id'] ?? null,

            'role' =>
                $validated['role'] ?? null,

            'classroom_id' =>
                $validated['classroom_id'] ?? null,

            'ip_address' =>
                $validated['ip_address'] ?? null,

            'reason' =>
                $validated['reason'] ?? null,

            'occurred_at' =>
                $validated['occurred_at']
                ?? now(),
        ]);

        return response()->json([
            'success' => true,
            'event' => $event,
        ]);
    }
}