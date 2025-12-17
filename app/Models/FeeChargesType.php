<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class FeeChargesType extends Model
{
    use HasFactory,SerializeDateTrait;
    protected $fillable = [
        'name',
        'abbreviation',
        'description',
        'frequency'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function fee_charges()
    {
        return $this->hasMany(FeeCharge::class);
    }
}
