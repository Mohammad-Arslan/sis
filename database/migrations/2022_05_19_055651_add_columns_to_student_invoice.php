<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStudentInvoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_period_id')->nullable()->after('payment_source_id');
            $table->foreign('fee_period_id')->references('id')->on('fee_periods');
            $table->date('issue_date')->nullable()->after('fee_period_id');
            $table->date('due_date')->nullable()->after('issue_date');
            $table->date('validity_date')->nullable()->after('due_date');
            $table->date('fee_month')->nullable()->after('validity_date');
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
            $table->dropColumn('issue_date');
            $table->dropColumn('due_date');
            $table->dropColumn('validity_date');
            $table->dropForeign('student_invoices_fee_period_id_foreign');
            $table->dropColumn('fee_period_id');
            $table->dropColumn('fee_month');
        });
    }
}
