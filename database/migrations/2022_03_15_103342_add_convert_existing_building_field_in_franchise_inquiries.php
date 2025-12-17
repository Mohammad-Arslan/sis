<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConvertExistingBuildingFieldInFranchiseInquiries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {

            $table->string('building_status')->nullable();
            $table->string('building_ownership')->nullable();
            $table->unsignedBigInteger('building_state_id')->nullable();;
            $table->foreign('building_state_id')->references('id')->on('states');
            $table->unsignedBigInteger('building_city_id')->nullable();;
            $table->foreign('building_city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('building_town_id')->nullable();;
            $table->foreign('building_town_id')->references('id')->on('towns');
            
            $table->string('building_area')->nullable();
            $table->string('building_address')->nullable();
            $table->string('building_existing_area_size')->nullable();
            $table->string('building_covered_area')->nullable();
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
            
            $table->dropColumn('building_status');
            $table->dropColumn('building_ownership');

            $table->dropForeign('franchise_inquiries_building_state_id_foreign');
            $table->dropColumn('building_state_id');
            $table->dropForeign('franchise_inquiries_building_city_id_foreign');
            $table->dropColumn('building_city_id');
            $table->dropForeign('franchise_inquiries_building_town_id_foreign');
            $table->dropColumn('building_town_id');


            $table->dropColumn('building_area');
            $table->dropColumn('building_address');
            $table->dropColumn('building_existing_area_size');
            $table->dropColumn('building_covered_area');

        });
    }
}
