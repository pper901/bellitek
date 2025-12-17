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
            $table->string('status', 50)->nullable()->change(); // nullable temporarily
        });

        // Step 2: Drop old check constraint if exists
        DB::statement('ALTER TABLE repairs DROP CONSTRAINT IF EXISTS repairs_status_check;');

        // Step 3: Add new check constraint
        DB::statement("
            ALTER TABLE repairs
            ADD CONSTRAINT repairs_status_check
            CHECK (status IN ('pending', 'received', 'diagnosing', 'repairing', 'completed', 'ready_for_pickup'));
        ");

        // Step 4: Set default and NOT NULL
        DB::statement("
            ALTER TABLE repairs
            ALTER COLUMN status SET DEFAULT 'pending',
            ALTER COLUMN status SET NOT NULL;
        ");
    }

    public function down(): void
    {
        // Revert type to 25 chars
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('status', 25)->change();
        });

        // Drop the check constraint
        DB::statement('ALTER TABLE repairs DROP CONSTRAINT IF EXISTS repairs_status_check;');

        // Optionally reset default
        DB::statement("
            ALTER TABLE repairs
            ALTER COLUMN status DROP DEFAULT;
        ");
    }
};
