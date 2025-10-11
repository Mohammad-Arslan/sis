<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveFiledsInFranchiseApplicationsAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_applications_attachments', function (Blueprint $table) {
            $table->dropColumn([
                'actual_name',
                'size',
                'tmp_name'
            ]);
            $table->string('file_name')->after('attachment_type_id')->nullable();
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
            $table->dropColumn(['file_name']);
            $table->string('actual_name')->after('attachment_type_id')->nullable();
            $table->string('size')->after('type')->nullable();
            $table->string('tmp_name')->after('size')->nullable();
        });
    }
}
