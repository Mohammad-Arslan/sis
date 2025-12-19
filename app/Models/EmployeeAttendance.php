<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAttendance extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $guarded = [];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function employees()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
