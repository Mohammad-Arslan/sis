<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class TaskStatus extends Model
{
    use HasFactory;
    use SerializeDateTrait;


    public function tasks()
    {
        return $this->hasMany(Task::class, 'task_status_id', 'id');
    }
}
