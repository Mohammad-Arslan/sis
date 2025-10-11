<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsValidActiveTillToClassStudentSubjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_student_subjects', function (Blueprint $table) {
            $table->string('is_valid')->nullable()->default(1);
            $table->string('active_till')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('class_student_subjects', function (Blueprint $table) {
            $table->dropColumn('is_valid');
            $table->dropColumn('active_till');
        });
    }
}
