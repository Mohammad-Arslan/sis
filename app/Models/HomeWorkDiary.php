<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\SerializeDateTrait;

class HomeWorkDiary extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'state_id',
        'branch_id',
        'class_id',
        'section_id',
        'academic_year_id',
        'homework_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $dates = [
        'homework_date',
        'created_at',
        'updated_at',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function com_class()
    {
        return $this->belongsTo(ComClass::class, 'class_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
