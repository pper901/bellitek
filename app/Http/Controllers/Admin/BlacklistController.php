<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlacklistedIp;
use App\Services\BlacklistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BlacklistController extends Controller
{
    /**
     * Display the blacklist administration page.
     */
    public function index()
    {
        return view('admin.security.blacklist');
    }

    /**
     * Return the current blacklist.
     */
    public function list(): JsonResponse
    {
        $blacklistedIps = BlacklistedIp::query()
            ->with('creator:id,name,email')
            ->latest()
            ->get();

        return response()->json([
            'blacklisted_ips' => $blacklistedIps,
        ]);
    }

    /**
     * Add an IP address to the blacklist.
     */
    public function store(
        Request $request,
        BlacklistService $blacklistService
    ): JsonResponse {

        $validated = $request->validate([
            'ip_address' => [
                'required',
                'ip',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:255',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate active entries
        |--------------------------------------------------------------------------
        */

        $existing = BlacklistedIp::query()
            ->where(
                'ip_address',
                $validated['ip_address']
            )
            ->where('is_active', true)
            ->first();

        if ($existing) {

            return response()->json([
                'message' =>
                    'This IP address is already blacklisted.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Create blacklist entry
        |--------------------------------------------------------------------------
        */

        $blacklist = $blacklistService->blacklist(
            ip: $validated['ip_address'],

            reason:
                $validated['reason'] ?? null,

            createdBy:
                $request->user()?->id,

            expiresAt:
                $validated['expires_at'] ?? null,
        );

        return response()->json([
            'success' => true,

            'message' =>
                'IP address has been blacklisted.',

            'blacklist' =>
                $blacklist->load(
                    'creator:id,name,email'
                ),
        ], 201);
    }

    /**
     * Disable a blacklist entry.
     */
    public function deactivate(
        BlacklistedIp $blacklistedIp,
        BlacklistService $blacklistService
    ): JsonResponse {

        $blacklistService->remove(
            $blacklistedIp->ip_address
        );

        return response()->json([
            'success' => true,

            'message' =>
                'IP address has been removed from the active blacklist.',
        ]);
    }

    /**
     * Permanently delete a blacklist entry.
     */
    public function destroy(
        BlacklistedIp $blacklistedIp,
        BlacklistService $blacklistService
    ): JsonResponse {

        $blacklistService->delete(
            $blacklistedIp->ip_address
        );

        return response()->json([
            'success' => true,

            'message' =>
                'Blacklist entry deleted.',
        ]);
    }

    /**
     * Reactivate a previously disabled blacklist entry.
     */
    public function activate(
        BlacklistedIp $blacklistedIp,
        BlacklistService $blacklistService
    ): JsonResponse {

        $blacklistedIp->update([
            'is_active' => true,
        ]);

        $blacklistService->refreshCache();

        return response()->json([
            'success' => true,

            'message' =>
                'IP address has been reactivated.',
        ]);
    }

    public function sync(): JsonResponse
    {
        return response()->json([
            'ips' => BlacklistedIp::query()
                ->where('is_active', true)
                ->pluck('ip_address')
                ->values(),
        ]);
    }
}