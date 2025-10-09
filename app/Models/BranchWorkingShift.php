<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class BranchWorkingShift extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait;

    protected $fillable = [
        'staff_id',
        'working_day_id',
        'working_shift_id',
        'name'
    ];

    public function staff()
    {
        return $this->belongsTo(StaffType::class, 'staff_id','id');
    }

    public function day()
    {
        return $this->belongsTo(WorkingDay::class,'working_day_id','id');
    }

    public function working_shift()
    {
        return $this->belongsTo(WorkingShift::class, 'working_shift_id','id');
    }

}
