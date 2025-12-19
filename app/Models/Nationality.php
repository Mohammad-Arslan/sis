<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Nationality extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
