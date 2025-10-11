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
        Schema::create('import_progress', function (Blueprint $table) {
            $table->id();
            $table->string('import_id')->unique(); // Unique identifier for each import session
            $table->string('import_type'); // employee, student, asset, etc.
            $table->integer('user_id'); // User who initiated the import
            $table->string('file_name'); // Original file name
            $table->integer('total_rows')->default(0); // Total rows to process
            $table->integer('processed_rows')->default(0); // Rows processed so far
            $table->integer('imported_count')->default(0); // Successfully imported
            $table->integer('skipped_count')->default(0); // Skipped rows
            $table->integer('error_count')->default(0); // Rows with errors
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('current_message')->nullable(); // Current status message
            $table->json('errors')->nullable(); // Store errors as JSON
            $table->integer('current_row')->default(0); // Current row being processed
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['import_id', 'status']);
            $table->index(['user_id', 'import_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_progress');
    }
};
