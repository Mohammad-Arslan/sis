<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneratorInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generator_infos', function (Blueprint $table) {
            $table->id();
            $table->date('date_refueling');
            $table->decimal('quantity_liter', 8, 2);
            $table->string('verified_by');
            $table->string('generator_capacity', 100);
            $table->time('starting_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('reading', 50)->nullable();
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
        Schema::dropIfExists('generator_infos');
    }
}
