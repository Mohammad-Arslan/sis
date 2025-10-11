<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvoiceFrequencyToStudentInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->string('invoice_frequency')->nullable()->after('invoice_type_id');
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
            $table->dropColumn('invoice_frequency');
        });
    }
}
