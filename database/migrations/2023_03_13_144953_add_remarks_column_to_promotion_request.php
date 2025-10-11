<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarksColumnToPromotionRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotion_requests', function (Blueprint $table) {
            $table->text('approval_remarks')->nullable()->after('type');
            $table->text('rejection_remarks')->nullable()->after('type');
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
            $table->dropColumn('approval_remarks');
            $table->dropColumn('rejection_remarks');
        });
    }
}
