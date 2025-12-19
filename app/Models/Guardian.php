<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Guardian extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;
    use HasApiTokens;
    use Notifiable;

    protected $fillable = [
        'guardian_name',
        'mobile',
        'CNIC',
        'email',
        'student_id',
        'relation_id',
        'is_parent',
        'employee_no',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class, 'relation_id', 'id');
    }

    public function family()
    {
        return $this->hasOne(FamilyInformation::class, 'guardian_id', 'id');
    }

    // Direct relationship to students through family
    public function familyStudents()
    {
        return $this->hasManyThrough(
            Student::class,
            FamilyInformation::class,
            'guardian_id', // Foreign key on family_information table
            'id', // Foreign key on students table
            'id', // Local key on guardians table
            'id' // Local key on family_information table
        )->whereHas('sibling_info');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_no', 'employee_id');
    }
}
