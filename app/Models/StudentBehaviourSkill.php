<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentBehaviourSkill extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'term_id',
        'branch_id',
        'class_id',
        'section_id',
        'subject_id',
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

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function student_behaviour_skill_marks()
    {
        return $this->hasMany(StudentBehaviourSkillMark::class, 'student_behaviour_skill_id', 'id');
    }

    public function student_behaviour_skill_remark()
    {
        return $this->hasOne(StudentBehaviourSkillRemark::class, 'student_behaviour_skill_id', 'id');
    }



    public function student_behaviour_skill_remarks()
    {
        return $this->hasMany(StudentBehaviourSkillRemark::class, 'student_behaviour_skill_id', 'id');
    }
}
