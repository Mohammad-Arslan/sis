<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentActivity extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'student_activity';

    protected $fillable = [
        'student_learning_outcome_id',
        'methodology',
        'resource',
        'assessment',
        'duration',
    ];
}
