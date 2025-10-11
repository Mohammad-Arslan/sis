<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchWorkingShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_working_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staff_types');
            $table->unsignedBigInteger('working_day_id');
            $table->foreign('working_day_id')->references('id')->on('working_days');
            $table->unsignedBigInteger('working_shift_id');
            $table->foreign('working_shift_id')->references('id')->on('working_shifts');
            $table->string('name',500);
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
        Schema::dropIfExists('branch_working_shifts');
    }
}
