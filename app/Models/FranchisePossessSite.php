<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchisePossessSite extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'inquiry_id',
        'ownership',
        'lease_rental',
        'from_date',
        'to_date',
        'total_area',
        'tile_carpet',
        'location'
    ];

    protected $dates = [
        'from_date',
        'to_date'
    ];
}
