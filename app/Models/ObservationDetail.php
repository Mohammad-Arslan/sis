<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObservationDetail extends Model
{
    use SoftDeletes;

    protected $table = 'observation_details';

    protected $fillable = [
        'teacher_observation_id',
        'academic_years_id',
        'class_id',
        'section_id',
        'subject_id',
        'nextobservationdate',
        'part_of_period_observed',
        'promoting_interest_rating',
        'planning_preparing_rating',
        'maintaining_relation_rating',
        'assessment_application_rating',
        'catering_diversity_rating',
        'observation_date',

    ];

    public function observation()
    {
        return $this->belongsTo(TeacherObservation::class, 'teacher_observation_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_years_id');
    }

    public function sections()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function com_classes()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(ClassSubject::class, 'subject_id');
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'observation_detail_id');
    }
}
