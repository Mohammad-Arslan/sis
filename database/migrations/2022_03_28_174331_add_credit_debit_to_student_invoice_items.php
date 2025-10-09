<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditDebitToStudentInvoiceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_invoice_items', function (Blueprint $table) {
            $table->float('debit')->nullable()->after('fee_charge_id');
            $table->float('credit')->nullable()->after('debit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_invoice_items', function (Blueprint $table) {
            $table->dropColumn('debit');
            $table->dropColumn('credit');
        });
    }
}
