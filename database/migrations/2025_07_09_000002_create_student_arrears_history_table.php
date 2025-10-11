<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_arrears_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('from_invoice_id')->nullable();
            $table->unsignedBigInteger('to_invoice_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('carried_date');
            $table->unsignedBigInteger('cleared_by_payment_id')->nullable();
            $table->date('cleared_date')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('from_invoice_id')->references('id')->on('student_invoices');
            $table->foreign('to_invoice_id')->references('id')->on('student_invoices');
            $table->foreign('cleared_by_payment_id')->references('id')->on('student_payments');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_arrears_history');
    }
}; 