<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddActiveColumnToFeePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_packages', function (Blueprint $table) {
            $table->date('active_from')->nullable();
            $table->date('active_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_packages', function (Blueprint $table) {
            $table->dropColumn('active_from');
            $table->dropColumn('active_to');
        });
    }
}
