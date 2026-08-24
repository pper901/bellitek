<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Services\ErrorTelemetryService;

//C:\\Users\\USER\\Documents\\Bellifix\\bellifix
///var/www/app
return Application::configure(basePath: 'C:\\Users\\USER\\Documents\\Bellifix\\bellifix')
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
	$middleware->trustProxies(at: '*');
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'lecturer' => \App\Http\Middleware\IsLecturer::class,
            'internal.service' => \App\Http\Middleware\InternalService::class,
            'blacklist' => App\Http\Middleware\CheckBlacklistedIp::class,
        ]);
    })->withExceptions(function (Exceptions $exceptions) {

        $exceptions->report(function (
            Throwable $exception
        ) {

            /*
            |--------------------------------------------------------------------------
            | Avoid recursive telemetry failures
            |--------------------------------------------------------------------------
            */

            try {

                app(ErrorTelemetryService::class)
                    ->recordException(
                        $exception,
                        request()
                    );

            } catch (Throwable $telemetryException) {

                /*
                |--------------------------------------------------------------------------
                | IMPORTANT
                |--------------------------------------------------------------------------
                |
                | If telemetry itself fails, do not allow it to
                | break Laravel's normal exception handling.
                |
                */

                report($telemetryException);
            }
        });

    })
    ->create();
