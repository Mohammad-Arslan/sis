<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RatingSystemToObservationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('observation_details', function (Blueprint $table) {
            $table->integer('planning_preparing_rating')->nullable();
            $table->integer('relation_rating')->nullable();
            $table->integer('promoting_interest_rating')->nullable();
            $table->integer('maintaining_relation_rating')->nullable();
            $table->integer('assessment_application_rating')->nullable();
            $table->integer('catering_diversity_rating')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('observation_details', function (Blueprint $table) {
            $table->dropColumn('planning_preparing_rating');
            $table->dropColumn('maintaining_relation_rating');
            $table->dropColumn('promoting_interest_rating');
            $table->dropColumn('maintaining_relation_rating');
            $table->dropColumn('assessment_application_rating');
            $table->dropColumn('catering_diversity_rating');
        });
    }
}
