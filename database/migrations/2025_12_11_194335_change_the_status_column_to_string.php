<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table and column exist before attempting to modify
        if (Schema::hasTable('repairs') && Schema::hasColumn('repairs', 'status')) {
            Schema::table('repairs', function (Blueprint $table) {
                // Change the status column to VARCHAR with a length of 50.
                // We use change() to modify an existing column definition.
                $table->string('status', 50)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the status column size back to a smaller, safer default (e.g., 25)
        // Adjust this number if you know the original size.
        if (Schema::hasTable('repairs') && Schema::hasColumn('repairs', 'status')) {
            Schema::table('repairs', function (Blueprint $table) {
                $table->string('status', 25)->change();
            });
        }
    }
};