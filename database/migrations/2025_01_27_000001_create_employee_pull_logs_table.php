<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('employee_pull_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->dateTime('LTime')->nullable();
            $table->string('Pin')->nullable();
            $table->string('CardNo')->nullable();
            $table->string('DoorId')->nullable();
            $table->string('EventType')->nullable();
            $table->string('IsInState')->nullable();
            $table->string('IpAdress')->nullable();
            $table->string('Port')->nullable();
            $table->string('MachineName')->nullable();
            $table->dateTime('CreatedDate')->nullable();
            $table->date('addeddate')->nullable();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_pull_logs');
    }
};
