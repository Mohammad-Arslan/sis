<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherObservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_observations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('evaluation_user_id');
            $table->timestamps();
            $table->softDeletes(); // Add soft delete columns

            // Define foreign keys
            $table->foreign('branch_id')->references('id')->on('branch_class_sections');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('evaluation_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_observations');
    }
}
