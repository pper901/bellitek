<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityTelemetry extends Model
{
    protected $table = 'security_telemetry';

    protected $fillable = [
        'event',
        'source',
        'connection_id',
        'user_id',
        'role',
        'classroom_id',
        'ip_address',
        'reason',
        'context',
        'occurred_at',
    ];

    protected $casts = [
        'context' => 'array',
        'occurred_at' => 'datetime',
    ];

    /**
     * User associated with the security event.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}