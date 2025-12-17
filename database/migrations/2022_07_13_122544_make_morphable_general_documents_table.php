<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeMorphableGeneralDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_documents', function (Blueprint $table) {
            $table->integer('general_documentable_id')->after('branch_id')->nullable();
            $table->string('general_documentable_type')->after('general_documentable_id')->nullable();
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
            $table->dropColumn('general_documentable_id');
            $table->dropColumn('general_documentable_type');
        });
    }
}
