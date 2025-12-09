<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class City extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait, LogsActivity;
    protected $fillable = [
        'city_name',
        'abbreviation',
        'state_id',
    ];
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function states()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
