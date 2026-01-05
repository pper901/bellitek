
<?php

use App\Http\Controllers\ShipbubbleWebhookController;

Route::post('/webhooks/shipbubble', [ShipbubbleWebhookController::class, 'handle']);
