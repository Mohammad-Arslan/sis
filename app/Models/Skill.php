<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'title',
        'parent_id',
        'term_id',
        'subject_id',
        'sort_no',
        'type',
        'class_id',
        'language',
        'status',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function term()
    {
        return $this->belongsTo(Term::class, 'term_id', 'id');
    }
}
