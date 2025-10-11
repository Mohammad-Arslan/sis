<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatNoInFranchiseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_applications', function (Blueprint $table) {
            $table->integer('seat_no')->after('state_id')->nullable();
            $table->integer('constituency_id')->after('seat_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_applications', function (Blueprint $table) {
            $table->dropColumn('seat_no');
            $table->dropColumn('constituency_id');
        });
    }
}
