<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAcademicClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('class_students', function (Blueprint $table) {
            $table->dropForeign('class_students_academic_class_id_foreign');
            $table->dropColumn('academic_class_id');

            $table->unsignedBigInteger('academic_year_id')->nullable()->after('id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('branch_class_section_id')->nullable()->after('academic_year_id');
            $table->foreign('branch_class_section_id')->references('id')->on('branch_class_sections');
        });

        Schema::table('class_teachers', function (Blueprint $table) {
            $table->dropForeign('class_teachers_academic_class_id_foreign');
            $table->dropColumn('academic_class_id');

            $table->unsignedBigInteger('academic_year_id')->nullable()->after('id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('branch_class_section_id')->nullable()->after('academic_year_id');
            $table->foreign('branch_class_section_id')->references('id')->on('branch_class_sections');
        });

        Schema::dropIfExists('academic_classes');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('academic_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('branch_class_section_id')->nullable();
            $table->foreign('branch_class_section_id')->references('id')->on('branch_class_sections');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('class_students', function (Blueprint $table) {
            $table->dropForeign('class_students_academic_year_id_foreign');
            $table->dropColumn('academic_year_id');
            $table->dropForeign('class_students_branch_class_section_id_foreign');
            $table->dropColumn('branch_class_section_id');

            $table->unsignedBigInteger('academic_class_id')->nullable()->after('id');
            $table->foreign('academic_class_id')->references('id')->on('academic_classes');
        });

        Schema::table('class_teachers', function (Blueprint $table) {
            $table->dropForeign('class_teachers_academic_year_id_foreign');
            $table->dropColumn('academic_year_id');
            $table->dropForeign('class_teachers_branch_class_section_id_foreign');
            $table->dropColumn('branch_class_section_id');

            $table->unsignedBigInteger('academic_class_id')->nullable()->after('id');
            $table->foreign('academic_class_id')->references('id')->on('academic_classes');
        });
    }
}
