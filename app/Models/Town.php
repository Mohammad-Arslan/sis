<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Town extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait, LogsActivity;
    protected $fillable = [
        'town_name',
        'abbreviation',
        'city_id',
    ];
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function cities(){
        return $this->belongsTo(City::class,'city_id','id');
    }
}
