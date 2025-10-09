<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmployeeSalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'house_rent_allowance',
        'medical_allowance',
        'transport_allowance',
        'other_allowances',
        'gross_salary',
        'effective_from',
        'effective_to',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'house_rent_allowance' => 'decimal:2',
        'medical_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'is_active' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateGrossSalary()
    {
        return $this->basic_salary + $this->house_rent_allowance + 
               $this->medical_allowance + $this->transport_allowance + 
               $this->other_allowances;
    }

    public function isEffectiveForDate($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        return $date->between($this->effective_from, $this->effective_to ?? Carbon::now()->addYear());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDate($query, $date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        
        return $query->where('effective_from', '<=', $date)
                    ->where(function($q) use ($date) {
                        $q->whereNull('effective_to')
                          ->orWhere('effective_to', '>=', $date);
                    });
    }
}
