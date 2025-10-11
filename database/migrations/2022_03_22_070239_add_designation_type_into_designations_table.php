<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesignationTypeIntoDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->unsignedBigInteger('type_id')->after('company_id')->nullable();
            $table->foreign('type_id')->references('id')->on('designation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('designations', function (Blueprint $table) {
            $table->dropForeign('designations_type_id_foreign');
            $table->dropColumn('type_id');
        });
    }
}
