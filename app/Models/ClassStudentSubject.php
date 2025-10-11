<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class ClassStudentSubject extends Model
{
    use HasFactory,SerializeDateTrait;

    protected $fillable = [
        'class_student_id',
        'subject_id',
        'is_valid',
        'active_till'
    ];

    public function subject(){
        return $this->belongsTo(Subject::class,'subject_id','id');
    }

    public function class_student(){
        return $this->belongsTo(ClassStudent::class,'class_student_id','id');
    }
}
