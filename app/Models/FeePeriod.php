<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePeriod extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait;
    protected $fillable = [
        'period_name',
        'period_description',
        'branch_id',
        'academic_year_id',
        'from_date',
        'issue_date',
        'due_date',
        'valid_date',
        'arrears_date',
        'to_date',
    ];

    protected $dates = [
        'from_date',
        'issue_date',
        'due_date',
        'valid_date',
        'arrears_date',
        'to_date',
        'created_at',
        'updated_at',
    ];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
