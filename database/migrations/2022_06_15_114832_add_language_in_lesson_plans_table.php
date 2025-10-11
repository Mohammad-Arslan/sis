<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageInLessonPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            $table->string('language')->after('evaluation_of_teacher')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            $table->dropColumn('language');
        });
    }
}
