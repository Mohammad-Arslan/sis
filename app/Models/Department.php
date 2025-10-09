<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes,SerializeDateTrait;
    protected $fillable = [
        'department_name',
        'abbreviation',
        'company_id',
        'parent_id',
        'for_school'
    ];
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    
    /**
     * Get assets assigned to this department
     */
    public function assets()
    {
        return $this->hasMany(Asset::class, 'current_department_id');
    }
}
