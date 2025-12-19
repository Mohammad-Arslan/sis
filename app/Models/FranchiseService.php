<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FranchiseService extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'inquiry_id',
        'from_date',
        'to_date',
        'organisation',
        'designation',
        'responsibilities'
    ];
    protected $dates = [
        'from_date',
        'to_date'
    ];
}
