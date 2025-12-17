<?php

namespace App\Models;

use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentTransferCase extends Model
{
    use HasFactory, SoftDeletes, SerializeDateTrait;

    protected $fillable = [
        'application_id',
        'request_date',
        'transfer_wef',
        'joining_date',
        'cancellation_date',
        'approved_date',
        'status',
        'cancellation_reason',
        'remarks',
        'status_updated_at',
        'student_id',
        'state_id',
        'created_by',
        'approved_by',
        'from_branch',
        'to_branch',
        'cancel_reason',
        'cancellation_remarks',
        'approval_remarks',
        'transfer_reason_id',
        'academic_year_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'approved_date',
        'request_date',
        'cancellation_date',
        'transfer_wef',
        'joining_date',
        'status_updated_at'
    ];

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

    public function from_branch_model()
    {
        return $this->belongsTo(Branch::class, 'from_branch', 'id');
    }

    public function to_branch_model()
    {
        return $this->belongsTo(Branch::class, 'to_branch', 'id');
    }

    public function reason()
    {
        return $this->belongsTo(StudentTransferReason::class, 'transfer_reason_id', 'id');
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }
}
