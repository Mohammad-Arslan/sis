<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->unsignedBigInteger('fee_period_id')->after('region_id')->nullable();
            $table->unsignedBigInteger('building_type_id')->after('fee_period_id')->nullable();
            $table->string('build_purpose', 100)->after('building_type_id')->nullable();
            $table->string('website', 255)->after('build_purpose')->nullable();
            $table->string('instagram', 255)->after('website')->nullable();
            $table->string('twitter', 255)->after('instagram')->nullable();
            $table->string('branch_banner', 255)->after('twitter')->nullable();
            $table->foreign('fee_period_id')->references('id')->on('fee_periods');
            $table->foreign('building_type_id')->references('id')->on('building_types');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->dropForeign('branches_fee_period_id_foreign');
            $table->dropColumn('fee_period_id');
            $table->dropForeign('branches_building_type_id_foreign');
            $table->dropColumn('building_type_id');
            $table->dropColumn('build_purpose');
            $table->dropColumn('website');
            $table->dropColumn('instagram');
            $table->dropColumn('twitter');
            $table->dropColumn('branch_banner');
        });
    }
}
