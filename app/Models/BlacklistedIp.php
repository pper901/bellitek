<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlacklistedIp extends Model
{
    protected $table = 'blacklisted_ips';

    protected $fillable = [
        'ip_address',
        'is_active',
        'reason',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Administrator
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Active Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($query) {

                $query
                    ->whereNull('expires_at')
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            });
    }

    /*
    |--------------------------------------------------------------------------
    | Expired
    |--------------------------------------------------------------------------
    */

    public function isExpired(): bool
    {
        return $this->expires_at !== null
            && $this->expires_at->isPast();
    }

    /*
    |--------------------------------------------------------------------------
    | Currently Active
    |--------------------------------------------------------------------------
    */

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        return !$this->isExpired();
    }
}