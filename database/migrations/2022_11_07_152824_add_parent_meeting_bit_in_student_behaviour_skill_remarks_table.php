<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentMeetingBitInStudentBehaviourSkillRemarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_behaviour_skill_remarks', function (Blueprint $table) {
            $table->integer('parent_meeting_attended')->after('is_promoted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_behaviour_skill_remarks', function (Blueprint $table) {
            $table->dropColumn('parent_meeting_attended');
        });
    }
}
