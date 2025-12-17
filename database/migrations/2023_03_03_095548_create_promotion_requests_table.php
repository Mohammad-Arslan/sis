<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prev_branch_id')->constrained('branches');
            $table->foreignId('prev_class_id')->constrained('com_classes');
            $table->foreignId('prev_section_id')->constrained('sections');
            $table->foreignId('prev_branch_class_section_id')->constrained('branch_class_sections');
            $table->foreignId('prev_academic_year_id')->constrained('academic_years');

            $table->foreignId('cur_branch_id')->constrained('branches');
            $table->foreignId('cur_class_id')->constrained('com_classes');
            $table->foreignId('cur_section_id')->constrained('sections');
            $table->foreignId('cur_branch_class_section_id')->constrained('branch_class_sections');
            $table->foreignId('cur_academic_year_id')->constrained('academic_years');

            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('rejected_by')->nullable()->constrained('users');

            $table->string('type');

            $table->timestamp('approved_date')->nullable();
            $table->timestamp('rejected_date')->nullable();
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
        Schema::dropIfExists('promotion_requests');
    }
}
