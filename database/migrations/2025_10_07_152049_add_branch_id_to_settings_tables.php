<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToSettingsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add branch_id to hm_campus_rounds table
        if (!Schema::hasColumn('hm_campus_rounds', 'branch_id')) {
            Schema::table('hm_campus_rounds', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
            });
        }

        // Add branch_id to calls_details table
        if (!Schema::hasColumn('calls_details', 'branch_id')) {
            Schema::table('calls_details', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
            });
        }

        // Add branch_id to repair_maintenances table
        if (!Schema::hasColumn('repair_maintenances', 'branch_id')) {
            Schema::table('repair_maintenances', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
            });
        }

        // Add branch_id to petty_cash_details table
        if (!Schema::hasColumn('petty_cash_details', 'branch_id')) {
            Schema::table('petty_cash_details', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
            });
        }

        // Add branch_id to electricity_meter_readings table
        if (!Schema::hasColumn('electricity_meter_readings', 'branch_id')) {
            Schema::table('electricity_meter_readings', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
            });
        }

        // Add branch_id to generator_infos table
        if (!Schema::hasColumn('generator_infos', 'branch_id')) {
            Schema::table('generator_infos', function (Blueprint $table) {
                $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove branch_id from hm_campus_rounds table
        Schema::table('hm_campus_rounds', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        // Remove branch_id from calls_details table
        Schema::table('calls_details', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        // Remove branch_id from repair_maintenances table
        Schema::table('repair_maintenances', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        // Remove branch_id from petty_cash_details table
        Schema::table('petty_cash_details', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        // Remove branch_id from electricity_meter_readings table
        Schema::table('electricity_meter_readings', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });

        // Remove branch_id from generator_infos table
        Schema::table('generator_infos', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
}
