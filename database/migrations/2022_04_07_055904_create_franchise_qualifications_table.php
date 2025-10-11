<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseQualificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_qualifications', function (Blueprint $table) {
            $table->id();
            $table->string('qualification')->nullable();
            $table->string('passing_year')->nullable();
            $table->string('institute')->nullable();
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
        Schema::dropIfExists('franchise_qualifications');
    }
}
