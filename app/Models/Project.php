<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class Project extends Model
{
    use HasFactory,SerializeDateTrait;

    public function members()
    {
        return $this->hasMany(ProjectMember::class, 'project_id', 'id');
    }

    public function project_type()
    {
        return $this->hasOne(ProjectType::class, 'id', 'project_type_id');
    }
}
