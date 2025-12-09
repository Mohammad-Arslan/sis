<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPreviousSchool extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'school_name',
        'description'
    ];

    public function student_previous_schools()
    {
        return $this->belongsTo(Student::class, 'previous_school_id', 'id');
    }
}
