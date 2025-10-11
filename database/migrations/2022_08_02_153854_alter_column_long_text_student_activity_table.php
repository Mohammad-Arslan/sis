<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnLongTextStudentActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_activity', function (Blueprint $table) {
            $table->longText('methodology')->change()->nullable();
            $table->longText('resource')->change()->nullable();
            $table->longText('assessment')->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_activity', function (Blueprint $table) {
            $table->text('methodology')->change()->nullable();
            $table->text('resource')->change()->nullable();
            $table->text('assessment')->change()->nullable();
        });
    }
}
