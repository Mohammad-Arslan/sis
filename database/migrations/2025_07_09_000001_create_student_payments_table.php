<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->string('method')->nullable();
            $table->string('reference')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('invoice_id')->references('id')->on('student_invoices');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_payments');
    }
}; 