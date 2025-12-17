<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmploymentLetterRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employment_letter_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('request_type')->default('employment_letter'); // employment_letter, experience_letter, etc.
            $table->text('purpose')->nullable(); // Purpose of the letter
            $table->text('additional_notes')->nullable(); // Any additional notes from employee
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable(); // Reason for rejection if applicable
            $table->unsignedBigInteger('approved_by')->nullable(); // HR who approved/rejected
            $table->timestamp('approved_at')->nullable();
            $table->longText('letter_content')->nullable(); // Generated letter content
            $table->string('letter_file_path')->nullable(); // Path to generated PDF file
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['employee_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employment_letter_requests');
    }
}
