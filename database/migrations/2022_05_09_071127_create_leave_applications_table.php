<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_type_id');
            $table->foreign('application_type_id')->references('id')->on('application_types');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->date('application_date');
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->integer('num_of_days')->nullable();
            $table->string('category',255)->nullable();
            $table->boolean('with_pay')->nullable();
            $table->string('type',255)->nullable();
            $table->text('reason',500)->nullable();
            $table->unsignedBigInteger('forward_to');
            $table->foreign('forward_to')->references('id')->on('employees');
            $table->date('adjustment_date')->nullable();
            $table->date('off_day_work_date')->nullable();
            $table->time('arrival_time')->nullable();
            $table->time('departure_time')->nullable();
            $table->date('attendance_not_marked_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_applications');
    }
}
