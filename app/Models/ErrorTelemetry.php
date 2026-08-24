<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErrorTelemetry extends Model
{
    protected $table = 'error_telemetry';

    protected $fillable = [
        'source',
        'level',
        'type',
        'message',
        'method',
        'url',
        'route',
        'user_id',
        'ip_address',
        'file',
        'line',
        'trace',
        'context',
        'occurred_at',
    ];

    protected $casts = [
        'context' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
}