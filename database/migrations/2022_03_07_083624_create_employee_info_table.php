<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('prefix', 5);
            $table->string('first_name', 50);
            $table->string('last_name', 50)->nullable();
            $table->string('preferred_name', 50)->nullable();
            $table->string('father_name', 50);
            $table->string('spouse_name', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedBigInteger('nationality_id')->nullable();
            $table->foreign('nationality_id')->references('id')->on('nationalities');
            $table->string('gender')->nullable();
            $table->unsignedBigInteger('religion_id')->nullable();
            $table->foreign('religion_id')->references('id')->on('religions');
            $table->string('CNIC',15)->nullable();
            $table->date('cnic_expiry')->nullable();
            $table->string('marital_status', 10)->nullable();
            $table->date('date_of_marriage')->nullable();
            $table->integer('no_of_children')->nullable();
            $table->integer('children_in_ucs')->nullable();
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedBigInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states');
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
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
        Schema::dropIfExists('employees');
    }
}
