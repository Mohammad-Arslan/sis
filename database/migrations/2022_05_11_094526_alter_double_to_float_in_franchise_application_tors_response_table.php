<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDoubleToFloatInFranchiseApplicationTorsResponseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_tors_responses', function (Blueprint $table) {
            $table->float('total_franchise_fee')->nullable()->change();
            $table->float('royalty_rate')->nullable()->change();
            $table->float('payment_on_mou')->nullable()->change();
            $table->float('payment_on_agreement')->nullable()->change();
            $table->float('token_money')->nullable()->change();
            $table->float('amount_received')->nullable()->change();
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
            $table->float('total_franchise_fee')->nullable()->change();
            $table->float('royalty_rate')->nullable()->change();
            $table->float('payment_on_mou')->nullable()->change();
            $table->float('payment_on_agreement')->nullable()->change();
            $table->float('token_money')->nullable()->change();
            $table->float('amount_received')->nullable()->change();
        });
    }
}
