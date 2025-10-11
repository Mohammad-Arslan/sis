<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeePackagesFeeChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_packages_fee_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_package_id');
            $table->foreign('fee_package_id')->references('id')->on('fee_packages');
            $table->unsignedBigInteger('fee_charge_id');
            $table->foreign('fee_charge_id')->references('id')->on('fee_charges');
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
        Schema::dropIfExists('fee_packages_fee_charges');
    }
}
