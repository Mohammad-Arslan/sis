<?php

namespace App\Http\Controllers;

use App\Models\StudentLedger;
use App\Models\StudentLedgerInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\IsNull;
use Yajra\DataTables\DataTables;

class StudentLedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentLedgerInvoice::whereHas('ledger', function ($query) use ($request) {
                $query->where('student_id', $request->student);
            })->whereNotNull('student_invoice_id')
                ->with([
                    'invoice.student_fee_package.academic_year',
                    'invoice.student_fee_package.com_class',
                    'invoice.student_fee_package.section',
                    'ledger.class_student.academic_years',
                    'ledger.class_student.branch_class_sections.com_classes',
                    'ledger.class_student.branch_class_sections.sections',
                    'invoice.student',
                    'invoice.payments',
                    'invoice.arrears_carried_from',
                    'invoice.arrears_history'
                ]);
            
            if ($request->academic_year_id) {
                $data = $data->whereHas('invoice.student_fee_package.academic_year', function ($q) use ($request) {
                    $q->where('academic_year_id', $request->academic_year_id);
                });
            }
            
            $data = $data->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('month_string', function ($row) {
                    return isset($row->month) ? Carbon::create(0, $row->month + 1, 0, 0, 0, 0)->format('M') : '<i class="text-muted">N/A</i>';
                })
                ->addColumn('issue_date', function ($row) {
                    return isset($row->invoice) ? Carbon::parse($row->invoice->issue_date)->format('d-M-Y') : '<i class="text-muted">Not Generated</i>';
                })
                ->addColumn('payment_date', function ($row) {
                    return (isset($row->invoice) && $row->invoice->is_paid == 1) ? Carbon::parse($row->invoice->paid_date)->format('d-M-Y') : '<i class="text-danger">Not Paid</i>';
                })
                ->addColumn('invoice_no', function ($row) {
                    return isset($row->invoice) ? $row->invoice->invoice_no : '<i class="text-muted">Not Generated</i>';
                })
                ->addColumn('debit', function ($row) {
                    if (!isset($row->invoice)) {
                        return '<span class="text-muted">0</span>';
                    }
                    
                    // Use normalized structure - total_payable from student_invoices table
                    $totalPayable = $row->invoice->total_payable ?? 0;
                    
                    // Check for arrears from previous months that were carried to this invoice
                    $arrearsCarried = 0;
                    if ($row->invoice->arrears_carried_from) {
                        $arrearsCarried = $row->invoice->arrears_carried_from->sum('amount');
                    }
                    
                    // Calculate fine if due date has passed and arrears not cleared
                    $fine = 0;
                    $isArrearsCleared = $row->invoice->arrears_history()
                        ->where('cleared_date', '!=', null)
                        ->exists();
                    
                    if ($row->invoice->due_date && now()->gt($row->invoice->due_date) && !$isArrearsCleared) {
                        $fine = $totalPayable * 0.025; // 2.5% fine
                    }
                    
                    $totalWithFine = $totalPayable + $fine;
                    
                    return number_format($totalWithFine);
                })
                ->addColumn('credit', function ($row) {
                    if (!isset($row->invoice)) {
                        return '<span class="text-muted">0</span>';
                    }
                    $totalPaid = $row->invoice->payments->sum('amount') ?? 0;
                    return $totalPaid > 0 ? number_format($totalPaid) : '<span class="text-muted">0</span>';
                })
                ->addColumn('balance', function ($row) {
                    if (!isset($row->invoice)) {
                        return '<span class="text-muted">0</span>';
                    }
                    $totalPayable = $row->invoice->total_payable ?? 0;
                    $totalPaid = $row->invoice->payments->sum('amount') ?? 0;

                    // Check if there are any pending arrears for this invoice
                    $pendingArrears = $row->invoice->arrears_history
                        ->where('cleared_date', null)
                        ->sum('amount');

                    // If there are no pending arrears, balance is 0
                    if ($pendingArrears == 0) {
                        return '<span class="text-success">0</span>';
                    }

                    // If paid less than payable and there are pending arrears, show positive balance
                    if ($totalPaid < $totalPayable) {
                        return '<span class="text-danger">' . number_format($totalPayable - $totalPaid) . '</span>';
                    }

                    // If paid more than payable, show negative balance (overpayment)
                    if ($totalPaid > $totalPayable) {
                        return '<span class="text-success">-' . number_format($totalPaid - $totalPayable) . '</span>';
                    }

                    // Default: exact payment, no arrears
                    return '<span class="text-success">0</span>';
                })
                ->addColumn('action', function ($row) {
                    return view('students.guardian_actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'issue_date', 'invoice_no', 'debit', 'credit', 'balance', 'month_string', 'payment_date'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentLedger  $studentLedger
     * @return \Illuminate\Http\Response
     */
    public function show(StudentLedger $studentLedger)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentLedger  $studentLedger
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentLedger $studentLedger)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentLedger  $studentLedger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentLedger $studentLedger)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentLedger  $studentLedger
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentLedger $studentLedger)
    {
        //
    }
}
