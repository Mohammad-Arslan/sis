<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('order_no');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('withdrawal_reason_id')->nullable();
            $table->foreign('withdrawal_reason_id')->references('id')->on('withdrawal_reasons');
            $table->date('withdrawal_wef')->nullable();
            $table->date('application_date')->nullable();
            $table->date('last_day_at')->nullable();
            $table->date('last_invoice_paid_at')->nullable();
            $table->boolean('library_clearance')->nullable();
            $table->integer('clearance_amount')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_withdrawals');
    }
}
