<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDeductionPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'deduction_type_id',
        'amount',
        'type',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deductionType()
    {
        return $this->belongsTo(DeductionType::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function calculateAmount($grossSalary = 0)
    {
        if ($this->type === 'percentage') {
            return $grossSalary * ($this->amount / 100);
        }
        
        return $this->amount;
    }
}
