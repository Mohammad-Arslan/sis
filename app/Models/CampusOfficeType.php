<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampusOfficeType extends Model
{
    use HasFactory;
    use SerializeDateTrait;
    use SoftDeletes;

    protected $fillable = [
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
