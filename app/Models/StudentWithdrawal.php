<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentWithdrawal extends Model
{
    use HasFactory, SerializeDateTrait, SoftDeletes;

    protected $fillable = [
        'order_no',
        'created_by',
        'withdrawal_wef',
        'application_date',
        'last_day_at',
        'withdrawal_reason_id',
        'last_invoice_paid_at',
        'library_clearance',
        'clearance_amount',
        'student_id',
        'approved_by',
        'class_student_id',
        'student_invoice_id',
        'approved_date',
        'remarks',
        'approval_remarks',
        'refund_status',
        'security_amount',
        'academic_year_id',
        'guardian_id',
        'beneficiary_name',
        'beneficiary_cnic',
        'beneficiary_postal_address',
        'beneficiary_phone',

        'cancellation_by',
        'cancellation_date',
        'cancellation_reason',
        'cancellation_approved_by',
        'cancellation_approved_remarks',

        'cheque_date',
        'cheque_number',
        'refund_remarks'
        //'application_status'
    ];

    protected $dates = [
        'withdrawal_wef',
        'application_date',
        'last_day_at',
        'approved_date',
        'cancellation_date',
        'cheque_date'
    ];

    public function reason()
    {
        return $this->belongsTo(WithdrawalReason::class, 'withdrawal_reason_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function approved_by()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'id');
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'guardian_id', 'id');
    }
}
