<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeCharge extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'academic_year_id',
        'company_id',
        'branch_id',
        'fee_charges_type_id',
        'amount',
        'is_discountable',
        'is_refundable'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function fee_charges_type()
    {
        return $this->belongsTo(FeeChargesType::class, 'fee_charges_type_id', 'id');
    }

    public function fee_packages_fee_charges()
    {
        return $this->hasMany(FeePackagesFeeCharges::class);
    }
    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }
    public function student_concessions()
    {
        return $this->hasMany(StudentConcession::class, 'fee_charge_id', 'id');
    }
}
