<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassStudent extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'branch_class_section_id',
        'student_id',
        'is_valid',
        'active_till',
        'is_promoted'
    ];

    protected $dates = ['active_till'];

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function academic_years()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function branch_class_sections()
    {
        return $this->belongsTo(BranchClassSection::class, 'branch_class_section_id', 'id');
    }

    public function class_student_subjects()
    {
        return $this->hasMany(ClassStudentSubject::class, 'class_student_id', 'id');
    }

    public function student_attendance()
    {
        return $this->hasOne(StudentAttendance::class, 'student_id', 'student_id');
    }

    public function student_invoices()
    {
        return $this->hasMany(StudentInvoice::class, 'student_id', 'student_id');
    }
}
