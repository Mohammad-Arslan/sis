<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_application_qualifications', function (Blueprint $table) {
            $table->id();
            $table->string('qualification')->nullable();
            $table->string('passing_year')->nullable();
            $table->string('institute')->nullable();
            $table->unsignedBigInteger('franchise_application_id')->nullable();
            $table->foreign('franchise_application_id','franch_app_id_foreign')->references('id')->on('franchise_applications');
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
        Schema::dropIfExists('franchise_application_qualifications');
    }
}
