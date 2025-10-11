<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectMarksSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_marks_setups', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');

            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign('term_id')->references('id')->on('terms');

            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches');

            $table->unsignedBigInteger('class_id')->nullable();
            $table->foreign('class_id')->references('id')->on('com_classes');

            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');

            $table->integer('assessment_level_one_id')->nullable();
            $table->integer('assessment_level_two_id')->nullable();
            $table->integer('assessment_level_three_id')->nullable();
            $table->double('marks')->nullable();
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
        Schema::dropIfExists('subject_marks_setups');
    }
}
