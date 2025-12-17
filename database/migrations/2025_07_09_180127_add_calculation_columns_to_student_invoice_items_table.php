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
        Schema::table('student_invoice_items', function (Blueprint $table) {
            $table->decimal('concession_amount', 12, 2)->nullable()->after('concession');
            $table->decimal('final_amount', 12, 2)->nullable()->after('concession_amount');
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
            $table->dropColumn(['concession_amount', 'final_amount']);
        });
    }
};
