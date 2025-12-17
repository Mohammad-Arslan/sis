<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeLeaveQuota extends Model
{
    use HasFactory,SerializeDateTrait;
    protected $guarded = [];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function leaveType()
    {
        return $this->hasOne(LeaveType::class, 'id', 'leave_type_id');
    }

    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }
}
