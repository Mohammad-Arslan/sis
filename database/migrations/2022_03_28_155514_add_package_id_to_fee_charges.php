<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackageIdToFeeCharges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_charges', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_package_id')->nullable()->after('id');
            $table->foreign('fee_package_id')->references('id')->on('fee_packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_charges', function (Blueprint $table) {
            $table->dropForeign('fee_charges_fee_package_id_foreign');
            $table->dropColumn('fee_package_id');
        });
    }
}
