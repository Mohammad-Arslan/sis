<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Religion extends Model
{
    use HasFactory,SerializeDateTrait;

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
