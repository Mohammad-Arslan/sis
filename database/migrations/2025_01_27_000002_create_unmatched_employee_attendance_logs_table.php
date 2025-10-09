<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnmatchedEmployeeAttendanceLogsTable extends Migration
{
    public function up()
    {
        Schema::create('unmatched_employee_attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->string('pin_code')->nullable();
            $table->string('card_no')->nullable();
            $table->dateTime('log_time');
            $table->json('raw_data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unmatched_employee_attendance_logs');
    }
}
