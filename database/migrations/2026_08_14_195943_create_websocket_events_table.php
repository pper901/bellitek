<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('websocket_events', function (Blueprint $table) {
            $table->id();

            // connected / disconnected / rejected
            $table->string('event', 30)->index();

            // Java-generated connection identifier
            $table->string('connection_id')->index();

            // Laravel user ID
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // lecturer / student
            $table->string('role', 30)->nullable();

            // Java classroom UUID
            $table->string('classroom_id')->nullable()->index();

            // Client IP
            $table->ipAddress('ip_address')->nullable();

            // Optional reason for rejected/disconnected events
            $table->string('reason')->nullable();

            // When Java says the event occurred
            $table->timestamp('occurred_at');

            $table->timestamps();

            $table->index(['event', 'occurred_at']);
            $table->index(['user_id', 'occurred_at']);
            $table->index(['connection_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('websocket_events');
    }
};