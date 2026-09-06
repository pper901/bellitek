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
        Schema::create('classroom_resources', function (Blueprint $table) {
            $table->id();
            
            // Classroom Identifier (matches your string/uuid classroom ID)
            $table->string('classroom_uuid')->index();
            
            // Foreign key referencing the uploader (Lecturer/User)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            
            // File Metadata
            $table->string('file_name');
            $table->string('uploadcare_uuid')->unique();
            $table->text('file_url');
            $table->string('mime_type')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_resources');
    }
};