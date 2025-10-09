<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class ContactInformation extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'address',
        'mobile',
        'phone',
        'email',
        'fax',
        'country_id',
        'state_id',
        'city_id',
        'town_id'
    ];

    public function contact_informationable()
    {
        return $this->morphTo();
    }

    public function state()
    {
        return $this->belongsTo(State::class,'state_id','id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id','id');
    }
}
