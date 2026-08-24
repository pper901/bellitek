<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('security_telemetry', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Event
            |--------------------------------------------------------------------------
            */

            $table->string('event', 50);

            /*
            |--------------------------------------------------------------------------
            | Connection
            |--------------------------------------------------------------------------
            */

            $table->string('connection_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('user_id')
                ->nullable();

            $table->string('role', 30)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Classroom
            |--------------------------------------------------------------------------
            */

            $table->string('classroom_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Network
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('ip_address')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Security Decision
            |--------------------------------------------------------------------------
            */

            $table->string('reason', 255)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Context
            |--------------------------------------------------------------------------
            */

            $table->json('context')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamp
            |--------------------------------------------------------------------------
            */

            $table->timestamp('occurred_at')
                ->useCurrent();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('event');

            $table->index('user_id');

            $table->index('ip_address');

            $table->index('classroom_id');

            $table->index('occurred_at');

            $table->index([
                'event',
                'occurred_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_telemetry');
    }
};