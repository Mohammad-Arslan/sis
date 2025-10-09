<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatecolumnIntoFranchiseApplicationLaResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_la_responses', function (Blueprint $table) {
            $table->date('forwarded_date')->after('forwarded_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_application_la_responses', function (Blueprint $table) {
            $table->dropColumn('forwarded_date');
        });
    }
}
