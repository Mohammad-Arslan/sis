<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Region extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait, LogsActivity;

    protected $fillable = [
        'region_name',
        'abbreviation',
        'description',
    ];
    protected $dates = [

        'created_at',
        'updated_at',
    ];
}
