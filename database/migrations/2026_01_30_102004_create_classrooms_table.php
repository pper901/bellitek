<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();

            // Lecturer who owns the class
            $table->foreignId('lecturer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Public identifiers
            $table->string('title');
            $table->text('description')->nullable();
            $table->uuid('uuid')->unique();

            // Runtime state
            $table->boolean('is_active')->default(false);

            // Optional: track websocket status later
            // $table->string('websocket_node')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classrooms');
    }
};
