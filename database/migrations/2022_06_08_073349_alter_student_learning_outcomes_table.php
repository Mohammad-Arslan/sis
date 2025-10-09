<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStudentLearningOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_learning_outcomes', function (Blueprint $table) {
            $table->text('teacher_activity')->change()->nullable();
            $table->text('description')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_learning_outcomes', function (Blueprint $table) {
            $table->string('teacher_activity')->change()->nullable();
            $table->mediumText('description')->change()->nullable();
        });
    }
}
