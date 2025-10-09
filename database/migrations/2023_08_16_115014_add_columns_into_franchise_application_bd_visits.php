<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoFranchiseApplicationBdVisits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_bd_visits', function (Blueprint $table) {
            $table->date('forwarded_date')->after('forward_to')->nullable();
            $table->date('approval_date')->after('approved_by')->nullable();
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
            $table->dropColumn('forwarded_date');
            $table->dropColumn('approval_date');
        });
    }
}
