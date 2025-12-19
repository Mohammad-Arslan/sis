<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskMember extends Model
{
    use HasFactory ;
    use SoftDeletes;

    protected $fillable = [
        'task_id',
        'user_id'
    ];


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function task()
    {
        return $this->hasOne(Tasl::class, 'id', 'task_id');
    }
}
