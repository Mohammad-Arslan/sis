<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('observation_detail_id');
            $table->unsignedBigInteger('question_dimension_id');
            $table->unsignedBigInteger('answer_dimension_id');
            $table->tinyInteger('rating');
            $table->timestamps();

            $table->foreign('observation_detail_id')->references('id')->on('observation_details');
            $table->foreign('question_dimension_id')->references('id')->on('question_dimensions');
            $table->foreign('answer_dimension_id')->references('id')->on('answer_dimensions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
