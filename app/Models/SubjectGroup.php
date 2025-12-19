<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectGroup extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'subject_group_name',
        'description',
    ];
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
