<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAttendance extends Model
{
    use HasFactory, SerializeDateTrait, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'branch_class_section_id',
        'student_id',
        'employee_id',
        'subject_id',
        'attendance_status_id',
        'attendance_date',
        'online_attendance',
    ];

    protected $dates = ['attendance_date'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function attendance_status()
    {
        return $this->belongsTo(AttendanceStatus::class, 'attendance_status_id', 'id');
    }

    protected function attendanceStatusId(): Attribute
    {
        return new Attribute(
            get: fn ($value) => ($value == 1 ? '<span class="badge bg-primary">Present</span>' : ($value == 2 ? '<span class="badge bg-danger">Absent</span>' : ($value == 3 ? '<span class="badge bg-info">Leave</span>' : ($value == 4 ? '<span class="badge bg-warning">Tardy</span>' : ($value == 5 ? '<span class="badge bg-success">Exempted</span>' :
                $value)))))
        );
    }
}
