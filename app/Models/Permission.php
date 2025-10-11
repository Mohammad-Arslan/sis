<?php

namespace App\Models;

use Laratrust\Models\Permission as LaratrustPermission;

class Permission extends LaratrustPermission
{
    public $guarded = [];
    protected $fillable = [
        'system_module_id',
        'name',
        'display_name',
        'description'

    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function system_modules()
    {
        return $this->belongsTo(SystemModule::class, 'system_module_id', 'id');
    }
}
