<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeApprovedByReferenceOnStudentWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_withdrawals', function (Blueprint $table) {
            //$table->dropForeign('approved_by');
            //$table->dropConstrainedForeignId('approved_by');

            //Add employee relation with with withdrawal approval
            // $table->unsignedBigInteger('approved_by')->nullable();
            // $table->foreign('approved_by')->references('id')->on('employees');
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
