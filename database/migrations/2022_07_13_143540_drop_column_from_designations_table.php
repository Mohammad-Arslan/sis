<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnFromDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->bigInteger('sort_order')->after('type_id')->nullable();
        });
    }
}
