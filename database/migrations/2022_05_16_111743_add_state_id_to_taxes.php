<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateIdToTaxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->after('tax_type_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes', function (Blueprint $table) {
            $table->dropForeign('taxes_state_id_foreign');
            $table->dropColumn('state_id');
        });
    }
}
