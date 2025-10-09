<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFineColumnsInStudentInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->boolean('due_date_fine')->nullable()->default(1)->after('royalty_amount');
            $table->boolean('arrears_fine')->nullable()->default(1)->after('due_date_fine');
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
            $table->dropColumn('due_date_fine');
            $table->dropColumn('arrears_fine');
        });
    }
}
