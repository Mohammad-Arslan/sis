<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoFranchiseApplicationTorsResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_tors_responses', function (Blueprint $table) {
            $table->date('actual_operational_date')->after('operational_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_application_tors_responses', function (Blueprint $table) {
            $table->dropColumn('actual_operational_date');
        });
    }
}
