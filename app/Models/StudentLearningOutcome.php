<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLearningOutcome extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'teacher_activity',
        'description',
        'lesson_plan_id',
    ];

    public function student_activities()
    {
        return $this->hasMany(StudentActivity::class, 'student_learning_outcome_id', 'id');
    }
}
