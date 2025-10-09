<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFeePackages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_year_id')->nullable()->after('branch_id');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('fee_package_type_id')->nullable()->after('academic_year_id');
            $table->foreign('fee_package_type_id')->references('id')->on('fee_package_types');
            $table->dropColumn('active_from');
            $table->dropColumn('active_to');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_packages', function (Blueprint $table) {
            $table->dropForeign('fee_packages_academic_year_id_foreign');
            $table->dropColumn('academic_year_id');
            $table->dropForeign('fee_packages_fee_package_type_id_foreign');
            $table->dropColumn('fee_package_type_id');
            $table->date('active_from')->nullable();
            $table->date('active_to')->nullable();
        });
    }
}
