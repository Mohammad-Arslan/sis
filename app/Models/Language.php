<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Language extends Model
{
    use HasFactory,SerializeDateTrait;

    protected $fillable = [
        'language_name'
     ];

     protected $dates = [
         'created_at',
         'updated_at',
     ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->hasMany(Language::class);
    }
}
