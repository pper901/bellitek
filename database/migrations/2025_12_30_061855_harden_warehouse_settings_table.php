<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Drop the table if it exists to clear the "incorrectly formed" errors
        Schema::dropIfExists('warehouse_settings');

        // 2. Recreate it from scratch with the hardened architecture
        Schema::create('warehouse_settings', function (Blueprint $table) {
            $table->id();
            // user_id is now a required part of the foundation
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->string('address_code');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();

            // 3. Define the unique constraint immediately on a fresh table
            $table->unique(['user_id', 'address_code'], 'ws_user_address_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouse_settings');
    }
};
