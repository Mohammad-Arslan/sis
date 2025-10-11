<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAssessmentMarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_assessment_marks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('assessment_entry_id');
            $table->foreign('assessment_entry_id')->references('id')->on('assessment_entries');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');

            $table->string('obtained_marks_grades')->nullable();
            $table->string('out_of')->nullable();

            $table->string('status')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('student_assessment_marks');
    }
}
