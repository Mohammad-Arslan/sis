<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherObservation extends Model
{
    use SoftDeletes;

    protected $table = 'teacher_observations';

    protected $fillable = [
        'branch_id',
        'employee_id',
        'evaluation_user_id',
    ];

    public function details()
    {
        return $this->hasMany(ObservationDetail::class, 'teacher_observation_id');
    }

    // public function branch()
    // {
    //     return $this->belongsTo(BranchClassSection::class, 'branch_id','id');
    // }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id','id');
    }


    public function teacher()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    // TeacherObservation.php
    public function observationDetails()
    {
        return $this->hasMany(ObservationDetail::class, 'teacher_observation_id');
    }


}
