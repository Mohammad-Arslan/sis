<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoFranchiseApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_applications', function (Blueprint $table) {
            $table->double('loi_token_amount')->nullable()->after('agreement_type');
            $table->date('execution_date')->nullable()->after('loi_token_amount');
            $table->date('cut_off_date')->nullable()->after('execution_date');
            $table->date('extension_date')->nullable()->after('cut_off_date');
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
            $table->dropColumn('loi_token_amount');
            $table->dropColumn('execution_date');
            $table->dropColumn('cut_off_date');
            $table->dropColumn('extension_date');
        });
    }
}
