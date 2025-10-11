<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficialLeaveDay extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'start_time',
        'end_time',
        'status',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
