<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Increase the length of the delivery_method column.
     */
    public function up(): void
    {
        // Check if the column exists before trying to modify it
        if (Schema::hasColumn('repairs', 'delivery_method')) {
            Schema::table('repairs', function (Blueprint $table) {
                // Change the string length to 20, which is more than enough for "shipbubble" (10 chars)
                $table->string('delivery_method', 20)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     * This will not automatically reverse the column size change safely, 
     * but we provide a rollback definition for completeness.
     */
    public function down(): void
    {
        if (Schema::hasColumn('repairs', 'delivery_method')) {
            Schema::table('repairs', function (Blueprint $table) {
                // Revert to a smaller size if needed, but be aware this could fail 
                // if there is existing data longer than the reverted size.
                $table->string('delivery_method', 10)->change(); 
            });
        }
    }
};