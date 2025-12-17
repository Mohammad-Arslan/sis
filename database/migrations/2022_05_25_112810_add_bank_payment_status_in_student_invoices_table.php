<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankPaymentStatusInStudentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->string('bank_payment_status')->after('royalty_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->dropColumn('bank_payment_status');
        });
    }
}
