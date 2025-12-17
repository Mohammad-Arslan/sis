<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeDescriptionNullableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('building_types', function (Blueprint $table) {
            $table->string('type_description',500)->nullable()->change();
        });
        Schema::table('class_groups', function (Blueprint $table) {
            $table->string('description',500)->nullable()->change();
        });
        Schema::table('fee_tiers', function (Blueprint $table) {
            $table->string('description',500)->nullable()->change();
        });
        Schema::table('regions', function (Blueprint $table) {
            $table->string('description',500)->nullable()->change();
        });
        Schema::table('tax_types', function (Blueprint $table) {
            $table->string('description',500)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('building_types', function (Blueprint $table) {
            $table->string('type_description',500)->change();
        });
        Schema::table('class_groups', function (Blueprint $table) {
            $table->string('description',500)->change();
        });
        Schema::table('fee_tiers', function (Blueprint $table) {
            $table->string('description',500)->change();
        });
        Schema::table('regions', function (Blueprint $table) {
            $table->string('description',500)->change();
        });
        Schema::table('tax_types', function (Blueprint $table) {
            $table->string('description',500)->change();
        });
    }
}
