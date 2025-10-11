<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnInNewSchoolFeeStructures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_school_fee_structures', function (Blueprint $table) {
            $table->integer('parent_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_school_fee_structures', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
