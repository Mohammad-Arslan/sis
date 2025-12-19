<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FranchiseApplicationBdVisit extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'franchise_application_id',
        'visit_by',
        'forward_to',
        'forwarded_date',
        'approved_by',
        'approval_date',
        'visit_date',
        'bd_status',
        'school_type',
        'school_configuration',
        'proposed_school_name',
        'area_population_half_km_radius',
        'area_population_one_km_radius',
        'area_population_two_km_radius',
        'site_address',
        'site_purpose',
        'remarks',
    ];

    protected $dates = [
        'visit_date',
        'forwarded_date',
        'approval_date'
    ];

    public function visit_by()
    {
        return $this->belongsTo(Employee::class, 'visit_by', 'id');
    }

    public function forward_to()
    {
        return $this->belongsTo(Employee::class, 'forward_to', 'id');
    }

    public function approved_by()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'id');
    }

    public function school_type()
    {
        return $this->belongsTo(ClassGroup::class, 'school_type', 'id');
    }

    public function getBdStatusAttribute($value)
    {
        return ($value == 'pending' ? 'Pending' : ($value == 'approved' ? 'Approved' : ($value == 'not_approved' ? 'Not Approved' :
            $value)));
    }

    public function franchise_application()
    {
        return $this->belongsTo(FranchiseApplication::class, 'franchise_application_id', 'id');
    }
}
