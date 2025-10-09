<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignationType extends Model
{
    use HasFactory,SerializeDateTrait;
    protected $fillable = [
        'type_name'
    ];

    public function designations()
    {
        return $this->hasMany(Designation::class, 'id','type_id');
    }
}
