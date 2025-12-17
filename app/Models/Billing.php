<?php

namespace App\Models;

use App\Models\Student;
use App\Models\StudentInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Billing extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'student_id',
        'invoice_id',
        'branch_id',
        'discountable_charges',
        'non_refundable_charges',
        'sibling_discount_percentage',
        'concession_type',
        'concession_percentage',
        'concession_discount',
        'royalty_percentage',
        'royalty_amount',
        'total_after_royalty',
        'arrears',
        'billing_amount'
    ];


    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'invoice_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }



}
