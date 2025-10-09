<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewSchoolFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_school_fee_structures', function (Blueprint $table) {
            $table->id();

            $table->string('state_id');
            $table->string('city_id');
            $table->string('academic_year_id');
            $table->string('school_name');
            $table->string('school_address');
            $table->string('class_group_id');
            $table->string('campus_area');
            $table->date('date');
            $table->string('remarks');


            $table->string('nearest_bss_school');
            $table->unsignedInteger('fee_charges');
            $table->unsignedInteger('school_fee');
            $table->unsignedInteger('admission_fee');
            $table->unsignedInteger('security_fee');
            $table->unsignedInteger('registration_fee');
            $table->unsignedInteger('final_fee_charges');
            $table->unsignedInteger('round_final_fee_charges')->nullable();
            $table->enum('fee_status_by_dd', ['Approved', 'Rejected','Pending']);
            $table->date('approval_date');

            $table->timestamps();
        });
    }




    public function down()
    {
        Schema::dropIfExists('new_school_fee_structures');
    }
}
