<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class LeaveType extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $guarded = [];
}
