<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStudentWithdrawals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_withdrawals', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->unsignedBigInteger('class_student_id')->nullable();
            $table->foreign('class_student_id')->references('id')->on('class_students');
            $table->unsignedBigInteger('student_invoice_id')->nullable();
            $table->foreign('student_invoice_id')->references('id')->on('student_invoices');
            $table->date('approved_date')->nullable();
            $table->string('approval_remarks', 255)->nullable();
            $table->string('remarks', 255)->nullable();
            $table->string('refund_status')->nullable();
            $table->integer('security_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_withdrawals', function (Blueprint $table) {
            //
        });
    }
}
