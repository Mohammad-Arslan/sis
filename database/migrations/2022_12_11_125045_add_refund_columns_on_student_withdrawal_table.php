<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundColumnsOnStudentWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_withdrawals', function (Blueprint $table) {
            $table->date('cheque_date')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('refund_remarks')->nullable();
            $table->string('application_status')->default('In Process')->nullable();
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
