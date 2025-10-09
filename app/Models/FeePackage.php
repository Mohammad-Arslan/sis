<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePackage extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait;

    protected $fillable = [
        'company_id',
        'branch_id',
        'package_name',
        'abbreviation',
        'description',
        'fee_package_type_id',
        'academic_year_id',
        'active_to',
        'active_from',
        'from_class_id',
        'to_class_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'active_from',
        'active_to',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function student_fee_package()
    {
        return $this->hasMany(StudentFeePackage::class);
    }

    public function fee_packages_fee_charges()
    {
        return $this->hasMany(FeePackagesFeeCharges::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function fee_package_type()
    {
        return $this->belongsTo(FeePackageType::class, 'fee_package_type_id', 'id');
    }

    public function from_class_id()
    {
        return $this->belongsTo(ComClass::class, 'from_class_id', 'id');
    }

    public function to_class_id()
    {
        return $this->belongsTo(ComClass::class, 'to_class_id', 'id');
    }

    public function classes()
    {
        return $this->belongsToMany(ComClass::class, 'class_fee_packages', 'fee_package_id', 'class_id');
    }
}
