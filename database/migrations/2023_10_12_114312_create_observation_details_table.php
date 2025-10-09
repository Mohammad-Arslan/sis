<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObservationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observation_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_observation_id');
            $table->unsignedBigInteger('academic_years_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('subject_id');
            $table->timestamps();

            // Define foreign keys
            $table->foreign('teacher_observation_id')->references('id')->on('teacher_observations');
            $table->foreign('academic_years_id')->references('id')->on('academic_years');
            $table->foreign('class_id')->references('id')->on('branch_class_sections');
            $table->foreign('section_id')->references('id')->on('branch_class_sections');
            $table->foreign('subject_id')->references('id')->on('class_subjects');
        });
    }
    /**tr
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observation_details');
    }
}
