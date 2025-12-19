<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class AcademicYear extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use LogsActivity;

    protected $fillable = [
        'title',
        'active'
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
