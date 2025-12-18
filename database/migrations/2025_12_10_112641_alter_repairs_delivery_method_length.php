<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure the table exists
        if (!Schema::hasTable('repairs')) {
            return;
        }

        // 2. Drop the old constraint manually if it exists (Postgres specific)
        // This prevents the "syntax error at or near check" when changing types
        if (config('database.default') === 'pgsql') {
            DB::statement('ALTER TABLE repairs DROP CONSTRAINT IF EXISTS repairs_status_check');
        }

        // 3. Change the column to a standard string
        // We remove the 'enum' logic here and just use a standard VARCHAR(255)
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('status', 255)->default('pending')->change();
        });
        
        // 4. Ensure no null values exist before setting NOT NULL
        DB::table('repairs')->whereNull('status')->update(['status' => 'pending']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('status', 50)->default('Pending')->change();
        });
    }
};