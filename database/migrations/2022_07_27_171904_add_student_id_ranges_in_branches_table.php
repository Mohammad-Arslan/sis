<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStudentIdRangesInBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->double('student_id_from')->after('closing_reason')->nullable();
            $table->double('student_id_to')->after('student_id_from')->nullable();
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
            $table->dropColumn('student_id_from');
            $table->dropColumn('student_id_to');
        });
    }
}
