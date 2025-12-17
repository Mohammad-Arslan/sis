<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusInFranchiseApplicationLaResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_la_responses', function (Blueprint $table) {
            $table->mediumText('remarks')->nullable()->change();
            $table->string('status')->after('review_by');
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
            $table->mediumText('remarks')->change();
            $table->dropColumn('status');
        });
    }
}
