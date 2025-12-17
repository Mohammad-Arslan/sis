<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchisePossessSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_possess_sites', function (Blueprint $table) {
            $table->id();
            $table->string('ownership')->nullable();
            $table->string('lease_rental')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('total_area')->nullable();
            $table->string('tile_carpet')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('inquiry_id')->nullable();
            $table->foreign('inquiry_id')->references('id')->on('franchise_inquiries');
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
        Schema::dropIfExists('franchise_possess_sites');
    }
}
