<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableInContactInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_information', function (Blueprint $table) {
            $table->string('phone', 255)->nullable()->change();
            $table->string('email', 255)->nullable()->change();
            $table->string('fax', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_information', function (Blueprint $table) {
            $table->string('phone', 255)->change();
            $table->string('email', 255)->change();
            $table->string('fax', 255)->change();
        });
    }
}
