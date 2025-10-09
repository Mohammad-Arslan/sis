<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsIntoAdmissionQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_queries', function (Blueprint $table) {
            // $table->double('inquiry_number')->after('id')->nullable();
            // $table->unsignedBigInteger('inquiry_type_id')->after('parent_contact')->default(1);
            // $table->foreign('inquiry_type_id')->references('id')->on('inquiries_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admission_queries', function (Blueprint $table) {
            // $table->dropColumn('inquiry_number');
            // $table->dropForeign('admission_queries_inquiry_type_id_foreign');
            // $table->dropColumn('inquiry_type_id');
        });
    }
}
