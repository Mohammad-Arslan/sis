<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class FeeConcession extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'company_id',
        'branch_id',
        'fee_concession_type_id',
        'academic_year_id',
        'concession_percentage',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function student_fee_package()
    {
        return $this->hasMany(StudentFeePackage::class);
    }

    public function fee_concession_type()
    {
        return $this->belongsTo(FeeConcessionType::class, 'fee_concession_type_id', 'id');
    }

    public function child_concession_type()
    {
        return $this->belongsTo(FeeConcessionType::class, 'fee_concession_type_id', 'id')->whereIn('id', [8,9]);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }
}
