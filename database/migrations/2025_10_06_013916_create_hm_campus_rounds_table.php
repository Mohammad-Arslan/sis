<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHmCampusRoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hm_campus_rounds', function (Blueprint $table) {
            $table->id();
            $table->string('area');
            $table->time('time_from');
            $table->time('time_to');
            $table->text('purpose');
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
        Schema::dropIfExists('hm_campus_rounds');
    }
}
