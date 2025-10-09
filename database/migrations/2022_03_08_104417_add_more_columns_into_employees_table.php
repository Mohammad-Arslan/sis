<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsIntoEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('previous_id')->after('expiry_date')->nullable();
            $table->unsignedBigInteger('company_id')->after('previous_id')->nullable();
            $table->unsignedBigInteger('branch_id')->after('company_id')->nullable();
            $table->unsignedBigInteger('department_id')->after('branch_id')->nullable();
            $table->unsignedBigInteger('designation_id')->after('department_id')->nullable();
            $table->unsignedBigInteger('category_id')->after('designation_id')->nullable();
            $table->string('insurance_plan',255)->after('category_id')->nullable();
            $table->string('grade',100)->after('insurance_plan')->nullable();
            $table->string('dept_head')->after('grade')->nullable();
            $table->string('school_head')->after('dept_head')->nullable();
            $table->string('sgo_head')->after('school_head')->nullable();
            $table->string('un_entiltled')->after('sgo_head')->nullable();
            $table->string('sm')->after('un_entiltled')->nullable();
            $table->string('hm')->after('sm')->nullable();
            $table->string('acc_secr')->after('hm')->nullable();
            $table->string('multi_appraiser')->after('acc_secr')->nullable();
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
            $table->dropColumn('previous_id');
            $table->dropColumn('company_id');
            $table->dropColumn('branch_id');
            $table->dropColumn('department_id');
            $table->dropColumn('designation_id');
            $table->dropColumn('category_id');
            $table->dropColumn('insurance_plan');
            $table->dropColumn('grade');
            $table->dropColumn('dept_head');
            $table->dropColumn('school_head');
            $table->dropColumn('sgo_head');
            $table->dropColumn('un_entiltled');
            $table->dropColumn('sm');
            $table->dropColumn('hm');
            $table->dropColumn('acc_secr');
            $table->dropColumn('multi_appraiser');
        });
    }
}
