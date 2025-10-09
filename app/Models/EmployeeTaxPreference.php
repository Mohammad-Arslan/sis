<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTaxPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'tax_slab_id',
        'tax_exemption_amount',
        'apply_tax',
        'notes'
    ];

    protected $casts = [
        'tax_exemption_amount' => 'decimal:2',
        'apply_tax' => 'boolean'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function taxSlab()
    {
        return $this->belongsTo(IncomeTaxSlab::class);
    }

    public function scopeActive($query)
    {
        return $query->where('apply_tax', true);
    }
}
