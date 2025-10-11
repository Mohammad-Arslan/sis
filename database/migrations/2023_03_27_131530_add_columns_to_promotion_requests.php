<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPromotionRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_requests', function (Blueprint $table) {
            $table->string('is_promotion')->after('type')->nullable();
            $table->unsignedBigInteger('cur_class_id')->nullable()->change();
            $table->unsignedBigInteger('cur_section_id')->nullable()->change();
            $table->unsignedBigInteger('cur_branch_class_section_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotion_requests', function (Blueprint $table) {
            $table->dropColumn('is_promotion');
            $table->unsignedBigInteger('cur_class_id')->nullable(false)->change();
            $table->unsignedBigInteger('cur_section_id')->nullable(false)->change();
            $table->unsignedBigInteger('cur_branch_class_section_id')->nullable(false)->change();
        });
    }
}
