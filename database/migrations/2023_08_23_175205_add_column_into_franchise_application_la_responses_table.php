<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIntoFranchiseApplicationLaResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_la_responses', function (Blueprint $table) {
            $table->integer('forwarded_to')->after('review_by')->nullable();
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
            $table->dropColumn('forwarded_to');
        });
    }
}
