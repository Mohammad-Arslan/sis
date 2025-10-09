<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        
        // Salary Breakdown
        'basic_salary',
        'permanent_allowances_total',
        'temporary_allowances_total',
        'taxable_gross_salary',
        'gross_salary',
        
        // Deductions Breakdown
        'total_deductions',
        'absent_deduction',
        'late_deduction',
        'provident_fund_employee',
        'provident_fund_employer',
        'income_tax',
        'other_deductions_total',
        
        // Attendance Data
        'present_days',
        'absent_days',
        'late_minutes',
        'extra_minutes',
        'approved_leaves',
        'total_working_days',
        
        // Tax Information
        'tax_slab_applied',
        'tax_exemption_applied',
        
        // Additional
        'notes',
        'net_salary',
        'status',
        'processed_by',
        'processed_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }
} 