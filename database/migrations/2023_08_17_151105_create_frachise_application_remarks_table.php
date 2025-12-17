<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrachiseApplicationRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frachise_application_remarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_application_id');
            $table->foreign('franchise_application_id')->references('id')->on('franchise_applications');
            $table->text('observation');
            $table->string('observation_for');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('user_role');
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
        Schema::dropIfExists('frachise_application_remarks');
    }
}
