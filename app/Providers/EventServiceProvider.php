<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use App\Services\SecurityTelemetryService;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Login::class => [],
        Failed::class => [],
        Logout::class => [],
        Lockout::class => [],
        RequestHandled::class => [],
    ];

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Successful Login
        |--------------------------------------------------------------------------
        */

        Event::listen(Login::class, function (Login $event) {

            app(SecurityTelemetryService::class)->record(
                event: 'login_success',

                userId:
                    $event->user?->id,

                ipAddress:
                    request()->ip(),

                context: [
                    'guard' =>
                        $event->guard,

                    'remember' =>
                        $event->remember,

                    'user_agent' =>
                        request()->userAgent(),

                    'url' =>
                        request()->fullUrl(),
                ],
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Failed Login
        |--------------------------------------------------------------------------
        */

        Event::listen(Failed::class, function (Failed $event) {

            app(SecurityTelemetryService::class)->record(
                event: 'login_failed',

                userId:
                    $event->user?->id,

                ipAddress:
                    request()->ip(),

                context: [
                    'guard' =>
                        $event->guard,

                    'email' =>
                        $event->credentials['email'] ?? null,

                    'user_agent' =>
                        request()->userAgent(),

                    'url' =>
                        request()->fullUrl(),
                ],
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Event::listen(Logout::class, function (Logout $event) {

            app(SecurityTelemetryService::class)->record(
                event: 'logout',

                userId:
                    $event->user?->id,

                ipAddress:
                    request()->ip(),

                context: [
                    'guard' =>
                        $event->guard,

                    'user_agent' =>
                        request()->userAgent(),
                ],
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Authentication Lockout
        |--------------------------------------------------------------------------
        */

        Event::listen(Lockout::class, function (Lockout $event) {

            app(SecurityTelemetryService::class)->record(
                event: 'account_lockout',

                userId:
                    $event->request->user()?->id,

                ipAddress:
                    $event->request->ip(),

                context: [
                    'email' =>
                        $event->request->input('email'),

                    'user_agent' =>
                        $event->request->userAgent(),
                ],
            );
        });


        /*
        |--------------------------------------------------------------------------
        | Laravel Rate Limit
        |--------------------------------------------------------------------------
        |
        | Laravel's throttle middleware and other rate-limit mechanisms
        | return HTTP 429 when the request exceeds its allowed rate.
        |
        */

        Event::listen(RequestHandled::class, function (
            RequestHandled $event
        ) {

            if ($event->response->getStatusCode() !== 429) {
                return;
            }

            $request = $event->request;

            app(SecurityTelemetryService::class)->record(
                event: 'rate_limit_exceeded',

                userId:
                    $request->user()?->id,

                ipAddress:
                    $request->ip(),

                reason:
                    'Too many requests',

                context: [
                    'method' =>
                        $request->method(),

                    'path' =>
                        $request->path(),

                    'url' =>
                        $request->fullUrl(),

                    'route' =>
                        $request->route()?->getName(),

                    'user_agent' =>
                        $request->userAgent(),

                    'status' =>
                        $event->response->getStatusCode(),

                    'retry_after' =>
                        $event->response->headers->get('Retry-After'),
                ],
            );
        });
    }
}