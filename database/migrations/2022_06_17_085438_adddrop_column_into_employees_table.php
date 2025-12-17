<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdddropColumnIntoEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('dept_head');
            $table->dropColumn('school_head');
            $table->dropColumn('sgo_head');
            $table->dropColumn('un_entiltled');
            $table->dropColumn('sm');
            $table->dropColumn('hm');
            $table->dropColumn('acc_secr');
            $table->dropColumn('multi_appraiser');
            $table->date('date_of_birth')->after('cnic_expiry')->nullable();
            $table->string('basic_salary')->after('grade')->nullable();
            $table->string('gross_salary')->after('basic_salary')->nullable();
            $table->string('allownces')->after('gross_salary')->nullable();
            $table->string('address',1000)->after('allownces')->nullable();

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
            $table->dropColumn('date_of_birth');
            $table->dropColumn('basic_salary');
            $table->dropColumn('gross_salary');
            $table->dropColumn('allownces');
            $table->dropColumn('address');
            $table->string('dept_head',255)->after('grade')->nullable();
            $table->string('school_head',255)->after('dept_head')->nullable();
            $table->string('sgo_head',255)->after('school_head')->nullable();
            $table->string('un_entiltled',255)->after('sgo_head')->nullable();
            $table->string('sm',255)->after('un_entiltled')->nullable();
            $table->string('hm',255)->after('sm')->nullable();
            $table->string('acc_secr',255)->after('hm')->nullable();
            $table->string('multi_appraiser',255)->after('acc_secr')->nullable();
        });
    }
}
