<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentTransferCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_transfer_cases', function (Blueprint $table) {
            $table->id();
            $table->string('application_id')->nullable();
            $table->date('request_date')->nullable();
            $table->date('transfer_wef')->nullable();
            $table->date('joining_date')->nullable();
            $table->date('cancellation_date')->nullable();
            $table->date('approved_date')->nullable();
            $table->string('status')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->date('status_updated_at')->nullable();
            $table->text('cancellation_remarks')->nullable();
            $table->text('approval_remarks')->nullable();
            $table->text('remarks')->nullable();

            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states');

            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->unsignedBigInteger('cancelled_by')->nullable();
            $table->foreign('cancelled_by')->references('id')->on('users');

            $table->unsignedBigInteger('from_branch')->nullable();
            $table->foreign('from_branch')->references('id')->on('branches');

            $table->unsignedBigInteger('to_branch')->nullable();
            $table->foreign('to_branch')->references('id')->on('branches');

            $table->unsignedBigInteger('transfer_reason_id')->nullable();
            $table->foreign('transfer_reason_id')->references('id')->on('student_transfer_reasons');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_transfer_cases');
    }
}
