<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationBdVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_application_bd_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_application_id')->nullable();
            $table->foreign('franchise_application_id', 'fa_fabdvisit_id_foreign')->references('id')->on('franchise_applications');
            $table->unsignedBigInteger('visit_by')->nullable();
            $table->foreign('visit_by')->references('id')->on('employees');
            $table->unsignedBigInteger('forward_to')->nullable();
            $table->foreign('forward_to')->references('id')->on('employees');
            $table->date('visit_date');
            $table->string('bd_status')->nullable();
            $table->string('site_address');
            $table->string('site_purpose');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('franchise_application_bd_visits');
    }
}
