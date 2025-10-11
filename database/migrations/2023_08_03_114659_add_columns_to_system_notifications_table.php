<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSystemNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id')->after('branch_id')->nullable();
            $table->unsignedBigInteger('section_id')->after('branch_id')->nullable();
            $table->foreign('class_id')->references('id')->on('com_classes');
            $table->foreign('section_id')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropForeign(['section_id']);
            $table->dropColumn('class_id');
            $table->dropColumn('section_id');
        });
    }
}
