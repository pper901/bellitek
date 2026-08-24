<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveWebSocketConnection extends Model
{

    protected $table = 'active_websocket_connections';
    protected $fillable = [
        'connection_id',
        'user_id',
        'role',
        'classroom_id',
        'ip_address',
        'connected_at',
    ];

    protected $casts = [
        'connected_at' => 'datetime',
    ];

    /**
     * The Laravel user associated with this connection.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}