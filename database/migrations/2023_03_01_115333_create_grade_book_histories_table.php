<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeBookHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_book_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_class_section_id')->constrained('branch_class_sections');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('student_behaviour_skill_id')->constrained('student_behaviour_skills');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grade_book_histories');
    }
}
