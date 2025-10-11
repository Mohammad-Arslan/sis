<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchClassSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_class_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('com_classes');
            $table->unsignedBigInteger('section_id');
            $table->foreign('section_id')->references('id')->on('sections');
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
        Schema::dropIfExists('branch_class_sections');
    }
}
