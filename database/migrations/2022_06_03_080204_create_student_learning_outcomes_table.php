<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentLearningOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_plan_id');
            $table->foreign('lesson_plan_id')->references('id')->on('lesson_plans');
            $table->string('teacher_activity')->nullable();
            $table->mediumText('description')->nullable();
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
        Schema::dropIfExists('student_learning_outcomes');
    }
}
