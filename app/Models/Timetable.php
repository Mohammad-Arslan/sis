<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','employee_id', 'class_id', 'section_id', 'subject_id', 'start_datetime', 'branch_id', 'end_datetime', 'class_nature'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function class()
    {
        return $this->belongsTo(ComClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }



    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function branches()
    {
        return $this->belongsTo(Branch::class);
    }
}
