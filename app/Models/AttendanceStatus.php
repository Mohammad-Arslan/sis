<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class AttendanceStatus extends Model
{
    use HasFactory;
    use SerializeDateTrait;
}
