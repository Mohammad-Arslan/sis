<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcademicYearIdIntoAdmissionQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admission_queries', function (Blueprint $table) {
            $table->unsignedBigInteger('academic_year_id')->after('source_id')->nullable();
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
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
            $table->dropForeign(['admission_queries_academic_year_id_foreign']);
            $table->dropColumn('academic_year_id');
        });
    }
}
