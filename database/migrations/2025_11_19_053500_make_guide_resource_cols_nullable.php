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
        // Must use Schema::table() when modifying an existing table
        Schema::table('guide_resources', function (Blueprint $table) {
            
            // Allow 'cause' to be null (it was previously a string/varchar(255))
            // We use text() here to ensure we handle any previous string length issues too.
            $table->text('cause')->nullable()->change(); 
            
            // Allow 'solution' to be null
            $table->text('solution')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to NOT NULL (default) and string type on rollback
        Schema::table('guide_resources', function (Blueprint $table) {
            $table->string('cause')->nullable(false)->change(); // Explicitly NOT NULL
            $table->string('solution')->nullable(false)->change(); // Explicitly NOT NULL
        });
    }
};