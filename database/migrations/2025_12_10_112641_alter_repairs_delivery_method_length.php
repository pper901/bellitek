<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Ensure the column exists
        if (!Schema::hasColumn('repairs', 'status')) {
            return;
        }

        // 2. Change column type first (no constraints here)
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('status', 255)->nullable()->change();
        });

        // 3. Drop any existing constraint safely
        DB::statement("
            ALTER TABLE repairs
            DROP CONSTRAINT IF EXISTS repairs_status_check;
        ");

        // 4. Add new constraint separately
        DB::statement("
            ALTER TABLE repairs
            ADD CONSTRAINT repairs_status_check
            CHECK (
                status IN ('pending','received','diagnosing','repairing','completed','ready_for_pickup')
            );
        ");

        // 5. Set NOT NULL separately
        DB::statement("
            ALTER TABLE repairs
            ALTER COLUMN status SET NOT NULL;
        ");

        // 6. Set DEFAULT separately
        DB::statement("
            ALTER TABLE repairs
            ALTER COLUMN status SET DEFAULT 'pending';
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE repairs
            DROP CONSTRAINT IF EXISTS repairs_status_check;
        ");

        Schema::table('repairs', function (Blueprint $table) {
            $table->string('status', 50)->nullable()->change();
        });

        DB::statement("
            ALTER TABLE repairs
            ALTER COLUMN status DROP DEFAULT;
        ");
    }
};
