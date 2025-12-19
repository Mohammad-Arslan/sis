<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'project_id',
        'task_status_id',
        'title',
        'summary',
        'task_detail_route'
    ];

    public function sub_tasks()
    {
        return $this->hasMany(SubTask::class, 'task_id', 'id');
    }

    public function members()
    {
        return $this->hasMany(TaskMember::class, 'task_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function status()
    {
        return $this->hasOne(TaskStatus::class, 'id', 'task_status_id');
    }

    public function taskable()
    {
        return $this->morphTo();
    }
}
