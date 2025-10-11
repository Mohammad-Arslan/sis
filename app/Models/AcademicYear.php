<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class AcademicYear extends Model
{
    use HasFactory,SerializeDateTrait;

    protected $fillable = [
        'title',
        'active'
     ];

     protected $dates = [
         'created_at',
         'updated_at',
     ];

    public function student_fee_package()
    {
        return $this->hasMany(StudentFeePackage::class);
    }

    public function branch_academic_year()
    {
        return $this->hasMany(BranchAcademicYear::class);
    }

    public function academic_classes()
    {
        return $this->hasMany(AcademicClass::class);
    }

    public function class_students()
    {
        return $this->hasMany(ClassStudent::class);
    }
}
