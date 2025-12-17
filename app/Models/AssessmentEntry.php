<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssessmentEntry extends Model
{
    use HasFactory, SerializeDateTrait,SoftDeletes;

    protected $fillable = [
        'academic_year_id',
        'term_id',
        'branch_id',
        'class_id',
        'section_id',
        'subject_id',
        'assessment_level_one_id',
        'assessment_level_two_id',
        'assessment_level_three_id',
        'marks_type',
        'grade_marks',
        'assessment_weightage',
        'assessment_date',
        'remarks',
    ];

    protected $dates = [
        'assessment_date',
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

    public function term(){
        return $this->belongsTo(Term::class,'term_id','id');
    }

    public function com_class(){
        return $this->belongsTo(ComClass::class,'class_id','id');
    }

    public function section(){
        return $this->belongsTo(Section::class,'section_id','id');
    }

    public function subject(){
        return $this->belongsTo(Subject::class,'subject_id','id');
    }

    public function assessment_level_one(){
        return $this->belongsTo(AssessmentLevel::class,'assessment_level_one_id','id');
    }

    public function assessment_level_two(){
        return $this->belongsTo(AssessmentLevel::class,'assessment_level_two_id','id');
    }

    public function assessment_level_three(){
        return $this->belongsTo(AssessmentLevel::class,'assessment_level_three_id','id');
    }

    public function student_assessment_marks(){
        return $this->hasMany(StudentAssessmentMark::class,'assessment_entry_id','id');
    }
}
