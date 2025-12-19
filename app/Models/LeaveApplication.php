<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class LeaveApplication extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $guarded = [];

    protected $dates = [
        'application_date',
        'from_date',
        'to_date',
        'adjustment_date',
        'off_day_work_date',
        'attendance_not_marked_date'
    ];

    public function leaveApplicationType()
    {
        return $this->belongsTo(ApplicationType::class, 'application_type_id', 'id');
    }

    public function leaveType()
    {
        return $this->belongsTo(\App\Models\LeaveType::class, 'leave_type_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
