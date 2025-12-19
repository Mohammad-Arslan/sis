<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentLedgerInvoice extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'student_ledger_id',
        'student_invoice_id',
        'sort',
        'month',
        'credit',
        'debit',
        'is_valid'
    ];

    public static function create_empty_record($student_ledger_id, $academic_start_date)
    {
        $student_ledger = StudentLedger::find($student_ledger_id);
        $ledger_entries = array();


        $j = (int) $academic_start_date;
        for ($i = 0; $i < 12; $i++) {
            if ($j == 13) {
                $j = 1;
            }

            $entry = [
                'student_ledger_id' => $student_ledger['id'],
                'sort' => $i + 1,
                'month' => $j,
            ];

            $ledger_entries = array_merge($ledger_entries, [$entry]);
            $j++;
        }

        StudentLedgerInvoice::insert($ledger_entries);
    }

    public function ledger()
    {
        return $this->belongsTo(StudentLedger::class, 'student_ledger_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo(StudentInvoice::class, 'student_invoice_id', 'id');
    }
}
