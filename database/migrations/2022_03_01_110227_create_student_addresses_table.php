<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('res_country_id');
            $table->foreign('res_country_id')->references('id')->on('countries');
            $table->unsignedBigInteger('res_state_id');
            $table->foreign('res_state_id')->references('id')->on('states');
            $table->unsignedBigInteger('res_city_id');
            $table->foreign('res_city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('res_town_id');
            $table->foreign('res_town_id')->references('id')->on('towns');
            $table->string('res_postal_code');
            $table->string('res_contact_person');
            $table->string('res_phone');
            $table->string('res_sms_number');
            $table->string('res_mobile');
            $table->unsignedBigInteger('per_city_id');
            $table->foreign('per_city_id')->references('id')->on('cities');
            $table->string('per_phone');
            $table->string('per_postal_code');
            $table->string('per_address');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_addresses');
    }
}
