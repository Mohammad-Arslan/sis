<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFeePackage extends Model
{
    use HasFactory,SerializeDateTrait,SoftDeletes;

    protected $fillable = [
        'student_id',
        'fee_package_id',
        'fee_concession_id',
        'academic_year_id',
        'com_class_id',
        'section_id',
        'is_valid'
    ];

    protected $dates = ['active_till'];

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function fee_package()
    {
        return $this->belongsTo(FeePackage::class, 'fee_package_id', 'id');
    }

    public function fee_concession()
    {
        return $this->belongsTo(FeeConcession::class, 'fee_concession_id', 'id');
    }
    public function fee_concession_invoice()
    {
        return $this->belongsTo(StudentConcession::class, 'fee_concession_id', 'id')->where('is_valid', 1);
    }
    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'com_class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }
}
