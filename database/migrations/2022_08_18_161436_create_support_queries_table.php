<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_queries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('raised_by');
            $table->foreign('raised_by')->references('id')->on('users');
            $table->string('url')->nullable();
            $table->string('file_name')->nullable();
            $table->text('description')->nullable();
            $table->string('priority')->nullable();
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
        Schema::dropIfExists('support_queries');
    }
}
