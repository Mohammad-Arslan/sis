<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Relation extends Model
{
    use HasFactory,SerializeDateTrait;

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }
}
