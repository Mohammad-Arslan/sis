<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class ComClass extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'class_name',
        'abbreviation',
        'description',
        'attendance_type_id',
        'sort',
    ];

    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function branch_class()
    {
        return $this->hasOne(BranchClass::class);
    }

    public function branch_class_section()
    {
        return $this->hasMany(BranchClassSection::class);
    }

    public function student_fee_package()
    {
        return $this->hasMany(StudentFeePackage::class);
    }

    public function fee_packages()
    {
        return $this->belongsToMany(FeePackage::class, 'class_fee_packages', 'class_id', 'fee_package_id');
    }

    public function class_group_class(){
        return $this->hasOne(ClassGroupClass::class,'class_id','id')->where('status','1');
    }

    public function attendance_type(){
        return $this->belongsTo(AttendanceType::class,'attendance_type_id','id');
    }
}
