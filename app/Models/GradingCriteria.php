<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingCriteria extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $table = 'grading_criteria';
    protected $fillable = [
        'title',
        'grading_key',
        'starting_percentage',
        'ending_percentage',
        'status',
        'description'
    ];

    public function grading_criteria_classes()
    {
        return $this->hasMany(GradingCriteriaClass::class, 'grading_criteria_id', 'id');
    }

    public function classes()
    {
        return $this->belongsToMany(ComClass::class, 'grading_criteria_classes', 'grading_criteria_id', 'class_id');
    }
}
