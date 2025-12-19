<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAssessmentMark extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $fillable = [
        'assessment_entry_id',
        'student_id',
        'obtained_marks_grades',
        'out_of',
        'overall_grade',
        'remarks',
    ];

    public function assessment_entry()
    {
        return $this->belongsTo(AssessmentEntry::class, 'assessment_entry_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
