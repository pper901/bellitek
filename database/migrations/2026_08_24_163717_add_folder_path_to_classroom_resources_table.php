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
        Schema::table('classroom_resources', function (Blueprint $table) {
            // Add folder_path after file_name column with default '/' for root directory
            $table->string('folder_path')->default('/')->after('file_name')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classroom_resources', function (Blueprint $table) {
            $table->dropColumn('folder_path');
        });
    }
};