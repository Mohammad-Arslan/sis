<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LessonPlan extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'branch_id',
        'academic_year_id',
        'com_class_id',
        'subject_id',
        'term_id',
        'week_id',
        'day',
        'state_id',
        'topic',
        'lesson_date',
        'chapter',
        'theme',
        'bocc_link',
        'approval_status',
        'created_by',
        'approved_by',
        'taught_date_from',
        'taught_date_to',
        'evaluation_of_student',
        'evaluation_of_teacher',
        'language',
    ];
    protected $dates = [
        'lesson_date',
        'taught_date_from',
        'taught_date_to',
        'created_at',
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

    public function week()
    {
        return $this->belongsTo(Week::class, 'week_id', 'id');
    }

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'com_class_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function student_learning_outcomes()
    {
        return $this->hasMany(StudentLearningOutcome::class, 'lesson_plan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function creater()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'lesson_plan_sections', 'lesson_plan_id', 'section_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }
}
