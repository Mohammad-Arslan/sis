<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentBehaviourSkillMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_behaviour_skill_marks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('student_behaviour_skill_id')->nullable();
            $table->foreign('student_behaviour_skill_id')->references('id')->on('student_behaviour_skills');

            $table->unsignedBigInteger('skill_id')->nullable();
            $table->foreign('skill_id')->references('id')->on('skills');

            $table->unsignedBigInteger('general_behaviour_id')->nullable();
            $table->foreign('general_behaviour_id')->references('id')->on('general_behaviours');

            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');

            $table->string('grade')->nullable();

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
        Schema::dropIfExists('student_behaviour_skill_marks');
    }
}
