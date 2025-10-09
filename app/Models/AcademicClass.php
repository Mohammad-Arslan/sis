<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class AcademicClass extends Model
{
    use HasFactory,SerializeDateTrait;

    public function branch_class_sections()
    {
        return $this->belongsTo(BranchClassSection::class, 'branch_class_section_id', 'id');
    }

    public function academic_years()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function class_students()
    {
        return $this->hasMany(ClassStudent::class);
    }
}
