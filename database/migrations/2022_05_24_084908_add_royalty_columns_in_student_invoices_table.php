<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoyaltyColumnsInStudentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->float('royalty_amount')->default(0)->after('paid_date');
            $table->float('royalty_percentage')->default(0)->after('paid_date');
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
            $table->dropColumn('royalty_amount');
            $table->dropColumn('royalty_percentage');
        });
    }
}
