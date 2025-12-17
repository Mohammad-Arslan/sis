<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait;

    protected $fillable = [
        'subject_name',
        'abbreviation',
        'sort_no',
        'is_academic',
        'language_id',
        'subject_type',
        'subject_group_id',
    ];
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function subject_group()
    {
        return $this->belongsTo(SubjectGroup::class, 'subject_group_id', 'id');
    }
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    public function class_student_subject()
    {
        return $this->hasOne(ClassStudentSubject::class, 'subject_id', 'id');
    }

    public function class_student_subjects()
    {
        return $this->hasMany(ClassStudentSubject::class, 'subject_id', 'id');
    }
}
