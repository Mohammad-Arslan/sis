<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeBookHistory extends Model
{
    use HasFactory,SerializeDateTrait, SoftDeletes;

    protected $fillable = [
        'branch_class_section_id',
        'student_id',
        'student_behaviour_skill_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function branch_class_section()
    {
        return $this->belongsTo(BranchClassSection::class, 'branch_class_section_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function student_behaviour_skill()
    {
        return $this->belongsTo(StudentBehaviourSkill::class,'student_behaviour_skill_id','id');
    }
}
