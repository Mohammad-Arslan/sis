<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnsFromEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('CNIC');
            $table->dropColumn('gender');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('emp_email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign('employees_user_id_foreign');
            $table->dropColumn('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('CNIC');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('emp_email');
        });
    }
}
