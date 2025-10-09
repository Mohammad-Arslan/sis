<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoEmployeeOfficialLeaveDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_official_leave_days', function (Blueprint $table) {
            $table->date('leave_date')->after('official_leave_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_official_leave_days', function (Blueprint $table) {
            $table->dropColumn('leave_date');
        });
    }
}
