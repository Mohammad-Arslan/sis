<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeWorkingDay extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'employee_id',
        'working_day_id',
        'working_shift_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id','id');
    }

    public function working_day()
    {
        return $this->belongsTo(WorkingDay::class, 'working_day_id','id');
    }

    public function working_shift()
    {
        return $this->belongsTo(WorkingShift::class, 'working_shift_id','id');
    }

    public function workingShift()
    {
        return $this->belongsTo(\App\Models\WorkingShift::class, 'working_shift_id');
    }
}
