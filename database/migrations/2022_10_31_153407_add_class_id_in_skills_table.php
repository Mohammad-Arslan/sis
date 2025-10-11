<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassIdInSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->after('parent_id')->nullable();
            $table->foreign('class_id')->references('id')->on('com_classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
        });
    }
}
