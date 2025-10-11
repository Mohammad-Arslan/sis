<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMiddleNameInStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('middle_name')->nullable()->change();
        });
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->string('fee_month')->nullable()->change();
            $table->date('arrears_date')->nullable()->after('validity_date');
            $table->string('arrears_amount')->nullable()->after('arrears_date');
        });
        Schema::table('student_fee_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_concession_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('middle_name')->change();
        });
        Schema::table('student_invoices', function (Blueprint $table) {
            $table->dropColumn('arrears_date');
            $table->dropColumn('arrears_amount');
        });
    }
}
