<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsFranchiseInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->string('appl_last_name', 50)->after('appl_name')->nullable();
            $table->integer('state_id')->after('deleted_at')->nullable();
            $table->string('organization_name', 50)->after('other_profession')->nullable();
            $table->string('inquirer_designation', 50)->after('organization_name')->nullable();
            $table->string('inquirer_qualification', 50)->after('inquirer_designation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->dropColumn('appl_last_name');
            $table->dropColumn('state_id');
            $table->dropColumn('organization_name');
            $table->dropColumn('inquirer_designation');
            $table->dropColumn('inquirer_qualification');
        });
    }
}
