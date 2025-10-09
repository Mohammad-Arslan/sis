<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HomeWorkDiaryAttachments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_work_diary_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diary_detail_id');
            $table->foreign('diary_detail_id')->references('id')->on('home_work_diary_detials');
            $table->string('attachment')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_work_diary_attachments');
    }
}
