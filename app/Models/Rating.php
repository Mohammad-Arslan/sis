<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['observation_detail_id', 'question_dimension_id', 'answer_dimension_id', 'rating'];

    // Define the relationship to the ObservationDetail model
    public function observationDetail()
    {
        return $this->belongsTo(ObservationDetail::class);
    }

    // Define the relationship to the QuestionDimension model
    public function questionDimension()
    {
        return $this->belongsTo(QuestionDimension::class, 'question_dimension_id');
    }


    // Define the relationship to the AnswerDimension model
    public function answerDimension()
    {
        return $this->belongsTo(AnswerDimension::class);
    }
}
