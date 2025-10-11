<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassBooklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_booklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('com_classes');
            $table->unsignedBigInteger('booklist_id');
            $table->foreign('booklist_id')->references('id')->on('booklists');
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('class_booklists');
    }
}
