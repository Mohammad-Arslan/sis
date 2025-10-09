<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFranchiseInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('franchise_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('appl_name', 50)->nullable();
            $table->string('CNIC', 15)->nullable();
            $table->string('personal_address', 200)->nullable();
            $table->string('primary_mobile_no', 15)->nullable();
            $table->string('secondary_mobile_no', 15)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('other_profession', 200)->nullable();
            $table->string('already_franchise', 1)->default('N');
            $table->string('remarks', 200)->nullable();
            $table->string('franchise_type', 200)->nullable();
            $table->string('campus_location', 50)->nullable();
            $table->string('entry_ip', 50)->nullable();
            $table->string('status', 1)->default('P');
            $table->integer('entered_by')->default('999950');
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
        Schema::dropIfExists('franchise_inquiries');
    }
}
