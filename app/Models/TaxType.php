<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class TaxType extends Model
{
    use HasFactory, SerializeDateTrait, LogsActivity;

    protected $fillable = [
        'name',
        'description'
    ];

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }
}
