<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralBehaviour extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'title',
        'parent_id',
        'class_id',
        'status',
        'description'
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }
}
