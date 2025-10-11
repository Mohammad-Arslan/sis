<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassFeePackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_fee_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('com_classes');
            $table->unsignedBigInteger('fee_package_id');
            $table->foreign('fee_package_id')->references('id')->on('fee_packages');
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
        Schema::dropIfExists('class_fee_packages');
    }
}
