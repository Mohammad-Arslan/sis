<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class FeeConcessionType extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'name',
        'abbreviation',
        'description'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
