<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Promo extends Model
{
    use HasFactory,SerializeDateTrait;
    protected $dates = ['active_till'];

    public function promo_type()
    {
        return $this->belongsTo(PromoType::class, 'promo_type_id', 'id');
    }
}
