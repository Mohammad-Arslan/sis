<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class TeacherType extends Model
{
    use HasFactory,SerializeDateTrait;

    public function getNameAttribute($value){
        return ucwords($value);
    }
}
