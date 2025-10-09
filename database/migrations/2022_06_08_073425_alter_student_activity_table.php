<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStudentActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_activity', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->text('methodology')->after('student_learning_outcome_id')->nullable();

            $table->dropColumn('learning_material');
            $table->text('resource')->after('methodology')->nullable();

            $table->dropColumn('formative_assessment');
            $table->text('assessment')->after('resource')->nullable();

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
            $table->string('name')->nullable();
            $table->dropColumn('methodology');

            $table->mediumText('learning_material')->nullable();
            $table->dropColumn('resource');

            $table->text('formative_assessment')->nullable();
            $table->dropColumn('assessment');
        });
    }
}
