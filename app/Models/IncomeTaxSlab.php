<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeTaxSlab extends Model
{
    use HasFactory;
    protected $fillable = ['fiscal_year', 'min_salary', 'max_salary', 'tax_percent', 'fixed_amount', 'status'];
}
