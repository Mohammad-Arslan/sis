<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'invoice_id',
        'amount',
        'payment_date',
        'method',
        'reference',
        'remarks',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'invoice_id', 'id');
    }

    public function cleared_arrears()
    {
        return $this->hasMany(StudentArrearsHistory::class, 'cleared_by_payment_id', 'id');
    }
} 