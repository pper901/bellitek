<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('repair_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repair_id')->constrained()->cascadeOnDelete();

            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();

            // ShipBubble
            $table->string('shipbubble_order_id')->nullable();
            $table->string('tracking_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_bookings');
    }
};
