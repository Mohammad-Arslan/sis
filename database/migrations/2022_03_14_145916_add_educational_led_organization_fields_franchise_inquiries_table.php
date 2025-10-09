<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEducationalLedOrganizationFieldsFranchiseInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->string('edu_organization_name', 200)->after('organization_name')->nullable();
            $table->string('inquirer_edu_designation', 200)->after('edu_organization_name')->nullable();
            $table->string('inquirer_experience', 200)->after('inquirer_edu_designation')->nullable();
            $table->string('edu_organization_desc', 50)->after('inquirer_experience')->nullable();
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
             $table->dropColumn('edu_organization_name');
             $table->dropColumn('inquirer_edu_designation');
             $table->dropColumn('inquirer_experience');
             $table->dropColumn('edu_organization_desc');
            //
        });
    }
}
