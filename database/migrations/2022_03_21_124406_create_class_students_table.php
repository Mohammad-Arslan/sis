<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_class_id')->nullable();
            $table->foreign('academic_class_id')->references('id')->on('academic_classes');
            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->boolean('is_valid')->default(1);
            $table->date('active_till')->nullable();
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
        Schema::dropIfExists('class_students');
    }
}
