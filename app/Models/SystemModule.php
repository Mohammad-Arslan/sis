<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemModule extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    protected $parentColumn = 'parent_id';

    public function parent()
    {
        return $this->belongsTo(SystemModule::class, $this->parentColumn);
    }

    public function children()
    {
        return $this->hasMany(SystemModule::class, $this->parentColumn);
    }

    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    public function modules_permission()
    {
        return $this->hasMany(Permission::class, 'system_module_id', 'id');
    }
}
