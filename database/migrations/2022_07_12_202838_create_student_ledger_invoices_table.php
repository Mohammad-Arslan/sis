<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentLedgerInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_ledger_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_ledger_id');
            $table->foreign('student_ledger_id')->references('id')->on('student_ledgers');
            $table->unsignedBigInteger('student_invoice_id')->nullable();
            $table->foreign('student_invoice_id')->references('id')->on('student_invoices');
            $table->integer('sort')->nullable();
            $table->integer('month')->nullable();
            $table->double('credit')->nullable();
            $table->double('debit')->nullable();
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
        Schema::dropIfExists('student_ledger_invoices');
    }
}
