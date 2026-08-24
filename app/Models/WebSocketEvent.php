<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebSocketEvent extends Model
{

    protected $table = 'websocket_events';
    protected $fillable = [
        'event',
        'connection_id',
        'user_id',
        'role',
        'classroom_id',
        'ip_address',
        'reason',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    /**
     * The Laravel user associated with this event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}