<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationOtherInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_application_other_information', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('designation');
            $table->string('experience');
            $table->string('personally_associated_with_org');
            $table->string('personally_associated_info')->nullable();
            $table->string('family_member_associated_with_org');
            $table->string('family_associated_info')->nullable();
            $table->unsignedBigInteger('franchise_application_id')->nullable();
            $table->foreign('franchise_application_id','fa_faoi_id_foreign')->references('id')->on('franchise_applications');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('franchise_application_other_information');
    }
}
