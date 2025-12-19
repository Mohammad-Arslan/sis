<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingCriteriaClass extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'class_id',
        'grading_criteria_id',
    ];

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function grading_criteria()
    {
        return $this->belongsTo(GradingCriteria::class, 'grading_criteria_id', 'id');
    }
}
