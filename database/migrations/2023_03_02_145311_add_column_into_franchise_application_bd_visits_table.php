<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoFranchiseApplicationBdVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_bd_visits', function (Blueprint $table) {
            $table->string('proposed_school_name',255)->after('school_configuration')->nullable();
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
            $table->dropColumn('proposed_school_name');
        });
    }
}
