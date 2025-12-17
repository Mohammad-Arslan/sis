<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('job_status')->after('children_in_ucs')->nullable();
            $table->date('hiring_date')->after('city_id')->nullable();
            $table->date('confirm_date')->after('hiring_date')->nullable();
            $table->date('regular_date')->after('confirm_date')->nullable();
            $table->date('left_date')->after('regular_date')->nullable();
            $table->date('from_date')->after('left_date')->nullable();
            $table->date('to_date')->after('from_date')->nullable();
            $table->date('probation_end_date')->after('to_date')->nullable();
            $table->string('probation_extended')->after('probation_end_date')->nullable();
            $table->string('death_case')->after('probation_extended')->nullable();
            $table->date('death_date')->after('death_case')->nullable();
            $table->string('eobi_number')->after('death_date')->nullable();
            $table->string('ni_number')->after('eobi_number')->nullable();
            $table->string('emp_email')->after('ni_number')->nullable();
            $table->string('mobile_number')->after('emp_email')->nullable();
            $table->string('passport_number')->after('mobile_number')->nullable();
            $table->string('crb')->after('passport_number')->nullable();
            $table->date('issue_date')->after('crb')->nullable();
            $table->date('expiry_date')->after('issue_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('job_status');
            $table->dropColumn('hiring_date');
            $table->dropColumn('confirm_date');
            $table->dropColumn('regular_date');
            $table->dropColumn('left_date');
            $table->dropColumn('from_date');
            $table->dropColumn('to_date');
            $table->dropColumn('probation_end_date');
            $table->dropColumn('probation_extended');
            $table->dropColumn('death_case');
            $table->dropColumn('death_date');
            $table->dropColumn('eobi_number');
            $table->dropColumn('ni_number');
            $table->dropColumn('emp_email');
            $table->dropColumn('mobile_number');
            $table->dropColumn('passport_number');
            $table->dropColumn('crb');
            $table->dropColumn('issue_date');
            $table->dropColumn('expiry_date');
        });
    }
}
