<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNextobservationdateAndPartOfPeriodToObservationDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('observation_details', function (Blueprint $table) {
            $table->dateTime('nextobservationdate')->nullable();
            $table->enum('part_of_period_observed', ['FIRST 20 MIN', 'MID 20 MIN', 'LAST 20 MIN', 'FULL PERIOD'])->default('FULL PERIOD');
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
            $table->dropColumn('nextobservationdate');
            $table->dropColumn('part_of_period_observed');
        });
    }
}
