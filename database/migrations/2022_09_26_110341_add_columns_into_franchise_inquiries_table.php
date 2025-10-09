<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoFranchiseInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->string('call_center_agent', 255)->nullable()->after('source_id');
            $table->string('inquiry_status', 255)->nullable()->after('source_id');
            $table->string('meeting_with_bd', 255)->nullable()->after('source_id');
            $table->string('call_back', 255)->nullable()->after('source_id');
            $table->string('launching_year', 255)->nullable()->after('source_id');
            $table->string('land_area', 255)->nullable()->after('source_id');
            $table->string('covered_area', 255)->nullable()->after('source_id');
            $table->mediumText('general_remarks')->nullable()->after('source_id');
            $table->mediumText('inquiry_remarks')->nullable()->after('source_id');
            $table->mediumText('meeting_remarks')->nullable()->after('source_id');
            $table->mediumText('expected_franchise_address')->nullable()->after('source_id');
            $table->string('experience', 255)->nullable()->after('source_id');
            $table->unsignedBigInteger('initiated_by')->nullable()->after('source_id');
            $table->unsignedBigInteger('last_updated_by')->nullable()->after('source_id');
            $table->string('created_by', 255)->nullable()->after('source_id');

            $table->foreign('initiated_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('last_updated_by')->references('id')->on('users')->cascadeOnDelete();
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
            $table->dropForeign(['initiated_by']);
            $table->dropForeign(['last_updated_by']);
            $table->dropColumn('call_center_agent');
            $table->dropColumn('inquiry_status');
            $table->dropColumn('meeting_with_bd');
            $table->dropColumn('call_back');
            $table->dropColumn('launching_year');
            $table->dropColumn('land_area');
            $table->dropColumn('covered_area');
            $table->dropColumn('general_remarks');
            $table->dropColumn('inquiry_remarks');
            $table->dropColumn('meeting_remarks');
            $table->dropColumn('expected_franchise_address');
            $table->dropColumn('experience');
            $table->dropColumn('initiated_by');
            $table->dropColumn('last_updated_by');
            $table->dropColumn('created_by');
        });
    }
}
