<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnswerDimension extends Model
{
    use SoftDeletes;

    protected $fillable = ['question_dimensions_id', 'title'];

    public function questionDimension()
    {
        return $this->belongsTo(QuestionDimension::class, 'question_dimensions_id');
    }
}
