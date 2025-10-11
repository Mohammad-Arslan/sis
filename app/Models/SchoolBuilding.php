<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class SchoolBuilding extends Model
{
    use HasFactory,SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'city_id',
        'location',
        'area_size',
        'inquirer_id',
        'remarks'
    ];

    public function inquirer()
    {
        return $this->belongsTo(FranchiseInquiryOld::class, 'inquirer_id', 'id');
    }
    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
}
