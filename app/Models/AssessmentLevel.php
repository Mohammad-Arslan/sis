<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentLevel extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'name',
        'parent_id',
        'status',
        'sort_no',
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
}
