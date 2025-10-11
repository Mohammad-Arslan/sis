<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentFeePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_fee_packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('fee_package_id');
            $table->foreign('fee_package_id')->references('id')->on('fee_packages');
            $table->unsignedBigInteger('fee_concession_id');
            $table->foreign('fee_concession_id')->references('id')->on('fee_concessions');
            $table->unsignedBigInteger('academic_year_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('com_class_id');
            $table->foreign('com_class_id')->references('id')->on('com_classes');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('sections');
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
        Schema::dropIfExists('student_fee_packages');
    }
}
