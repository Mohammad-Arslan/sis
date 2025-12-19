<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassTeacher extends Model
{
    use SoftDeletes;
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'academic_year_id',
        'branch_class_section_id',
        'employee_id',
        'teacher_type_id',
        'subject_id',
        'is_valid',
        'active_till'
    ];

    protected $dates = ['active_till'];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function branch_class_section()
    {
        return $this->belongsTo(BranchClassSection::class, 'branch_class_section_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function teacher_type()
    {
        return $this->belongsTo(TeacherType::class, 'teacher_type_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
