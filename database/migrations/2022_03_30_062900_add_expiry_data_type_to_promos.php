<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpiryDataTypeToPromos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->string('promo_unit')->after('code')->nullable();
            $table->string('promo_amount')->after('promo_unit')->nullable();
            $table->date('active_till')->after('promo_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn('promo_unit');
            $table->dropColumn('promo_amount');
            $table->dropColumn('active_till');
        });
    }
}
