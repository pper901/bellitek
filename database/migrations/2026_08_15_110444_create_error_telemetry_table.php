<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('error_telemetry', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Error Classification
            |--------------------------------------------------------------------------
            */

            $table->string('source', 50);
            // laravel, java, websocket, database, system, etc.

            $table->string('level', 20)->default('error');
            // error, warning, critical

            $table->string('type')->nullable();
            // Exception class / error type

            $table->string('message', 1000);

            /*
            |--------------------------------------------------------------------------
            | Request Context
            |--------------------------------------------------------------------------
            */

            $table->string('method', 10)->nullable();

            $table->text('url')->nullable();

            $table->string('route')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();

            $table->ipAddress('ip_address')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Exception Details
            |--------------------------------------------------------------------------
            */

            $table->string('file')->nullable();

            $table->unsignedInteger('line')->nullable();

            $table->text('trace')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Additional Context
            |--------------------------------------------------------------------------
            */

            $table->json('context')->nullable();

            $table->timestamp('occurred_at')->useCurrent();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('source');

            $table->index('level');

            $table->index('occurred_at');

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_telemetry');
    }
};