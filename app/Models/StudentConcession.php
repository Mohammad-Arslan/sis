<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentConcession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'fee_charge_id',
        'fee_concession_id',
        'start_date',
        'end_date'
    ];

    public static function store($payload) {
        self::create($payload);
    }

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function fee_charge() {
        return $this->belongsTo(FeeCharge::class, 'fee_charge_id', 'id');
    }

    public function fee_concession() {
        return $this->belongsTo(FeeConcession::class, 'fee_concession_id', 'id');
    }

    public function academic_year() {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }
}
