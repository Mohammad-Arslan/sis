<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Country extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;

    protected $fillable = [
        'country_name',
        'abbreviation',
        'country_code'
    ];

    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function states()
    {
        return $this->hasMany(State::class, 'country_id', 'id');
    }

    public function students()
    {
        return $this->hasOne(Student::class, 'country_id', 'id');
    }
}
