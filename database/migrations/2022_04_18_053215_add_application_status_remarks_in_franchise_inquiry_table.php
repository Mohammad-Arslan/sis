<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApplicationStatusRemarksInFranchiseInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->mediumText('application_status_remarks')->nullable();
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
            $table->dropColumn('application_status_remarks');
        });
    }
}
