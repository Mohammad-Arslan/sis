<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSystemNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            $table->unsignedBigInteger('country_id')->after('branch_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unsignedBigInteger('state_id')->after('country_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states');
            $table->unsignedBigInteger('user_id')->after('state_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('branch_id')->nullable()->change();
            $table->text('message')->nullable()->change();
            $table->string('email_header')->nullable()->after('message');
            $table->text('email_body')->nullable()->after('email_header');
            $table->string('email_salutation')->nullable()->after('email_body');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            $table->dropForeign('system_notifications_country_id_foreign');
            $table->dropForeign('system_notifications_state_id_foreign');
            $table->dropForeign('system_notifications_user_id_foreign');
            $table->dropColumn('country_id');
            $table->dropColumn('state_id');
            $table->dropColumn('user_id');
            $table->dropColumn('email_header');
            $table->dropColumn('email_body');
            $table->dropColumn('email_salutation');
            $table->unsignedBigInteger('branch_id')->change();
            $table->text('message')->change();
        });
    }
}
