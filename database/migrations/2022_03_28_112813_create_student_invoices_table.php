<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_fee_package_id')->nullable();
            $table->foreign('student_fee_package_id')->references('id')->on('student_fee_packages');
            $table->unsignedBigInteger('promo_id')->nullable();
            $table->foreign('promo_id')->references('id')->on('promos');
            $table->unsignedBigInteger('invoice_type_id');
            $table->foreign('invoice_type_id')->references('id')->on('invoice_types');
            $table->unsignedBigInteger('payment_source_id')->nullable();
            $table->foreign('payment_source_id')->references('id')->on('payment_sources');
            $table->string('invoice_no');
            $table->boolean('is_paid')->default(0);
            $table->date('paid_date')->nullable();
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
        Schema::dropIfExists('student_invoices');
    }
}
