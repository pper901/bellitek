<?php

namespace App\Listeners;

use App\Services\SecurityTelemetryService;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class RecordSecurityTelemetry
{
    public function __construct(
        protected SecurityTelemetryService $securityTelemetry
    ) {
    }

    /**
     * Successful login.
     */
    public function handleLogin(Login $event): void
    {
        $this->securityTelemetry->record(
            'login_success',
            $event->user?->id
        );
    }

    /**
     * Failed login.
     */
    public function handleFailed(Failed $event): void
    {
        $this->securityTelemetry->record(
            'login_failed',
            null,
            'Invalid credentials',
            [
                'identifier' =>
                    $event->credentials['email']
                    ?? null,
            ]
        );
    }

    /**
     * Logout.
     */
    public function handleLogout(Logout $event): void
    {
        $this->securityTelemetry->record(
            'logout',
            $event->user?->id
        );
    }
}