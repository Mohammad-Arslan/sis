<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->nullable()->after('arrears_fine');
            $table->decimal('total_discount', 12, 2)->nullable()->after('subtotal');
            $table->decimal('total_payable', 12, 2)->nullable()->after('total_discount');
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
            $table->dropColumn(['subtotal', 'total_discount', 'total_payable']);
        });
    }
};
