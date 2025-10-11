<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyEmployeeIdNullableInStudentAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropForeign(['employee_id']); // Constraint removed
            $table->unsignedBigInteger('employee_id')->nullable()->change(); // Column stays
            $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete(); // Constraint re-added
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            //
        });
    }
}
