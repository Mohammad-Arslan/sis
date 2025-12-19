<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentSubjectRemark extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $fillable = [
        'subject_remark_id',
        'student_id',
        'remarks',
        'status'
    ];

    public function subject_remark()
    {
        return $this->belongsTo(SubjectRemark::class, 'subject_remark_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
