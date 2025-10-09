<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperType extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $fillable = [
        'name',
        'subject_id',
        'status',
        'description'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id','id');
    }
}
