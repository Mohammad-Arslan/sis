<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialStatusColsFranchiseInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->string('criminal_record')->after('turn_over')->default('N');
            $table->string('criminal_proceedings')->after('criminal_record')->default('N');
            $table->string('unlawful_acts')->after('criminal_proceedings')->default('N');
            $table->string('criminal_record_details')->after('unlawful_acts')->nullable();
            
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
         $table->dropColumn([
            'criminal_record',
            'criminal_proceedings',
            'unlawful_acts',
            'criminal_record_details'
        ]);
     });
    }
}
