<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDependent extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait;

    protected $fillable = [
        'employee_id',
        'dependent_name',
        'dependent_cnic',
        'dependent_relationship',
        'dependent_dob'
    ];

    protected $dates = [
        'dependent_dob'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id', 'employee_id');
    }
}
