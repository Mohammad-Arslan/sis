<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentBehaviourSkillRemark extends Model
{
    use HasFactory, SerializeDateTrait,SoftDeletes;

    protected $fillable = [
        'student_behaviour_skill_id',
        'student_id',
        'teacher_comments',
        'schoolhead_comments',
        'is_promoted',
        'parent_meeting_attended',
    ];

    public function student(){
        return $this->belongsTo(Student::class,'student_id','id');
    }

    public function student_behaviour_skill(){
        return $this->belongsTo(StudentBehaviourSkill::class,'student_behaviour_skill_id','id');
    }
}
