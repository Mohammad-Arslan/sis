<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassStudentSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_student_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_student_id');
            $table->foreign('class_student_id')->references('id')->on('class_students');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->timestamps();
        });

        Schema::table('class_students', function (Blueprint $table) {
            $table->dropForeign('class_students_subject_id_foreign');
            $table->dropColumn('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_student_subjects');

        Schema::table('class_students', function (Blueprint $table) {
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }
}
