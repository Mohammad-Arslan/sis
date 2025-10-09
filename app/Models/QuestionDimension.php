<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionDimension extends Model
{
    use SoftDeletes;

    protected $fillable = ['title'];

    public function answerDimensions()
    {
        return $this->hasMany(AnswerDimension::class, 'question_dimensions_id');
    }
}
