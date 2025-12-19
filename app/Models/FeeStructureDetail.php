<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeStructureDetail extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'new_school_fee_structure_id',
    'nearest_bss_school',
    'fee_charges',
    'school_fee',
    'academic_year_id',
    'admission_fee',
    'security_fee',
    'registration_fee',
    'final_fee_charges',
    'round_final_fee_charges',
    'fee_status_by_dd',
    'approval_date',
    'remarks',
    'status_approval_date'
    ];

    public function new_school_fee_structure()
    {
        return $this->belongsTo(NewSchoolFeeStructure::class, 'new_school_fee_structure_id', 'id');
    }

    public function academic_years()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
