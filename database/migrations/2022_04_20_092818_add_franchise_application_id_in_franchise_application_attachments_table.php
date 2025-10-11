<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFranchiseApplicationIdInFranchiseApplicationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_applications_attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('franchise_application_id')->after('id')->nullable();
            $table->foreign('franchise_application_id','fa_faa_id_foreign')->references('id')->on('franchise_applications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_applications_attachments', function (Blueprint $table) {
            $table->dropForeign('fa_faa_id_foreign');
            $table->dropColumn('franchise_application_id');
        });
    }
}
