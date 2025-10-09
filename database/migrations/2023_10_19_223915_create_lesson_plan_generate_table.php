<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonPlanGenerateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create lesson_plans table
        Schema::create('lesson_plans_generate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('academic_year_id');
            $table->unsignedBigInteger('com_class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('week_id');

            $table->unsignedBigInteger('state_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->integer('day');
            $table->string('topic')->nullable();
            $table->string('chapter')->nullable();
            $table->text('learning_outcomes')->nullable();
            $table->text('lesson_plan_details')->nullable();
            $table->string('bocc_link')->nullable();
            $table->string('approval_status')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->foreign('com_class_id')->references('id')->on('com_classes');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('term_id')->references('id')->on('terms');
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('section_id')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_plans_generate');
    }
}
