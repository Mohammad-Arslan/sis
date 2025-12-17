<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function curricula()
    {
        return $this->hasMany(Curriculum::class, 'curriculum_type_id');
    }

    // Define the relationship to the CurriculumCategory model
    public function curriculum_categories()
    {
        return $this->hasMany(CurriculumCategory::class, 'curriculum_type_id');
    }
}
