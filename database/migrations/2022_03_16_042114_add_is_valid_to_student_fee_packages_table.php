<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsValidToStudentFeePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_fee_packages', function (Blueprint $table) {
            $table->boolean('is_valid')->nullable()->default(1);
            $table->date('active_till')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_fee_packages', function (Blueprint $table) {
            $table->dropColumn('is_valid');
            $table->dropColumn('active_till');
        });
    }
}
