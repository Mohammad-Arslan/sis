<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecurityDepositIntoStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->integer('security_deposit')->default('0')->after('branch_id');
            $table->unsignedBigInteger('admission_year_id')->after('security_deposit')->nullable();
            $table->foreign('admission_year_id')->references('id')->on('academic_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('security_deposit');
            $table->dropForeign(['students_admission_year_id_foreign']);
            $table->dropColumn('admission_year_id');
        });
    }
}
