<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityIdIntoFranchiseInquiries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('source_id');
            $table->foreign('source_id')->references('id')->on('sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->dropForeign('franchise_inquiries_city_id_foreign');
            $table->dropColumn('city_id');
            $table->dropForeign('franchise_inquiries_source_id_foreign');
            $table->dropColumn('source_id');
        });
    }
}
