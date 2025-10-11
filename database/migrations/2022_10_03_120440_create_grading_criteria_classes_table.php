<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradingCriteriaClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grading_criteria_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grading_criteria_id')->nullable();
            $table->foreign('grading_criteria_id')->references('id')->on('grading_criteria');
            $table->unsignedBigInteger('class_id')->nullable();
            $table->foreign('class_id')->references('id')->on('com_classes');
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
        Schema::dropIfExists('grading_criteria_classes');
    }
}
