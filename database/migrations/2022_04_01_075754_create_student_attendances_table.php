<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_year_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('branch_class_section_id');
            $table->foreign('branch_class_section_id')->references('id')->on('branch_class_sections');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->unsignedBigInteger('attendance_status_id');
            $table->foreign('attendance_status_id')->references('id')->on('attendance_statuses');
            $table->dateTime('attendance_date');
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
        Schema::dropIfExists('student_attendances');
    }
}
