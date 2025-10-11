<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $table = 'curriculum';

    protected $fillable = [
        'class_id',
        'subject_id',
        'curriculum_category_id',
        'title',
        'description',
        'is_strands',
    ];

    public function curriculum_category()
    {
        return $this->belongsTo(CurriculumCategory::class, 'curriculum_category_id');
    }

    public function curriculum_class()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function curriculum_subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
