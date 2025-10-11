<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFeePeriods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_periods', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable()->after('id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedBigInteger('academic_year_id')->nullable()->after('branch_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->string('period_name')->nullable()->change();
            $table->string('period_description')->nullable()->change();
            $table->string('frequency')->nullable()->after('period_description');
            $table->date('from_date')->nullable()->after('frequency');
            $table->date('to_date')->nullable()->after('from_date');
            $table->date('issue_date')->nullable()->after('to_date');
            $table->date('due_date')->nullable()->after('issue_date');
            $table->date('valid_date')->nullable()->after('due_date');
            $table->date('arrears_date')->nullable()->after('valid_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_periods', function (Blueprint $table) {
            $table->dropForeign('fee_periods_branch_id_foreign');
            $table->dropColumn('branch_id');
            $table->dropForeign('fee_periods_academic_year_id_foreign');
            $table->dropColumn('academic_year_id');
            $table->dropColumn('frequency');
            $table->dropColumn('from_date');
            $table->dropColumn('to_date');
            $table->dropColumn('issue_date');
            $table->dropColumn('due_date');
            $table->dropColumn('valid_date');
            $table->dropColumn('arrears_date');
        });
    }
}
