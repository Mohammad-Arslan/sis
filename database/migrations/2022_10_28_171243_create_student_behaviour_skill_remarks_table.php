<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentBehaviourSkillRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_behaviour_skill_remarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_behaviour_skill_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->text('teacher_comments')->nullable();
            $table->text('schoolhead_comments')->nullable();
            $table->integer('is_promoted')->nullable();
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
        Schema::dropIfExists('student_behaviour_skill_remarks');
    }
}
