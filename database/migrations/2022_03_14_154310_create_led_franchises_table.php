<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedFranchisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('led_franchises', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 200);
            $table->integer('experience');
            $table->string('connected')->default('N');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('inquirer_id');
            $table->foreign('inquirer_id')->references('id')->on('franchise_inquiries');
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
        Schema::dropIfExists('led_franchises');
    }
}
