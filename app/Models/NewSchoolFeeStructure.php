<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewSchoolFeeStructure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'state_id',
        'city_id',
        'academic_year_id',
        'school_name',
        'school_address',
        'class_group_id',
        'campus_area',
        'date',
        'remarks',

    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function academic_years()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function class_group()
    {
        return $this->belongsTo(ClassGroup::class, 'class_group_id');
    }

    public function new_fee_structure_details()
    {
        return $this->hasMany(FeeStructureDetail::class, 'new_school_fee_structure_id', 'id');
    }
}
