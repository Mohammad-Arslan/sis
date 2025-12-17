<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswerDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer_dimensions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_dimensions_id');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('question_dimensions_id')->references('id')->on('question_dimensions');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer_dimensions');
    }
}
