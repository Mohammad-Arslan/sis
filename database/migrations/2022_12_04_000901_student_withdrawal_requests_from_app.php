<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StudentWithdrawalRequestsFromApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_withdrawal_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');

            $table->unsignedBigInteger('guardian_id');
            $table->foreign('guardian_id')->references('id')->on('guardians');

            $table->unsignedBigInteger('withdrawal_reason_id');
            $table->foreign('withdrawal_reason_id')->references('id')->on('withdrawal_reasons');

            $table->text('feedback_message')->nullable();

            //$table->unsignedBigInteger('beneficiary_id');
            $table->string('beneficiary_name')->nullable();
            //$table->foreign('beneficiary_id')->references('id')->on('guardians');

            $table->date('last_day_at_school')->nullable();

            $table->string('guardian_cnic_image_front')->nullable();
            $table->string('guardian_cnic_image_back')->nullable();

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
        Schema::dropIfExists('student_withdrawal_requests');
    }
}
