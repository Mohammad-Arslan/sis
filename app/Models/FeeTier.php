<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class FeeTier extends Model
{
    use HasFactory,SerializeDateTrait;
    protected $fillable = [
        'tier_name',
        'description'
     ];

     protected $dates = [

         'created_at',
         'updated_at',
     ];
}
