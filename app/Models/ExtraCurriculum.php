<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraCurriculum extends Model
{
    use HasFactory;

    protected $table = 'extra_curriculam_activities';
    protected $fillable = ['student_id', 'activity_name', 'activity_type', 'activity_date', 'remarks', 'marks_type', 'grade', 'total_marks', 'obtained_marks', 'attachment'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
