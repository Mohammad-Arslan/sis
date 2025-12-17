<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMarksFieldsToExtraCurriculamActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extra_curriculam_activities', function (Blueprint $table) {
            $table->enum('marks_type', ['grade', 'marks'])->nullable()->after('remarks');
            $table->string('grade')->nullable()->after('marks_type');
            $table->unsignedInteger('total_marks')->nullable()->after('grade');
            $table->unsignedInteger('obtained_marks')->nullable()->after('total_marks');
            $table->string('attachment')->nullable()->after('obtained_marks');
        });
    }

    public function down()
    {
        Schema::table('extra_curriculam_activities', function (Blueprint $table) {
            $table->dropColumn(['marks_type', 'grade', 'total_marks', 'obtained_marks', 'attachment']);
        });
    }
}
