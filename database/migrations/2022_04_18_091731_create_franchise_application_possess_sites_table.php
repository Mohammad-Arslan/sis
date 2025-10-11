<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseApplicationPossessSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_application_possess_sites', function (Blueprint $table) {
            $table->id();
            $table->string('ownership')->nullable();
            $table->string('lease_rental')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('total_area')->nullable();
            $table->string('tile_carpet')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('franchise_application_id')->nullable();
            $table->foreign('franchise_application_id','fa_faps_id_foreign')->references('id')->on('franchise_applications');
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
        Schema::dropIfExists('franchise_application_possess_sites');
    }
}
