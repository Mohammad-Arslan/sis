<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentConcessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_concessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('fee_charge_id');
            $table->foreign('fee_charge_id')->references('id')->on('fee_charges');
            $table->unsignedBigInteger('fee_concession_id');
            $table->foreign('fee_concession_id')->references('id')->on('fee_concessions');
            $table->boolean('is_valid')->default(1);
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
        Schema::dropIfExists('student_concessions');
    }
}
