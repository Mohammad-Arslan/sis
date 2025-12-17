<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('curriculum_category_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_targets')->default(0);
            $table->timestamps();

            $table->foreign('class_id')->references('id')->on('com_classes');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->foreign('curriculum_category_id')->references('id')->on('curriculum_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum');
    }
}
