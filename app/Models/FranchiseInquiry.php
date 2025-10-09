<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseInquiry extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'full_name',
        'CNIC',
        'email',
        'address',
        'city_id',
        'contact_no_1',
        'contact_no_2',
        'current_occupation',
        'led_franchise',
        'franchise_name',
        'franchise_interest',
        'area_location',
        'source_id',
        'call_center_agent',
        'inquiry_status',
        'meeting_with_bd',
        'call_back',
        'launching_year',
        'land_area',
        'covered_area',
        'general_remarks',
        'inquiry_remarks',
        'meeting_remarks',
        'experience',
        'expected_franchise_address',
        'initiated_by',
        'last_updated_by',
        'created_by'
    ];

    protected $dates = [
        'recommended_date',
        'approved_date',
        'forwarded_date'
    ];

    public function city(){
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function source(){
        return $this->belongsTo(Source::class,'source_id','id');
    }

    public static function store_update_franchise_inquiry($request,$franchisesInquiry = null,$type = 'create'){

        if ($type == 'create')
            $response = self::create($request);
        else if ($type == 'update'){
            $franchisesInquiry->update($request);
            $response = $franchisesInquiry->refresh();
        }

        return $response;
    }
}
