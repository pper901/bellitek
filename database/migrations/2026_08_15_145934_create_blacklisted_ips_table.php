<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blacklisted_ips', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | IP Address
            |--------------------------------------------------------------------------
            */

            $table->ipAddress('ip_address')
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Blacklist Status
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_active')
                ->default(true)
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Reason
            |--------------------------------------------------------------------------
            */

            $table->string('reason', 255)
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Expiration
            |--------------------------------------------------------------------------
            |
            | NULL = permanent blacklist.
            |
            */

            $table->timestamp('expires_at')
                ->nullable()
                ->index();

            /*
            |--------------------------------------------------------------------------
            | Administrator
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('created_by')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index([
                'is_active',
                'expires_at',
            ]);

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blacklisted_ips');
    }
};