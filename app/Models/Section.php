<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Section extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'section_name',
        'abbreviation',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function branch_class_section()
    {
        return $this->hasMany(BranchClassSection::class);
    }

    public function student_fee_package()
    {
        return $this->hasMany(StudentFeePackage::class);
    }

    public function lesson_plans()
    {
        return $this->belongsToMany(LessonPlan::class, 'lesson_plan_sections','section_id','lesson_plan_id');
    }
}
