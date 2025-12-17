<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_activity', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_learning_outcome_id');
            $table->foreign('student_learning_outcome_id')->references('id')->on('student_learning_outcomes');
            $table->string('name')->nullable();
            $table->string('duration')->nullable();
            $table->text('learning_material')->nullable();
            $table->text('formative_assessment')->nullable();
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
        Schema::dropIfExists('student_activity');
    }
}
