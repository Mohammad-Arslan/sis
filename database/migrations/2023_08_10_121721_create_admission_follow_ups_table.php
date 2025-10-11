<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdmissionFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admission_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admission_query_id');
            $table->foreign('admission_query_id')->references('id')->on('admission_queries');
            $table->unsignedBigInteger('followup_type_id');
            $table->foreign('followup_type_id')->references('id')->on('follow_up_types');
            $table->date('next_follow_up_date')->nullable();
            $table->string('remarks',1000)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('admission_follow_ups');
    }
}
