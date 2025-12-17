<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentArrearsHistory extends Model
{
    use HasFactory;

    protected $table = 'student_arrears_history';
    protected $fillable = [
        'student_id',
        'from_invoice_id',
        'to_invoice_id',
        'amount',
        'carried_date',
        'cleared_by_payment_id',
        'cleared_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function from_invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'from_invoice_id', 'id');
    }

    public function to_invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'to_invoice_id', 'id');
    }

    public function cleared_by_payment()
    {
        return $this->belongsTo(StudentPayment::class, 'cleared_by_payment_id', 'id');
    }
} 