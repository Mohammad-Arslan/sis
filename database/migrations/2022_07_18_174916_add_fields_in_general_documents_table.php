<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsInGeneralDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_documents', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_year_id')->after('attachment_type_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('com_class_id')->after('branch_id')->nullable();
            $table->foreign('com_class_id')->references('id')->on('com_classes');
            $table->unsignedBigInteger('subject_id')->after('com_class_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('state_id')->after('subject_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_documents', function (Blueprint $table) {
            $table->dropForeign(['academic_year_id']);
            $table->dropForeign(['com_class_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['state_id']);

            $table->dropColumn('academic_year_id');
            $table->dropColumn('com_class_id');
            $table->dropColumn('subject_id');
            $table->dropColumn('state_id');
        });
    }
}
