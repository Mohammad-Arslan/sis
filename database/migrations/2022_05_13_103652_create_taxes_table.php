<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedBigInteger('tax_type_id');
            $table->foreign('tax_type_id')->references('id')->on('tax_types');
            $table->float('tax_percentage');
            $table->date('active_from');
            $table->date('active_till');
            $table->timestamps();
        });

        Schema::table('branch_royalties', function (Blueprint $table) {
            $table->dropColumn('sales_tax');
            $table->dropColumn('fed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxes');

        Schema::table('branch_royalties', function (Blueprint $table) {
            $table->float('sales_tax')->nullable();
            $table->float('fed')->nullable();
        });
    }
}
