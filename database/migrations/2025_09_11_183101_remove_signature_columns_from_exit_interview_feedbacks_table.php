<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSignatureColumnsFromExitInterviewFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exit_interview_feedbacks', function (Blueprint $table) {
            $table->dropColumn([
                'employee_signature',
                'employee_signature_date',
                'hr_signature',
                'hr_signature_date'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exit_interview_feedbacks', function (Blueprint $table) {
            $table->string('employee_signature')->nullable();
            $table->timestamp('employee_signature_date')->nullable();
            $table->string('hr_signature')->nullable();
            $table->timestamp('hr_signature_date')->nullable();
        });
    }
}
