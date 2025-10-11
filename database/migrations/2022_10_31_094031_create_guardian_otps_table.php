<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuardianOtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guardian_otps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guardian_id');
            $table->foreign('guardian_id')->references('id')->on('guardians');
            $table->integer('OTP');
            $table->string('status');
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
        Schema::dropIfExists('guardian_otps');
    }
}
