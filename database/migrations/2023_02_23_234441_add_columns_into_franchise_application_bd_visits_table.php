<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoFranchiseApplicationBdVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_bd_visits', function (Blueprint $table) {
            $table->unsignedBigInteger('school_type')->after('bd_status')->nullable();
            $table->string('school_configuration')->after('school_type')->nullable();
            $table->string('area_population_half_km_radius')->after('school_configuration')->nullable();
            $table->string('area_population_one_km_radius')->after('area_population_half_km_radius')->nullable();
            $table->string('area_population_two_km_radius')->after('area_population_one_km_radius')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_application_bd_visits', function (Blueprint $table) {
            $table->dropColumn('school_type');
            $table->dropColumn('school_configuration');
            $table->dropColumn('area_population_half_km_radius');
            $table->dropColumn('area_population_one_km_radius');
            $table->dropColumn('area_population_two_km_radius');
        });
    }
}
