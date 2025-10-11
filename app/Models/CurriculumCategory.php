<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'targets', 'curriculum_type_id'];

    // Define the relationship to the CurriculumType model
    public function curriculum_type()
    {
        return $this->belongsTo(CurriculumType::class, 'curriculum_type_id');
    }
}
