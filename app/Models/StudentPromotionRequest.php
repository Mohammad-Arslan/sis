<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPromotionRequest extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait;

    protected $fillable = [
        'student_id',
        'promotion_request_id',
    ] ;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function student(){
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function promotion_request()
    {
        return $this->belongsTo(PromotionRequest::class, 'promotion_request_id', 'id');
    }
}
