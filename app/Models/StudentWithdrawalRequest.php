<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentWithdrawalRequest extends Model
{
    use HasFactory, SerializeDateTrait, SoftDeletes;

    protected $fillable = [
        'student_id',
        'guardian_id',
        'withdrawal_reason_id',
        'feedback_message',
        'beneficiary_name',
        //'guardian_cnic_image_front',
        //'guardian_cnic_image_back',
        'last_day_at_school'
    ];

    protected $dates = [
        'last_day_at_school'
    ];

    public function reason()
    {
        return $this->belongsTo(WithdrawalReason::class, 'withdrawal_reason_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id', 'id');
    }

    /*public function beneficiary()
    {
        return $this->belongsTo(Guardian::class, 'beneficiary_id', 'id');
    }*/

}
