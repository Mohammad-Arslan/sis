<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_id',
        'type',
        'label',
        'amount',
        'is_permanent',
        'is_taxable',
        'category',
        'description',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
