<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToStudentAssessmentMarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_assessment_marks', function (Blueprint $table) {
            $table->string('overall_grade')->nullable()->after('out_of');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_assessment_marks', function (Blueprint $table) {
            $table->dropColumn('overall_grade');
        });
    }
}
