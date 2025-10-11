<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumAttainmentTarget extends Model
{
    use HasFactory;

    protected $table = 'curriculum_attainment_targets';

    protected $fillable = ['target', 'curriculum_id', 'sort_order'];

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, 'curriculum_id');
    }
}
