<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingOtp extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'OTP'
    ];

    
    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
