<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class BranchClassSection extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'branch_id',
        'class_id',
        'section_id',
    ];

    public function branches()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function sections()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function com_classes()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function class_students()
    {
        return $this->hasMany(ClassStudent::class, 'branch_class_section_id', 'id');
    }

    public function class_teachers()
    {
        return $this->hasMany(ClassTeacher::class, 'branch_class_section_id', 'id');
    }

    public function class_teacher()
    {
        return $this->hasOne(ClassTeacher::class, 'branch_class_section_id', 'id');
    }
}
