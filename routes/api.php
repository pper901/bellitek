
<?php

use App\Http\Controllers\ShipbubbleWebhookController;
use App\Http\Controllers\Internal\WebSocketTelemetryController;
use App\Http\Controllers\Admin\BlacklistController;

Route::post('/webhooks/shipbubble', [ShipbubbleWebhookController::class, 'handle']);
Route::middleware('internal.service') ->post( '/internal/websocket/events',[WebSocketTelemetryController::class, 'event']);
Route::middleware('internal.service') ->get(
    '/internal/security/blacklist',
    [BlacklistController::class, 'sync']
);
