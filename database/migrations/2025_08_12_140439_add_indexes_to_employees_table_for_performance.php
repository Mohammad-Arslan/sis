<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToEmployeesTableForPerformance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add composite indexes for commonly filtered columns
            $table->index(['branch_id', 'company_id'], 'idx_employees_branch_company');
            $table->index(['branch_id', 'department_id'], 'idx_employees_branch_department');
            $table->index(['branch_id', 'designation_id'], 'idx_employees_branch_designation');
            $table->index(['company_id', 'department_id'], 'idx_employees_company_department');
            
            // Add indexes for individual columns used in filtering
            $table->index('city_id', 'idx_employees_city');
            $table->index('created_at', 'idx_employees_created_at');
            
            // Add index for user relationship queries
            $table->index('user_id', 'idx_employees_user');
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
            $table->dropIndex('idx_employees_branch_company');
            $table->dropIndex('idx_employees_branch_department');
            $table->dropIndex('idx_employees_branch_designation');
            $table->dropIndex('idx_employees_company_department');
            $table->dropIndex('idx_employees_city');
            $table->dropIndex('idx_employees_created_at');
            $table->dropIndex('idx_employees_user');
        });
    }
}
