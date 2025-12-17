<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOfficeUseFieldsInFranchiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->string('agreement_type')->after('status')->nullable();
            
            $table->unsignedBigInteger('recommended_by')->nullable();
            $table->foreign('recommended_by')->references('id')->on('users');
            $table->date('recommended_date')->after('recommended_by')->nullable();            

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('users');
            $table->date('approved_date')->after('approved_by')->nullable();

            $table->unsignedBigInteger('forwarded_by')->nullable();
            $table->foreign('forwarded_by')->references('id')->on('users');
            $table->date('forwarded_date')->after('forwarded_by')->nullable();
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
            $table->dropColumn('agreement_type');

            $table->dropForeign('franchise_inquiries_recommended_by_foreign');
            $table->dropColumn('recommended_by');
            $table->dropColumn('recommended_date');
            
            $table->dropForeign('franchise_inquiries_approved_by_foreign');
            $table->dropColumn('approved_by');
            $table->dropColumn('approved_date');

             $table->dropForeign('franchise_inquiries_forwarded_by_foreign');
            $table->dropColumn('forwarded_by');
            $table->dropColumn('forwarded_date');
        });
    }
}
