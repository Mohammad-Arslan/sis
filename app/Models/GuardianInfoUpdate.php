<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuardianInfoUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'guardian_id',
        'update_type',
        'update_value',
        'student_id',
        'status'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date:d-m-Y H:m',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
