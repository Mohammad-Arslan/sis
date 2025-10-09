<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class TaxType extends Model
{
    use HasFactory, SerializeDateTrait;

    protected $fillable = [
        'name',
        'description'
    ];

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }
}
