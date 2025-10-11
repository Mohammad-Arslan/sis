<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicYearWorkingDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_year_working_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states');
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedBigInteger('academic_year_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('term_id')->nullable();
            $table->foreign('term_id')->references('id')->on('terms');
            $table->integer('working_days');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('academic_year_working_days');
    }
}
