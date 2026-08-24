<?php
namespace App\Http\Middleware;

use App\Services\BlacklistService;
use App\Services\SecurityTelemetryService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBlacklistedIp
{
    public function handle(Request $request, Closure $next): Response
    {
        // Prevent infinite execution loops during exception handling/redirection
        if ($request->attributes->get('is_blacklist_checked')) {
            return $next($request);
        }

        $request->attributes->set('is_blacklist_checked', true);

        $blacklist = app(BlacklistService::class);
        $securityTelemetry = app(SecurityTelemetryService::class);

        $ip = $request->ip();

        if (!$ip) {
            return $next($request);
        }

        if ($blacklist->isBlacklisted($ip)) {
            $securityTelemetry->record(
                event: 'blacklisted_ip_blocked',
                userId: $request->user()?->id,
                ipAddress: $ip,
                reason: 'IP address is blacklisted',
                context: [
                    'method' => $request->method(),
                    'path' => $request->path(),
                    'url' => $request->fullUrl(),
                    'user_agent' => $request->userAgent(),
                ],
                request: $request
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Access denied.',
                ], 403);
            }

            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}