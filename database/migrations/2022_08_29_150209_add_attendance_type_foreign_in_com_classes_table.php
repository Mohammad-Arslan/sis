<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttendanceTypeForeignInComClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('com_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('attendance_type_id')->nullable()->after('id');
            $table->foreign('attendance_type_id')->references('id')->on('attendance_types');
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
            $table->dropForeign(['attendance_type_id']);
            $table->dropColumn('attendance_type_id');
        });
    }
}
