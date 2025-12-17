<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectRemark extends Model
{
    use HasFactory, SerializeDateTrait, SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'created_by_id',
        'term_id',
        'branch_id',
        'class_id',
        'section_id',
        'subject_id',
        'remarks_date',
    ];

    protected $dates = [
        'remarks_date',
        'updated_at',
        'updated_at',
    ];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id', 'id');
    }

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function student_subject_remarks()
    {
        return $this->hasMany(StudentSubjectRemark::class, 'subject_remark_id', 'id');
    }
}
