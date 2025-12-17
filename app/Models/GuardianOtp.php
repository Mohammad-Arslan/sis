<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuardianOtp extends Model
{
    use HasFactory;
    protected $fillable = [
        'guardian_id',
        'OTP',
        'status'
    ];
}
