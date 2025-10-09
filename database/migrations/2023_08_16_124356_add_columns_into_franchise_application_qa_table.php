<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoFranchiseApplicationQaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_qa', function (Blueprint $table) {
            $table->string('required_uom')->after('plot_size_required')->nullable();
            $table->string('actual_uom')->after('plot_size_actual')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_application_qa', function (Blueprint $table) {
            $table->dropColumn('required_uom');
            $table->dropColumn('actual_uom');
        });
    }
}
