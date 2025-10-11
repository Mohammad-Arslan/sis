<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveColumnFromNewSchoolFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_school_fee_structures', function (Blueprint $table) {
            $table->dropColumn('nearest_bss_school');
            $table->dropColumn('fee_charges');
            $table->dropColumn('school_fee');
            $table->dropColumn('admission_fee');
            $table->dropColumn('security_fee');
            $table->dropColumn('registration_fee');
            $table->dropColumn('final_fee_charges');
            $table->dropColumn('round_final_fee_charges')->nullable();
            DB::statement("ALTER TABLE new_school_fee_structures DROP COLUMN fee_status_by_dd");
            $table->dropColumn('approval_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_school_fee_structures', function (Blueprint $table) {
            $table->string('nearest_bss_school');
            $table->unsignedInteger('fee_charges');
            $table->unsignedInteger('school_fee');
            $table->unsignedInteger('admission_fee');
            $table->unsignedInteger('security_fee');
            $table->unsignedInteger('registration_fee');
            $table->unsignedInteger('final_fee_charges');
            $table->unsignedInteger('round_final_fee_charges')->nullable();
            $table->enum('fee_status_by_dd', ['Approved', 'Rejected', 'Pending']);
            $table->date('approval_date')->nullable();
        });
    }
}
