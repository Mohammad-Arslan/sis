<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhancePayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            // Salary Breakdown
            $table->decimal('basic_salary', 12, 2)->after('year')->default(0);
            $table->decimal('permanent_allowances_total', 12, 2)->after('basic_salary')->default(0);
            $table->decimal('temporary_allowances_total', 12, 2)->after('permanent_allowances_total')->default(0);
            $table->decimal('taxable_gross_salary', 12, 2)->after('temporary_allowances_total')->default(0)->comment('Basic + Permanent Allowances (for tax calculation)');
            // Note: gross_salary already exists (attendance-adjusted total gross)
            
            // Deductions Breakdown
            $table->decimal('total_deductions', 12, 2)->after('gross_salary')->default(0);
            $table->decimal('absent_deduction', 12, 2)->after('total_deductions')->default(0);
            $table->decimal('late_deduction', 12, 2)->after('absent_deduction')->default(0);
            $table->decimal('provident_fund_employee', 12, 2)->after('late_deduction')->default(0);
            $table->decimal('provident_fund_employer', 12, 2)->after('provident_fund_employee')->default(0);
            $table->decimal('income_tax', 12, 2)->after('provident_fund_employer')->default(0);
            $table->decimal('other_deductions_total', 12, 2)->after('income_tax')->default(0)->comment('EOBI, loans, etc.');
            
            // Attendance Data
            $table->integer('present_days')->after('other_deductions_total')->default(0);
            $table->integer('absent_days')->after('present_days')->default(0);
            $table->integer('late_minutes')->after('absent_days')->default(0);
            $table->integer('extra_minutes')->after('late_minutes')->default(0);
            $table->integer('approved_leaves')->after('extra_minutes')->default(0);
            $table->integer('total_working_days')->after('approved_leaves')->default(30)->comment('For the month');
            
            // Tax Information
            $table->string('tax_slab_applied')->nullable()->after('total_working_days')->comment('Tax slab information');
            $table->decimal('tax_exemption_applied', 12, 2)->after('tax_slab_applied')->default(0);
            
            // Additional Notes
            $table->text('notes')->nullable()->after('tax_exemption_applied');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn([
                'basic_salary',
                'permanent_allowances_total',
                'temporary_allowances_total',
                'taxable_gross_salary',
                'total_deductions',
                'absent_deduction',
                'late_deduction',
                'provident_fund_employee',
                'provident_fund_employer',
                'income_tax',
                'other_deductions_total',
                'present_days',
                'absent_days',
                'late_minutes',
                'extra_minutes',
                'approved_leaves',
                'total_working_days',
                'tax_slab_applied',
                'tax_exemption_applied',
                'notes',
            ]);
        });
    }
}


