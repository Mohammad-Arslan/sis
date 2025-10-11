<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApprovedByToFranchiseApplicationBdVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_bd_visits', function (Blueprint $table) {
            $table->integer('approved_by')->after('forward_to')->nullable();
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
            $table->dropColumn('approved_by');
        });
    }
}
