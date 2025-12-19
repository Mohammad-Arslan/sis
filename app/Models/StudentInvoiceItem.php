<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;

class StudentInvoiceItem extends Model
{
    use HasFactory;
    use SerializeDateTrait;

    protected $fillable = [
        'student_invoice_id',
        'fee_charge_id',
        'debit',
        'credit',
        'concession',
        'concession_amount',
        'final_amount'
    ];

    public function student_invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'student_invoice_id', 'id');
    }

    public function fee_charges()
    {
        return $this->belongsTo(FeeCharge::class, 'fee_charge_id', 'id');
    }
}
