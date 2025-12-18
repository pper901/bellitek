<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Change the column type
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('status', 255)->nullable()->change(); // temporarily nullable
        });

        // Step 2: Drop old check constraint if exists
        DB::statement('ALTER TABLE repairs DROP CONSTRAINT IF EXISTS repairs_status_check;');

        // Step 3: Add new check constraint
        DB::statement("
            ALTER TABLE repairs
            ADD CONSTRAINT repairs_status_check
            CHECK (status IN ('pending', 'received', 'diagnosing', 'repairing', 'completed', 'ready_for_pickup'));
        ");

        // Step 4: Set NOT NULL and default
        DB::statement("
            ALTER TABLE repairs
            ALTER COLUMN status SET NOT NULL,
            ALTER COLUMN status SET DEFAULT 'pending';
        ");
    }

    public function down(): void
    {
        // Step 1: Drop the check constraint
        DB::statement('ALTER TABLE repairs DROP CONSTRAINT IF EXISTS repairs_status_check;');

        // Step 2: Revert column type
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        // Step 3: Remove default
        DB::statement("
            ALTER TABLE repairs
            ALTER COLUMN status DROP DEFAULT;
        ");
    }
};
