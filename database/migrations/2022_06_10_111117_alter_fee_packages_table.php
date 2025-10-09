<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFeePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_packages', function (Blueprint $table) {
            $table->unsignedBigInteger('from_class_id')->nullable()->after('fee_package_type_id');
            $table->foreign('from_class_id')->references('id')->on('com_classes');
            $table->unsignedBigInteger('to_class_id')->nullable()->after('from_class_id');
            $table->foreign('to_class_id')->references('id')->on('com_classes');
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
            $table->dropForeign('fee_packages_from_class_id_foreign');
            $table->dropColumn('from_class_id');
            $table->dropForeign('fee_packages_to_class_id_foreign');
            $table->dropColumn('to_class_id');
        });
    }
}
