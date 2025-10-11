<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curriculum_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curriculum_type_id');
            $table->string('name');
            $table->boolean('targets')->default(0);
            $table->timestamps();

            // Define the foreign key relationship to the curriculum_types table
            $table->foreign('curriculum_type_id')->references('id')->on('curriculum_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum_categories');
    }
}
