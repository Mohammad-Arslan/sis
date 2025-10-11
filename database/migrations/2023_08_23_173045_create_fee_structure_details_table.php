<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeeStructureDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fee_structure_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('new_school_fee_structure_id')->nullable()->constrained('new_school_fee_structures');
            $table->string('nearest_bss_school')->nullable();
            $table->unsignedInteger('fee_charges')->nullable();
            $table->unsignedInteger('school_fee')->nullable();
            $table->string('academic_year_id')->nullable();

            $table->unsignedInteger('admission_fee')->nullable();
            $table->unsignedInteger('security_fee')->nullable();
            $table->unsignedInteger('registration_fee')->nullable();
            $table->unsignedInteger('final_fee_charges')->nullable();
            $table->unsignedInteger('round_final_fee_charges')->nullable();
            $table->enum('fee_status_by_dd', ['Approved', 'Rejected', 'Pending'])->nullable();
            $table->date('approval_date')->nullable();
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
        Schema::dropIfExists('fee_structure_details');
    }
}
