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
        Schema::create('import_error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('import_id');
            $table->string('import_type')->default('employee');
            $table->unsignedBigInteger('user_id');
            $table->integer('row_number');
            $table->string('error_type')->nullable(); // validation_error, import_error, lookup_error, etc.
            $table->string('field_name')->nullable();
            $table->text('error_message');
            $table->text('problematic_value')->nullable();
            $table->json('row_data')->nullable(); // Store the full row data for debugging
            $table->timestamp('occurred_at');
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['import_id', 'import_type']);
            $table->index(['user_id']);
            $table->index(['error_type']);
            $table->index(['occurred_at']);
            
            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_error_logs');
    }
};
