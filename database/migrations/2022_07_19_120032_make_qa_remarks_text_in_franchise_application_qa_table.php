<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeQaRemarksTextInFranchiseApplicationQaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_application_qa', function (Blueprint $table) {
            $table->text('qa_remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_application_qa', function (Blueprint $table) {
            $table->string('qa_remarks', 150)->nullable()->change();
        });
    }
}
