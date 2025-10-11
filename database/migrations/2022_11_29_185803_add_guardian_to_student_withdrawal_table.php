<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGuardianToStudentWithdrawalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_withdrawals', function (Blueprint $table) {

            $table->unsignedBigInteger('guardian_id')->nullable();
            $table->foreign('guardian_id')->references('id')->on('guardians');

            $table->string('beneficiary_name');
            $table->string('beneficiary_cnic');
            $table->string('beneficiary_postal_address')->nullable();
            $table->string('beneficiary_phone')->nullable();

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
