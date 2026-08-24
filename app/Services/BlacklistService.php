<?php

namespace App\Services;

use App\Models\BlacklistedIp;
use Illuminate\Support\Facades\Cache;

class BlacklistService
{
    /**
     * Cache key containing the active blacklist.
     */
    private const CACHE_KEY = 'security:blacklisted_ips';

    /**
     * Cache duration.
     *
     * The cache is explicitly invalidated whenever
     * the blacklist changes, so this is primarily
     * a safety fallback.
     */
    private const CACHE_TTL = 3600;


    /*
    |--------------------------------------------------------------------------
    | Check IP
    |--------------------------------------------------------------------------
    */

    public function isBlacklisted(string $ip): bool
    {
        return in_array(
            $ip,
            $this->getBlacklistedIps(),
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get Cached Blacklist
    |--------------------------------------------------------------------------
    */

    public function getBlacklistedIps(): array
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            function () {

                return BlacklistedIp::query()
                    ->active()
                    ->pluck('ip_address')
                    ->map(
                        fn ($ip) => (string) $ip
                    )
                    ->values()
                    ->all();
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Add IP
    |--------------------------------------------------------------------------
    */

    public function add(
        string $ip,
        ?string $reason = null,
        ?int $createdBy = null,
        $expiresAt = null
    ): BlacklistedIp {

        $blacklist =
            BlacklistedIp::updateOrCreate(
                [
                    'ip_address' => $ip,
                ],
                [
                    'is_active' => true,
                    'reason' => $reason,
                    'created_by' => $createdBy,
                    'expires_at' => $expiresAt,
                ]
            );

        $this->clearCache();

        return $blacklist;
    }


    /*
    |--------------------------------------------------------------------------
    | Remove IP
    |--------------------------------------------------------------------------
    */

    public function remove(string $ip): bool
    {
        $deleted =
            BlacklistedIp::where(
                'ip_address',
                $ip
            )->delete();

        $this->clearCache();

        return $deleted > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Disable IP
    |--------------------------------------------------------------------------
    */

    public function deactivate(string $ip): bool
    {
        $updated =
            BlacklistedIp::where(
                'ip_address',
                $ip
            )->update([
                'is_active' => false,
            ]);

        $this->clearCache();

        return $updated > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Activate IP
    |--------------------------------------------------------------------------
    */

    public function activate(string $ip): bool
    {
        $updated =
            BlacklistedIp::where(
                'ip_address',
                $ip
            )->update([
                'is_active' => true,
            ]);

        $this->clearCache();

        return $updated > 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Clear Cache
    |--------------------------------------------------------------------------
    */

    public function clearCache(): void
    {
        Cache::forget(
            self::CACHE_KEY
        );
    }
}