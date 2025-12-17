<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvaludationColumnsInLessonPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_plans', function (Blueprint $table) {
            $table->text('evaluation_of_teacher')->after('approved_by')->nullable();
            $table->text('evaluation_of_student')->after('approved_by')->nullable();
            $table->date('taught_date_to')->after('approved_by')->nullable();
            $table->date('taught_date_from')->after('approved_by')->nullable();
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
            $table->dropColumn('taught_date_from');
            $table->dropColumn('taught_date_to');
            $table->dropColumn('evaluation_of_student');
            $table->dropColumn('evaluation_of_teacher');
        });
    }
}
