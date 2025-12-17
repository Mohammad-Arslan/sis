<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
class VisitDetail extends Model
{
    use HasFactory,SerializeDateTrait,SoftDeletes;

    protected $fillable = [
        'user_id',
        'campus_office_id',
        'branch_id',
        'from_city_id',
        'to_city_id',
        'total_duration',
        'travel_on',
        'return_on',
        'travel_mode',
        'purpose',
        'remarks',
        'approval_status',
        'approved_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function campus()
    {
        return $this->belongsTo(CampusOfficeType::class, 'campus_office_id', 'id');
    }

    public function fromCity()
    {
        return $this->belongsTo(City::class, 'from_city_id', 'id');
    }

    public function toCity()
    {
        return $this->belongsTo(City::class, 'to_city_id', 'id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by','id');
    }
}
