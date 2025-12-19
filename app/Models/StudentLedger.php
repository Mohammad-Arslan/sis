<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLedger extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'class_student_id',
    ];

    public function ledger_invoices()
    {
        return $this->hasMany(StudentLedgerInvoice::class);
    }

    public function class_student()
    {
        return $this->belongsTo(ClassStudent::class, 'class_student_id', 'id');
    }
}
