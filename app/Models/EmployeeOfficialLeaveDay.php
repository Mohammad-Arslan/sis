<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeOfficialLeaveDay extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'employee_id',
        'working_day_id',
        'official_leave_id',
        'leave_date',
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

    public function official_leave()
    {
        return $this->belongsTo(OfficialLeaveDay::class, 'official_leave_id','id');
    }
}
