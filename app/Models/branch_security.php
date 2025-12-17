<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class branch_security extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'academic_year_id',
        'branch_id',
        'amount',
        'remarks',
        'created_by'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id','id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by','id');
    }
}
