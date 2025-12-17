<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class InquiriesType extends Model
{
    use HasFactory,SerializeDateTrait,SoftDeletes;

    protected $fillable = [
        'type',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
