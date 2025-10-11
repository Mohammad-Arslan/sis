<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonPlanAttainmentTarget extends Model
{
    use HasFactory;

    protected $table = 'lesson_plan_attainment_targets';

    public function lessonPlan()
    {
        return $this->belongsTo(LessonPlanGenerate::class, 'lesson_plan_id', 'id');
    }

    public function attainmentTarget()
    {
        return $this->belongsTo(CurriculumAttainmentTarget::class, 'attainment_target_id', 'id');
    }
}
