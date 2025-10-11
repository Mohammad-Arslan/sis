<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProvidentFundDefinition extends Model
{
    use HasFactory;

    protected $fillable = [
        'fiscal_year',
        'employee_contribution_percent',
        'employer_contribution_percent',
        'status',
    ];
}
