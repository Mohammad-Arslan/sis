<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdditionalColumnChangesOnStudentWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_withdrawals', function (Blueprint $table) {

            $table->string('beneficiary_name')->nullable()->change();
            $table->string('beneficiary_cnic')->nullable()->change();
            $table->string('beneficiary_postal_address')->nullable()->change();
            $table->string('beneficiary_phone')->nullable()->change();

            //Added additional cancellation columns
            $table->string('cancellation_by')->nullable();
            $table->date('cancellation_date')->nullable();
            $table->string('cancellation_reason')->nullable();

            //Add employee relation with with withdrawal approval
            $table->unsignedBigInteger('cancellation_approved_by')->nullable();
            $table->foreign('cancellation_approved_by')->references('id')->on('employees');
            $table->string('cancellation_approved_remarks')->nullable();

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
