<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class TaskStatusChangeLog extends Model
{
    use HasFactory,SerializeDateTrait;
    protected $fillable = [
        'task_id',
        'from_status',
        'to_status',
        'user_id'
    ];
}
