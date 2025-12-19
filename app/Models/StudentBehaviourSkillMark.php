<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentBehaviourSkillMark extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'student_behaviour_skill_id',
        'skill_id',
        'general_behaviour_id',
        'student_id',
        'grade',
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'skill_id', 'id');
    }

    public function behaviour()
    {
        return $this->belongsTo(GeneralBehaviour::class, 'general_behaviour_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function student_behaviour_skill()
    {
        return $this->belongsTo(StudentBehaviourSkill::class, 'student_behaviour_skill_id', 'id');
    }

    public function student_behaviour_skill_for_report()
    {
        return $this->belongsTo(StudentBehaviourSkill::class, 'student_behaviour_skill_id', 'id')->where('academic_year_id', 2)->where('term_id', 2);
    }
}
