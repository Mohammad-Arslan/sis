<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_invoice_id');
            $table->foreign('student_invoice_id')->references('id')->on('student_invoices');
            $table->unsignedBigInteger('fee_charge_id');
            $table->foreign('fee_charge_id')->references('id')->on('fee_charges');
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
        Schema::dropIfExists('student_invoice_items');
    }
}
