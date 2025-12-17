<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeeChargesTypeIdToFeeChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_charges', function (Blueprint $table) {
            
            $table->dropColumn('name');
            $table->dropColumn('abbreviation');
            $table->dropColumn('description');
            $table->dropColumn('frequency');

            $table->unsignedBigInteger('fee_charges_type_id')->after('branch_id');
            $table->foreign('fee_charges_type_id')->references('id')->on('fee_charges_types');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_charges', function (Blueprint $table) {
            
            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->string('description')->nullable();
            $table->string('frequency');

            $table->dropForeign('fee_charges_fee_charges_type_id_foreign');
            $table->dropColumn('fee_charges_type_id');
        });
    }
}
