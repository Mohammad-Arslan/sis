<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('campus_office_id');
            $table->foreign('campus_office_id')->references('id')->on('campus_office_types');
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedBigInteger('from_city_id');
            $table->foreign('from_city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('to_city_id');
            $table->foreign('to_city_id')->references('id')->on('cities');
            $table->string('total_duration');
            $table->date('travel_on');
            $table->date('return_on');
            $table->string('travel_mode');
            $table->text('purpose');
            $table->text('remarks')->nullable();
            $table->string('approval_status')->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
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
        Schema::dropIfExists('visit_details');
    }
}
