<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortColumnToComClasses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('com_classes', function (Blueprint $table) {
            $table->integer('sort')->nullable()->after('class_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('com_classes', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
}
