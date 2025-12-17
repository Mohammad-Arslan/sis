<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexesToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add indexes for commonly filtered columns
            $table->index('branch_id');
            $table->index('company_id');
            $table->index('department_id');
            $table->index('designation_id');
            $table->index('user_id');
            
            // Composite indexes for common filter combinations
            $table->index(['branch_id', 'department_id']);
            $table->index(['branch_id', 'designation_id']);
            $table->index(['company_id', 'branch_id']);
            
            // Index for employee_id for faster lookups
            $table->index('employee_id');
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
            // Drop indexes in reverse order
            $table->dropIndex(['employee_id']);
            $table->dropIndex(['company_id', 'branch_id']);
            $table->dropIndex(['branch_id', 'designation_id']);
            $table->dropIndex(['branch_id', 'department_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['designation_id']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['branch_id']);
        });
    }
}
