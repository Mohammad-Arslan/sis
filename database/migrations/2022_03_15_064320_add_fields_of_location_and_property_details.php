<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsOfLocationAndPropertyDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->string('time_required')->nullable();
            $table->integer('proposed_investment')->default(0);
            $table->integer('public_schools')->default(0);
            $table->integer('private_schools')->default(0);
            $table->string('property_status')->nullable();
            $table->string('financing_plan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('franchise_inquiries', function (Blueprint $table) {
            $table->dropColumn('time_required');
            $table->dropColumn('proposed_investment');
            $table->dropColumn('public_schools');
            $table->dropColumn('private_schools');
            $table->dropColumn('property_status');
            $table->dropColumn('financing_plan');
        });
    }
}
