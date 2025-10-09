<?php

namespace App\Http\Controllers;

use App\Exports\ExportRoyaltyReport;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchAcademicYear;
use App\Models\BranchClass;
use App\Models\ClassStudent;
use App\Models\ComClass;
use App\Models\FeeCharge;
use App\Models\FeePackage;
use App\Models\FeePackagesFeeCharges;
use App\Models\FeePeriod;
use App\Models\InvoiceType;
use App\Models\PromoClass;
use App\Models\Region;
use App\Models\Section;
use App\Models\State;
use App\Models\Student;
use App\Models\StudentArrearsHistory;
use App\Models\StudentConcession;
use App\Models\StudentFeePackage;
use App\Models\StudentInvoice;
use App\Models\StudentInvoiceItem;
use App\Models\StudentLedger;
use App\Models\StudentLedgerInvoice;
use App\Models\StudentPayment;
use App\Services\ArrearsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\BranchClassSection;
use DateTime;

class StudentInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = StudentInvoice::with([
                'items.fee_charges',
                'payments',
                'invoice_type',
                'student_fee_package.academic_year',
                'student_fee_package.com_class',
                'student_fee_package.section',
                'student_fee_package.fee_concession.fee_concession_type',
                'fee_period'
            ])
                ->where('student_id', $request->student);

            // Filter by academic year if provided
            if ($request->has('academic_year_id') && !empty($request->academic_year_id)) {
                $query->whereHas('student_fee_package', function ($q) use ($request) {
                    $q->where('academic_year_id', $request->academic_year_id);
                });
            }

            $invoices = $query->orderBy('due_date')->get();

            $invoiceData = [];
            foreach ($invoices as $invoice) {
                $itemsTotal = $invoice->total_payable ?? 0;
                $paymentsTotal = $invoice->payments->sum('amount');

                // Fetch arrears and advance records for this invoice
                $arrearsRecords = StudentArrearsHistory::where('to_invoice_id', $invoice->id)
                    ->where('amount', '>', 0)
                    ->get(); // Removed whereNull('cleared_date') to include all arrears
                $advanceRecords = StudentArrearsHistory::where('to_invoice_id', $invoice->id)
                    ->where('amount', '<', 0)
                    ->whereNull('cleared_date')
                    ->get();

                $arrears = $arrearsRecords->sum('amount');
                $advance = abs($advanceRecords->sum('amount'));

                // Always return numeric values (0 if none)
                $uiArrears = $arrears > 0 ? $arrears : 0;
                $uiAdvance = $advance > 0 ? $advance : 0;

                // Breakdown arrays for UI (include carried_date and cleared_date)
                $arrearsBreakdown = $arrearsRecords->map(function ($record) {
                    $fromInvoice = StudentInvoice::with('fee_period')->find($record->from_invoice_id);
                    $monthLabel = $fromInvoice && $fromInvoice->fee_period
                        ? get_month_name($fromInvoice->fee_period->from_date)
                        : '';
                    return [
                        'amount' => $record->amount,
                        'from_invoice_id' => $record->from_invoice_id,
                        'from_month_label' => $monthLabel,
                        'carried_date' => $record->carried_date,
                        'cleared_date' => $record->cleared_date,
                    ];
                });
                $advanceBreakdown = $advanceRecords->map(function ($record) {
                    $fromInvoice = StudentInvoice::with('fee_period')->find($record->from_invoice_id);
                    $monthLabel = $fromInvoice && $fromInvoice->fee_period
                        ? get_month_name($fromInvoice->fee_period->from_date)
                        : '';
                    return [
                        'amount' => $record->amount,
                        'from_invoice_id' => $record->from_invoice_id,
                        'from_month_label' => $monthLabel,
                        'carried_date' => $record->carried_date,
                        'cleared_date' => $record->cleared_date,
                    ];
                });

                // Calculate payable for this invoice
                $payable = $itemsTotal;
                $payable = max(0, $payable);

                // Show balance only for overpaid invoices
                $overpaidAmount = ($paymentsTotal > $payable) ? ($paymentsTotal - $payable) : 0;
                $uiBalance = $overpaidAmount > 0 ? $overpaidAmount : '-';

                // Determine payment status for display
                $paymentStatus = $invoice->bank_payment_status;
                $statusBadge = '';
                switch ($paymentStatus) {
                    case 'paid':
                        $statusBadge = '<span class="badge bg-success">Paid</span>';
                        break;
                    case 'partially_paid':
                        $statusBadge = '<span class="badge bg-warning">Partially Paid</span>';
                        break;
                    case 'overpaid':
                        $statusBadge = '<span class="badge bg-info">Overpaid</span>';
                        break;
                    case 'unpaid':
                        $statusBadge = '<span class="badge bg-danger">Unpaid</span>';
                        break;
                    case 'cancelled':
                        $statusBadge = '<span class="badge bg-secondary">Cancelled</span>';
                        break;
                    default:
                        $statusBadge = '<span class="badge bg-secondary">' . ucfirst($paymentStatus) . '</span>';
                }

                $invoiceData[] = [
                    'invoice' => $invoice,
                    'items_total' => $itemsTotal,
                    'arrears' => $uiArrears,
                    'arrears_breakdown' => $arrearsBreakdown,
                    'advance_payment' => $uiAdvance,
                    'advance_breakdown' => $advanceBreakdown,
                    'arrears_carried_to' => [], // You can fill this if you want to show details
                    'arrears_cleared_date' => null, // You can fill this if you want to show details
                    'total_payable' => $payable,
                    'paid_amount' => $paymentsTotal,
                    'balance' => $uiBalance,
                    'payment_status' => $paymentStatus,
                    'status_badge' => $statusBadge,
                    'month' => isset($invoice->fee_period) ? get_month_name($invoice->fee_period->from_date) : 'N/A',
                ];
            }

            return response()->json(['data' => $invoiceData]);
        }

        $academic_years = AcademicYear::all();
        return view('student-invoices.show', compact('academic_years'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */


    // public function calculate_previous_year_invoices()
    // {

    // }
    public function create()
    {
        return redirect()->route('generate-invoices')->with('error', 'Academic year doesn\'t exist in branch.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * Validation Rules:
     * - Student must not have 'left' status
     * - For Admission invoices: Only one admission invoice per student (regardless of payment status)
     * - For Monthly invoices: No unpaid invoice for the same fee period
     * - No existing monthly invoice in ledger for the same month
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'fee_period_id' => 'required',
            'student_id' => 'required',
            'student_fee_package_id' => 'required',
            'fee_charges_items' => 'required | array | min:1',
            'invoice_type' => 'required|in:Admission,Monthly',
        ]);

        //Condition to check if student is not left already
        $studentInfo = Student::with('student_promotion_request.promotion_request')->where('id', $request->student_id)->first();
        if ($studentInfo->status == 'left') {
            return redirect()->back()->with('error', 'Can\'t generate invoice for student with left status!');
        }

        // Early validation for admission invoices - prevent multiple admission invoices
        if ($request->invoice_type == 'Admission' && $this->hasAdmissionInvoice($request->student_id)) {
            $existingAdmissionInvoice = $this->getExistingAdmissionInvoice($request->student_id);

            return redirect()->back()->with('error', 'Student already has an admission invoice. Invoice ID: ' . $existingAdmissionInvoice->id . ' (Status: ' . ucfirst($existingAdmissionInvoice->bank_payment_status) . ')');
        }

        $fee_period = FeePeriod::find($request->fee_period_id);
        $current_class = ClassStudent::where(['student_id' => $request->student_id, 'is_valid' => 1])->first();
        $invoice_month = Carbon::parse($fee_period->from_date)->format('m');
        // Check for existing invoices based on invoice type
        if ($request->invoice_type == 'Admission') {
            // For admission invoices, check if ANY admission invoice exists (regardless of status)
            $check_existing_invoice = StudentInvoice::where([
                'student_id' => $request->student_id,
                'invoice_frequency' => 'Admission'
            ])->where('bank_payment_status', '!=', 'cancelled');
        } else {
            // For monthly invoices, check for unpaid invoices in the same fee period
            $check_existing_invoice = StudentInvoice::where([
                'student_id' => $request->student_id,
                'fee_period_id' => $request->fee_period_id
            ])->where('bank_payment_status', '!=', 'cancelled')
                ->where('bank_payment_status', 'unpaid');
        }

        $existing_invoice = $check_existing_invoice->first();

        if ($existing_invoice) {
            Log::info('Invoice already exists check failed', [
                'student_id' => $request->student_id,
                'invoice_type' => $request->invoice_type,
                'fee_period_id' => $request->fee_period_id,
                'existing_invoice_id' => $existing_invoice->id,
                'existing_invoice_frequency' => $existing_invoice->invoice_frequency,
                'existing_invoice_fee_period_id' => $existing_invoice->fee_period_id,
                'existing_invoice_status' => $existing_invoice->bank_payment_status
            ]);

            $errorMessage = $request->invoice_type == 'Admission'
                ? 'Admission invoice already exists for this student. Invoice ID: ' . $existing_invoice->id . ' (Status: ' . ucfirst($existing_invoice->bank_payment_status) . ')'
                : 'Invoice already exists for this period. Invoice ID: ' . $existing_invoice->id;

            return redirect()->back()->with('error', $errorMessage);
        }

        $check_existing_monthly_invoice = StudentLedgerInvoice::where([
            'month' => $invoice_month
        ])->whereHas('ledger', function ($query) use ($request, $current_class) {
            $query->where(['student_id' => $request->student_id, 'class_student_id' => $current_class->id]);
        })->whereNotNull('student_invoice_id')->exists();

        if ($check_existing_monthly_invoice) {
            return redirect()->back()->with('error', 'Selected month invoice is already generated.');
        }

        if (!isset($request->issue_date) && empty($request->issue_date))
            $issue_date = Carbon::now()->startOfMonth();
        else
            $issue_date = $request->issue_date;

        if (!isset($request->due_date) && empty($request->due_date))
            $due_date = Carbon::now()->startOfMonth()->addDays(2);
        else
            $due_date = $request->due_date;

        if (!isset($request->validity_date) && empty($request->validity_date))
            $validity_date = Carbon::now()->startOfMonth()->addDays(2);
        else
            $validity_date = $request->validity_date;

        $invoice_dates = [
            'issue_date' => $issue_date,
            'due_date' => $due_date,
            'validity_date' => $validity_date
        ];

        // Calculate totals for the selected items
        $subtotal = 0;
        $totalDiscount = 0;
        $totalPayable = 0;

        foreach ($request->fee_charges_items as $chargeId) {
            $fee_charge = FeeCharge::where('id', $chargeId)->first();
            if ($fee_charge) {
                $originalAmount = $fee_charge->amount ?? 0;
                $subtotal += $originalAmount;

                // Get student concession for this charge
                $charge_concession = StudentConcession::where([
                    'student_id' => $request->student_id,
                    'fee_charge_id' => $chargeId,
                    'is_valid' => 1
                ])->first();

                $discountPercentage = $charge_concession ? $charge_concession->fee_concession->concession_percentage : 0;
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $totalDiscount += $discountAmount;
            }
        }

        $totalPayable = $subtotal - $totalDiscount;

        // --- NEW LOGIC: Add arrears and subtract advance ---
        // 1. Get all uncleared arrears for this student (not yet assigned to a new invoice)
        $arrears = StudentArrearsHistory::where('student_id', $request->student_id)
            ->whereNull('cleared_date')
            ->whereNull('to_invoice_id')
            ->where('amount', '>', 0) // Only positive amounts (arrears)
            ->sum('amount');

        // 2. Get available advance payment for this student
        $advance = ArrearsService::getAdvancePaymentAmount($request->student_id);

        // 3. Calculate the new total payable
        $totalPayableWithArrears = $totalPayable + $arrears - $advance;
        $finalTotalPayable = max(0, $totalPayableWithArrears);
        $carriedAdvance = $advance > ($totalPayable + $arrears) ? $advance - ($totalPayable + $arrears) : 0;

        // 4. Save breakdown for display (optional: you can add these to the invoice if you want to show in UI)
        //
        // Use database transaction for data integrity
        return DB::transaction(function () use ($request, $invoice_dates, $subtotal, $totalDiscount, $totalPayable, $arrears, $advance, $finalTotalPayable, $carriedAdvance, $current_class, $invoice_month) {

            $input = [
                'student_id' => $request->student_id,
                'student_fee_package_id' => $request->student_fee_package_id,
                'promo_id' => $request->promo_id,
                'invoice_type_id' => $request->invoice_type_id,
                'invoice_frequency' => $request->invoice_type,
                'payment_source_id' => null, // No payment source provided in form
                'is_paid' => 0, // Invoice is unpaid initially
                'paid_date' => null,
                'fee_period_id' => isset($request->fee_period_id) ? $request->fee_period_id : null,
                'due_date' => $invoice_dates['due_date'],
                'issue_date' => $invoice_dates['issue_date'],
                'validity_date' => $invoice_dates['validity_date'],
                'bank_payment_status' => 'unpaid',
                // Add calculated totals to invoice
                'subtotal' => $subtotal,
                'total_discount' => $totalDiscount,
                'total_payable' => $finalTotalPayable,
                // Optionally store for reporting/UI
                'arrears_included' => $arrears,
                'advance_applied' => $advance,
                'carried_advance' => $carriedAdvance,
            ];

            $studentInvoice = StudentInvoice::create($input);

            // Carry forward all open arrears to this new invoice
            StudentArrearsHistory::where('student_id', $request->student_id)
                ->whereNull('to_invoice_id')
                ->whereNull('cleared_date')
                ->update(['to_invoice_id' => $studentInvoice->id]);

            // Get the total arrears just carried forward to this invoice
            // $carriedArrears = StudentArrearsHistory::where('to_invoice_id', $studentInvoice->id)
            //     ->where('amount', '>', 0)
            //     ->whereNull('cleared_date')
            //     ->sum('amount');

            // Update the invoice's total_payable to include the carried arrears
            // if ($carriedArrears > 0) {
            //     $studentInvoice->total_payable += $carriedArrears;
            //     $studentInvoice->save();
            // }

            // Create invoice items
            foreach ($request->fee_charges_items as $chargeId) {
                $fee_charge = FeeCharge::where('id', $chargeId)->first();
                $charge_concession = StudentConcession::where([
                    'student_id' => $request->student_id,
                    'fee_charge_id' => $chargeId,
                    'is_valid' => 1
                ])->first();

                $originalAmount = $fee_charge->amount ?? 0;
                $discountPercentage = $charge_concession ? $charge_concession->fee_concession->concession_percentage : 0;
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $finalAmount = $originalAmount - $discountAmount;

                $item_input = [
                    'student_invoice_id' => $studentInvoice->id,
                    'fee_charge_id' => $chargeId,
                    'debit' => $request->invoice_type_id == 1 ? $originalAmount : null,
                    'credit' => $request->invoice_type_id == 2 ? $originalAmount : null,
                    'concession' => $discountPercentage,
                    'concession_amount' => $discountAmount,
                    'final_amount' => $finalAmount
                ];

                StudentInvoiceItem::create($item_input);
            }

            // No payment record created - invoice is unpaid initially
            // Payments will be recorded separately when actual payments are made

            // Carry existing arrears to this new invoice
            ArrearsService::carryArrearsToInvoice($request->student_id, $studentInvoice);

            // Check for advance payments and apply to this invoice
            $advanceAmount = ArrearsService::getAdvancePaymentAmount($request->student_id);
            if ($advanceAmount > 0) {
                $amountToApply = min($advanceAmount, $finalTotalPayable);

                // Apply advance to this invoice and clear the advance records
                $actualAmountApplied = ArrearsService::applyAdvanceToInvoice($request->student_id, $studentInvoice->id, $amountToApply);

                if ($actualAmountApplied > 0) {
                    // Create a payment record for the advance amount
                    StudentPayment::create([
                        'student_id' => $request->student_id,
                        'invoice_id' => $studentInvoice->id,
                        'amount' => $actualAmountApplied,
                        'payment_date' => Carbon::now(),
                        'method' => 'advance',
                        'reference' => 'Advance Payment',
                        'remarks' => 'Applied from previous overpayment'
                    ]);
                }
            }

            // Ledger
            $current_ledger = StudentLedger::where(['student_id' => $request->student_id, 'class_student_id' => $current_class->id])->first();

            if ($request->invoice_type == 'Admission') {
                Student::find($request->student_id)->update(['status' => 'registered']);
            }

            if ($current_ledger) {
                StudentLedgerInvoice::where([
                    'student_ledger_id' => $current_ledger->id,
                    'month' => $invoice_month
                ])->update([
                            'student_invoice_id' => $studentInvoice->id,
                        ]);
            }

            return redirect(route('students.edit', $request->student_id) . '?tab=invoice')->with('success', 'Invoice created successfully.');
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentInvoice  $studentInvoice
     * @return \Illuminate\Http\View
     */
    public function show(StudentInvoice $studentInvoice)
    {
        $studentInvoice = StudentInvoice::where(
            'id',
            $studentInvoice->id
        )->with([
                    'student',
                    'student_fee_package.fee_package',
                    'student_fee_package.fee_concession.fee_concession_type',
                    'student_fee_package.academic_year',
                    'student_fee_package.com_class',
                    'student_fee_package.section',
                    'student_invoice_items.fee_charges.fee_charges_type',
                    'invoice_type',
                    'payment_source',
                    'promo.promo_type',
                    'fee_period',
                    'payments',
                    'arrears_carried_from'
                ])->first();

        // Use the new normalized structure instead of helper functions
        $calculations = $this->calculateInvoiceTotals($studentInvoice);

        return view('students.invoice_detail_modal', ['student_invoice' => $studentInvoice, 'total' => $calculations]);
    }

    /**
     * Check if student already has an admission invoice
     *
     * @param  int  $studentId
     * @return bool
     */
    private function hasAdmissionInvoice($studentId)
    {
        return StudentInvoice::where([
            'student_id' => $studentId,
            'invoice_frequency' => 'Admission'
        ])->where('bank_payment_status', '!=', 'cancelled')->exists();
    }

    /**
     * Get existing admission invoice for student
     *
     * @param  int  $studentId
     * @return \App\Models\StudentInvoice|null
     */
    private function getExistingAdmissionInvoice($studentId)
    {
        return StudentInvoice::where([
            'student_id' => $studentId,
            'invoice_frequency' => 'Admission'
        ])->where('bank_payment_status', '!=', 'cancelled')->first();
    }

    /**
     * Calculate invoice totals using the new normalized structure
     *
     * @param  \App\Models\StudentInvoice  $studentInvoice
     * @return array
     */
    private function calculateInvoiceTotals(StudentInvoice $studentInvoice)
    {
        $subTotal = $studentInvoice->subtotal ?? 0;
        $totalDiscount = $studentInvoice->total_discount ?? 0;
        $totalPayable = $studentInvoice->total_payable ?? 0;

        // Payments for this invoice only
        $paidAmount = $studentInvoice->payments->sum('amount');

        // Fine logic
        $dueDateFine = $totalPayable * 0.025;
        $afterDueDate = $totalPayable + $dueDateFine;
        $fineToApply = 0;
        $payableForBalance = $totalPayable;
        if ($studentInvoice->due_date && now()->gt($studentInvoice->due_date)) {
            $fineToApply = $dueDateFine;
            $payableForBalance = $afterDueDate;
        }

        $balance = $paidAmount - $payableForBalance;

        // Arrears and advance for info only
        $arrearsRecords = StudentArrearsHistory::where('to_invoice_id', $studentInvoice->id)
            ->where('amount', '>', 0)
            ->get();
        $arrears = $arrearsRecords->sum('amount');
        $advanceRecords = StudentArrearsHistory::where('to_invoice_id', $studentInvoice->id)
            ->where('amount', '<', 0)
            ->whereNull('cleared_date')
            ->get();
        $advance = abs($advanceRecords->sum('amount'));

        // Concession details
        $concessionDetails = [];
        foreach ($studentInvoice->student_invoice_items as $item) {
            if ($item->concession && $item->concession > 0) {
                $concessionDetails[] = [
                    'charge_name' => $item->fee_charges->fee_charges_type->name,
                    'percentage' => $item->concession,
                    'amount' => $item->concession_amount ?? 0
                ];
            }
        }

        // Arrears breakdown
        $arrearsBreakdown = $arrearsRecords->map(function ($arrear) {
            return [
                'amount' => $arrear->amount,
                'from_month_label' => $arrear->from_invoice ? get_month_name($arrear->from_invoice->fee_period->from_date ?? now()) : 'Unknown',
                'cleared_date' => $arrear->cleared_date
            ];
        })->toArray();

        // Advance breakdown
        $advanceBreakdown = $advanceRecords->map(function ($advance) {
            return [
                'amount' => abs($advance->amount),
                'from_month_label' => $advance->from_invoice ? get_month_name($advance->from_invoice->fee_period->from_date ?? now()) : 'Unknown',
                'carried_date' => $advance->carried_date
            ];
        })->toArray();

        return [
            'sub_total' => $subTotal,
            'concession_discount' => $totalDiscount,
            'promo_discount' => 0, // Will be calculated if promo exists
            'arrears' => $arrears,
            'advance_payment' => $advance,
            'total' => $totalPayable,
            'after_due_date' => $afterDueDate,
            'due_date_fine' => $dueDateFine,
            'paid_amount' => $paidAmount,
            'balance' => $balance,
            'arrear_months' => collect($arrearsBreakdown)->pluck('from_month_label')->toArray(),
            'arrears_breakdown' => $arrearsBreakdown,
            'advance_breakdown' => $advanceBreakdown,
            'concession_details' => $concessionDetails,
            'royalty_amount' => $studentInvoice->royalty_amount ?? 0,
            'royalty_percentage' => $studentInvoice->royalty_percentage ?? 0,
            'total_after_royalty' => ($totalPayable - ($studentInvoice->royalty_amount ?? 0)),
            'fine_applied' => $fineToApply,
            'payable_for_balance' => $payableForBalance,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentInvoice  $studentInvoice
     * @return \Illuminate\View\View
     */
    public function edit(StudentInvoice $studentInvoice)
    {
        //dd($studentInvoice->toArray());
        $studentInvoice->load([
            'student.branch.class_group',
            'student.branch.bank_accounts',
            'student.city',
            'student_fee_package.fee_package',
            'student_fee_package.fee_concession.fee_concession_type',
            'student_fee_package.academic_year',
            'student_fee_package.com_class',
            'student_fee_package.section',
            'student_invoice_items.fee_charges.fee_charges_type',
            'invoice_type',
            'payment_source',
            'promo.promo_type',
            'fee_period',
        ]);
        $student_package_charges = FeePackagesFeeCharges::where(['fee_package_id' => $studentInvoice->student_fee_package->fee_package_id, 'status' => 1])->with(['fee_charges.fee_charges_type'])->get();
        $additional_charges = FeeCharge::where(['branch_id' => $studentInvoice->student->branch_id])->whereNull('fee_package_id')->with('fee_charges_type')->get();

        $packages_charges = array();
        foreach ($student_package_charges->toArray() as $charges) {
            $temp = false;
            foreach ($studentInvoice->student_invoice_items as $item) {
                if ($charges['fee_charges']['id'] == $item->fee_charges->id) {
                    $temp = true;
                    $selected = array_merge($charges, ['selected' => true]);
                    $packages_charges = array_merge($packages_charges, [$selected]);
                    break;
                }
            }
            if (!$temp) {
                $selected = array_merge($charges, ['selected' => false]);
                $packages_charges = array_merge($packages_charges, [$selected]);
            }
        }

        $add_packages_charges = array();
        foreach ($additional_charges->toArray() as $charges) {
            $temp = false;
            foreach ($studentInvoice->student_invoice_items as $item) {
                if ($charges['id'] == $item->fee_charges->id) {
                    $temp = true;
                    $selected = array_merge($charges, ['selected' => true]);
                    $add_packages_charges = array_merge($add_packages_charges, [$selected]);
                    break;
                }
            }
            if (!$temp) {
                $selected = array_merge($charges, ['selected' => false]);
                $add_packages_charges = array_merge($add_packages_charges, [$selected]);
            }
        }
        // $max_paid_invoice = StudentInvoice::where('student_id', $studentInvoice['student_id'])
        //     ->where(['bank_payment_status' => 'paid'])->max('id');

        $adjusted_invoice = [];
        // if(isset($max_paid_invoice)){
        $adjusted_invoice = StudentInvoice::where(
            'student_id',
            $studentInvoice['student_id'],
        )->with([
                    'student',
                    'fee_period'
                    // ])->where('bank_payment_status', 'adjusted')->where('is_adjusted', '1')->where('id', '<', $max_paid_invoice)->get();
                ])->where('adjusted_invoice_id', $studentInvoice->id)->get();
        // }
        //    unpaid invoices
        $unpaid_invoice = StudentInvoice::where(
            'student_id',
            $studentInvoice['student_id'],
        )->with([
                    'student',
                    'fee_period'
                ])->where('bank_payment_status', 'unpaid')->where('is_adjusted', '0')->where('id', '<', $studentInvoice['id'])->get();
        $data = [
            'student_invoice' => $studentInvoice,
            'student_package_charges' => collect($packages_charges),
            'additional_charges' => collect($add_packages_charges),
            'calculation' => calculate_total_price_by_invoice($studentInvoice),
            'unpaid_invoice' => $unpaid_invoice,
            'adjusted_invoice' => $adjusted_invoice,
        ];

        return view('students.edit_invoice_info_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentInvoice  $studentInvoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentInvoice $studentInvoice)
    {
        // dd(isset($request->due_date_fine));
        $request->validate([
            'student_id' => 'required',
            'due_date' => 'required',
            //'issue_date' => 'required',
            'validity_date' => 'required',
            'student_fee_package_id' => 'required',
            'fee_charges_items' => 'required | array | min:1',
        ]);

        $updatedData = [
            'invoice_type_id' => $request->invoice_type_id,
            'due_date' => $request->due_date,
            'issue_date' => $request->issue_date,
            'validity_date' => $request->validity_date,
        ];

        $updatedData['due_date_fine'] = isset($request->due_date_fine);
        $updatedData['arrears_fine'] = isset($request->arrears_fine);

        //if want to update royalty against an invoice on update then uncomment this
        /*$student = Student::find($request->student_id);
        $updatedData['royalty_percentage'] = get_branch_royalty($student->branch_id);*/

        if (isset($request->update_active_package)) {
            $student_fee_package = StudentFeePackage::where(['student_id' => $request->student_id, 'is_valid' => 1])->first();
            $updatedData['student_fee_package_id'] = $student_fee_package->id;
        }

        $studentInvoice->update($updatedData);

        // For Adding New Items;
        for ($i = 0; $i < count($request->fee_charges_items); $i++) {
            $fee_charge = FeeCharge::find($request->fee_charges_items[$i]);
            $existing_charge = StudentInvoiceItem::where([
                'fee_charge_id' => $request->fee_charges_items[$i],
                'student_invoice_id' => $studentInvoice->id,
            ])->exists();

            if ($existing_charge) {
                StudentInvoiceItem::where([
                    'fee_charge_id' => $request->fee_charges_items[$i],
                    'student_invoice_id' => $studentInvoice->id,
                ])->update([
                            'debit' => $request->invoice_type_id == 1 ? $fee_charge->amount : null,
                            'credit' => $request->invoice_type_id == 2 ? $fee_charge->amount : null,
                        ]);
            } else {
                StudentInvoiceItem::create([
                    'student_invoice_id' => $studentInvoice->id,
                    'fee_charge_id' => $request->fee_charges_items[$i],
                    'debit' => $request->invoice_type_id == 1 ? $fee_charge->amount : null,
                    'credit' => $request->invoice_type_id == 2 ? $fee_charge->amount : null,
                ]);
            }
        }

        // For Deleting Old Items
        foreach ($studentInvoice->student_invoice_items()->get()->toArray() as $existing_items) {
            $temp = false;
            $excluded_item = 0;
            foreach ($request->fee_charges_items as $new_items) {
                $excluded_item = $existing_items['fee_charge_id'];
                if ($new_items == $existing_items['fee_charge_id']) {
                    $temp = true;
                }
            }
            if (!$temp)
                StudentInvoiceItem::where([
                    'fee_charge_id' => $excluded_item,
                    'student_invoice_id' => $studentInvoice->id,
                ])->delete();
        }

        return redirect(route('students.edit', $request->student_id) . '?tab=invoice')->with('success', 'Invoice updated succesfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentInvoice  $studentInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentInvoice $studentInvoice)
    {
        //
    }

    public function updateUnpaidInvoices(Request $request)
    {
        $id = explode(",", $request->unpaid_invoice);
        foreach ($id as $invoice_id) {
            // dd($invoice_id);

            StudentInvoice::where([
                'id' => $invoice_id,
            ])->update([
                        'is_adjusted' => 1,
                        'bank_payment_status' => 'adjusted',
                        'remarks' => $request->remarks,
                        'adjusted_invoice_id' => $request->current_invoice_id,
                    ]);
        }
    }
    public function updateAdjustedInvoices(Request $request)
    {
        $id = explode(",", $request->adjusted_invoice);
        foreach ($id as $invoice_id) {
            // dd($invoice_id);

            StudentInvoice::where([
                'id' => $invoice_id,
            ])->update([
                        'is_adjusted' => 0,
                        'bank_payment_status' => 'unpaid',
                        'remarks' => null,
                        'adjusted_invoice_id' => null,
                        'bank_payment_status' => 'unpaid',
                    ]);
        }
    }

    public function getStudentInvoice($studentId)
    {
        $studentInvoice = StudentInvoice::where(
            'student_id',
            $studentId
        )->with([
                    'student',
                    'student_fee_package.fee_package',
                    'student_fee_package.fee_concession.fee_concession_type',
                    'student_fee_package.academic_year',
                    'student_fee_package.com_class',
                    'student_fee_package.section',
                    'student_invoice_items.fee_charges.fee_charges_type',
                    'invoice_type',
                    'payment_source',
                    'promo.promo_type',
                    'fee_period'
                ]);

        if (!$studentInvoice->exists())
            return $studentInvoice->exists();
        return $studentInvoice->first();
    }

    public function totalPrice($studentInvoice)
    {
        // dd($studentInvoice->toArray());
        // return $studentInvoice->invoice_no;

        $totalPrice = 0;
        $non_refund_discountable_charges = 0;

        // dd($studentInvoice['student_invoice_items']->toArray());

        if (isset($studentInvoice['student_invoice_items'])) {
            foreach ($studentInvoice['student_invoice_items'] as $student_invoice_item) {
                $student_item_amount = isset($student_invoice_item['debit']) ? $student_invoice_item['debit'] : $student_invoice_item['credit'];
                $totalPrice = $totalPrice + $student_item_amount;
                $non_refund_discountable_charges = $student_invoice_item['fee_charges']['is_discountable'] && !$student_invoice_item['fee_charges']['is_refundable'] ? $non_refund_discountable_charges + $student_item_amount : $non_refund_discountable_charges + 0;
            }
        }

        $data = [
            'sub_total' => $totalPrice,
            'non_refund_non_discountable_charges' => $non_refund_discountable_charges
        ];
        // Fee Concession Calculation
        if (isset($studentInvoice['student_fee_package']['fee_concession']) && !empty($studentInvoice['student_fee_package']['fee_concession'])) {
            $fee_concession = $studentInvoice['student_fee_package']['fee_concession'];
            $concessionDiscount = ($non_refund_discountable_charges / 100) * $fee_concession['concession_percentage'];

            $data['concession_type'] = $fee_concession['fee_concession_type']['name'];
            $data['concession_percentage'] = $fee_concession['concession_percentage'];
            $data['concession_discount'] = $concessionDiscount;
        } else {
            $concessionDiscount = 0;
            $data['concession_type'] = '';
            $data['concession_percentage'] = 0;
            $data['concession_discount'] = 0;
        }

        // Promo Discount Calculation
        if (isset($studentInvoice->promo) && !empty($studentInvoice->promo)) {
            $promoClasses = PromoClass::where('promo_id', $studentInvoice->promo->id)->get();
            // dd($promoClasses->toArray());

            $promoApplicable = false;
            // dd($promoClasses->toArray());

            foreach ($promoClasses as $key => $value) {
                if ($value->class_id == $studentInvoice->student_fee_package->com_class->id) {
                    $promoApplicable = true;
                    break;
                }
            }

            if ($promoApplicable) {
                $promoDiscount = $studentInvoice->promo->promo_unit == 'percentage' ? ($totalPrice / 100) * $studentInvoice->promo->promo_amount : $studentInvoice->promo->promo_amount;

                $data['promo_name'] = $studentInvoice->promo->name;
                $data['promo_unit'] = $studentInvoice->promo->promo_unit;
                $data['promo_amount'] = $studentInvoice->promo->promo_amount;
                $data['promo_discount'] = $promoDiscount;
            } else {
                $promoDiscount = 0;
            }
        } else {
            $promoDiscount = 0;
        }

        $data['royalty_percentage'] = get_branch_royalty($studentInvoice['student']['branch_id']);
        $non_refundable_royalty = $non_refund_discountable_charges * ($data['royalty_percentage'] / 100);
        $concession_royalty = $concessionDiscount * ($data['royalty_percentage'] / 100);
        $data['royalty_amount'] = $non_refundable_royalty - $concession_royalty;

        $data['total'] = $totalPrice - ($concessionDiscount + $promoDiscount);
        $data['total_after_royalty'] = $totalPrice - ($concessionDiscount + $promoDiscount) - $data['royalty_amount'];



        $diff_month = 1;
        if (isset($studentInvoice['fee_period']) && !empty($studentInvoice['fee_period']['to_date']) && !empty($studentInvoice['fee_period']['from_date'])) {
            $to_date = Carbon::parse($studentInvoice['fee_period']['to_date'])->addDays(2);
            $diff_month = Carbon::parse($studentInvoice['fee_period']['from_date'])->diffInMonths($to_date);
        }

        //multiplication based on no of months invoice
        $data['sub_total'] = $data['sub_total'] * $diff_month;
        $data['non_refund_non_discountable_charges'] = $data['non_refund_non_discountable_charges'] * $diff_month;
        $data['concession_discount'] = $data['concession_discount'] * $diff_month;
        $data['royalty_amount'] = $data['royalty_amount'] * $diff_month;
        $data['total'] = $data['total'] * $diff_month;
        $data['diff_month'] = $diff_month;

        return $data;
    }

    public function generateChallan(StudentInvoice $studentInvoice)
    {
        try {
            // Use the already loaded model instance instead of querying again
            $studentInvoice->load([
                'student.branch.class_group',
                'student.branch.bank_accounts',
                'student.city',
                'student_fee_package.fee_package',
                'student_fee_package.fee_concession.fee_concession_type',
                'student_fee_package.academic_year',
                'student_fee_package.com_class',
                'student_fee_package.section',
                'student_invoice_items.fee_charges.fee_charges_type',
                'invoice_type',
                'payment_source',
                'promo.promo_type',
                'fee_period',
            ]);

            $data['studentInvoice'] = $studentInvoice;

            // Check if bank accounts exist
            if (!$studentInvoice->student?->branch?->bank_accounts?->count()) {
                return redirect()->back()->with('error', 'Bank Account Not Available');
            }

            // Use the new calculateTotalPrice method instead of the old helper functions
            $data['calculations'] = $this->calculateInvoiceTotals($studentInvoice);

            // Generate PDF with better memory management
            $pdf = Pdf::loadView('students.student_challan', ['invoices' => [$data]])
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'dpi' => 150,
                    'defaultFont' => 'sans-serif',
                    'isRemoteEnabled' => false,
                    'chroot' => public_path(),
                ]);

            $filename = ($studentInvoice->student->last_name ?? 'student') . ' Challan.pdf';

            return $pdf->stream($filename);

        } catch (Exception $e) {
            Log::error('Challan generation failed: ' . $e->getMessage(), [
                'invoice_id' => $studentInvoice->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to generate challan. Please try again.');
        }
    }

    public function reportRoyaltyComputation(Request $request)
    {
        $students = [];

        $total_fee_charges = [
            'tution' => 0,
            'admission' => 0,
            'security' => 0,
            'total' => 0,
            'total_royalty' => 0,
            'nwa_amount' => 0,
        ];
        if (!empty($request->filters['state_id'])) {
            $return_data = StudentInvoice::royaltyComputation($request);
            $students = $return_data['students'];
            $total_fee_charges = $return_data['total_fee_charges'];
        }

        if ($request->ajax()) {
            return DataTables::of($students)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    return $row->student->first_name . ' ' . $row->student->last_name;
                })
                ->addColumn('invoice_no', function ($row) {
                    return view('students.invoice_list_link', ['row' => $row]);
                })
                ->addColumn('fee_period', function ($row) {
                    return get_month_name($row->fee_period->from_date) == get_month_name($row->fee_period->to_date) ? get_month_name($row->fee_period->from_date) : get_month_name($row->fee_period->from_date) . ' - ' . get_month_name($row->fee_period->to_date);
                })
                ->addColumn('admission_wef', function ($row) {
                    return parse_date($row['student']['admission_wef'], 'd-M-y');
                })
                ->addColumn('admission_fees', function ($row) {
                    $fees_calc = calculate_total_price_by_invoice($row);
                    $admission_fees = $fees_calc['invoices_charges']['AF'];
                    return number_format($admission_fees);
                })
                ->addColumn('tution_fees', function ($row) {
                    $fees_calc = calculate_total_price_by_invoice($row);
                    $tution_fees = $fees_calc['invoices_charges']['TF'];
                    return number_format($tution_fees);
                })
                ->addColumn('security_fees', function ($row) {
                    $fees_calc = calculate_total_price_by_invoice($row);
                    $security_fees = $fees_calc['invoices_charges']['SD'];
                    return number_format($security_fees);
                })
                ->addColumn('total_fees', function ($row) {
                    $fees_calc = calculate_total_price_by_invoice($row);
                    $total = $fees_calc['total'];
                    return number_format($total);
                })
                ->addColumn('royalty', function ($row) {
                    $fees_calc = calculate_total_price_by_invoice($row);
                    return number_format($fees_calc['royalty_amount']);
                })
                ->addColumn('nwa_amount', function ($row) {
                    $fees_calc = calculate_total_price_by_invoice($row);
                    return number_format($fees_calc['total_after_royalty']);
                })
                ->addColumn('is_paid', function ($row) {
                    $badge = ($row->bank_payment_status == 'paid' ? '<span class="badge bg-success">Paid</span>' : ($row->bank_payment_status == 'unpaid' ? '<span class="badge bg-warning">Unpaid</span>' : ($row->bank_payment_status == 'cancelled' ? '<span class="badge bg-danger">Cancelled</span>' : '<span class="badge bg-secondary">pending</span>')));
                    return $badge;
                })
                ->addColumn('edit_invoice', function ($row) {
                    $btn = '
                    <a target="_blank" href="' . route('student-invoices.edit', $row->id) . '" class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                        <i class="mdi mdi-lead-pencil"></i>
                    </a>';
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    return view('students.bulk_invoices.bulk_students_action', ['row' => $row]);
                })
                ->rawColumns(['full_name', 'is_paid', 'action', 'edit_invoice'])
                ->with($total_fee_charges)
                ->make(true);
        }

        $branches = Branch::all();
        $regions = Region::all();
        $states = State::all();
        $classes = ComClass::all();
        $sections = Section::all();
        $fee_periods = FeePeriod::all();
        $academic_years = AcademicYear::all();

        $branchIds = Branch::all()->pluck('id');

        return view('reports.royalty_computation', [
            'branches' => $branches,
            'regions' => $regions,
            'states' => $states,
            'classes' => $classes,
            'sections' => $sections,
            'fee_periods' => $fee_periods,
            'branchIds' => $branchIds,
            'total_fee_charges' => $total_fee_charges,
            'academic_years' => $academic_years
        ]);
    }

    public function reportRoyaltyComputationReport(Request $request)
    {
        $students = [];
        $branches = Branch::all()->pluck('id');

        // if (isset($request->sections)) {
        //     $student_ids = ClassStudent::where('is_valid', 1)->whereIn('branch_class_section_id', $request->sections)->get()->pluck('students.id');
        //     // dd($list->toArray());
        // } else if (!isset($request->sections) && isset($request->classes)) {
        //     $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
        //         $query->whereHas('com_classes', function ($subquery) use ($request) {
        //             $subquery->whereIn('branch_id', $request->branches)->whereIn('class_id', $request->classes);
        //         });
        //     })->get()->pluck('students.id');
        // } else {
        //     // $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
        //     //     $query->whereHas('com_classes', function ($subquery) use ($request) {
        //     //         $subquery->whereIn('branch_id', $request->branches);
        //     //     });
        //     // })->get()->pluck('students.id');

        //     $students = Student::where('branch_id', 1)->with([
        //         'student_invoices.student_fee_package.fee_package.fee_packages_fee_charges.fee_charges'
        //     ])->whereHas('student_invoices', function ($query) {
        //         $query->where('is_paid',1);
        //     })->get();
        // }

        $students = Student::whereIn('branch_id', $branches)->with([
            'student_invoices.student_fee_package.fee_package.fee_packages_fee_charges.fee_charges',
            'student_invoices' => function ($query) {
                $query->where('is_paid', 1);
            }
        ])->get();

        // foreach ($student_ids as $student_id) {
        //     $student_active_class = Student::where('id', $student_id)->with('active_class.branch_class_sections')->first();

        //     $student = Student::where('id', $student_id)->whereHas('student_invoices', function ($query) use ($student_active_class) {
        //         $query->where(['is_paid' => 1, 'invoice_frequency' => 'Admission'])->whereHas('student_fee_package', function ($query) use ($student_active_class) {
        //             $query->where([
        //                 'academic_year_id' => $student_active_class->active_class->academic_year_id,
        //                 'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id,
        //                 'section_id' => $student_active_class->active_class->branch_class_sections->sections->id
        //             ]);
        //         });
        //     })->with(['active_class.branch_class_sections.sections', 'active_class.branch_class_sections.com_classes'])->first();

        //     if (isset($student)) $students = array_merge($students, [$student->toArray()]);
        // }

        // dd($students->toArray());
        return DataTables::of($students)
            ->addIndexColumn()
            ->addColumn('full_name', function ($row) {
                return $row->first_name . ' ' . $row->last_name;
                // return view('students.student_image_tr', ['row' => $row]);
            })
            ->addColumn('invoices', function ($row) {
                return $row->student_invoices->count();
            })
            ->addColumn('tution_fees', function ($row) {
                $tution_fees = 0;
                foreach ($row->student_invoices as $invoice) {
                    $fees_calc = calculate_total_price_by_invoice($invoice);
                    $tution_fees += $fees_calc['invoices_charges']['TF'];
                }
                return $tution_fees;
            })
            ->addColumn('admission_fees', function ($row) {
                $admission_fees = 0;
                foreach ($row->student_invoices as $invoice) {
                    $fees_calc = calculate_total_price_by_invoice($invoice);
                    $admission_fees += $fees_calc['invoices_charges']['AF'];
                }
                return $admission_fees;
            })
            ->addColumn('security_fees', function ($row) {
                $security_fees = 0;
                foreach ($row->student_invoices as $invoice) {
                    $fees_calc = calculate_total_price_by_invoice($invoice);
                    $security_fees += $fees_calc['invoices_charges']['SD'];
                }
                return $security_fees;
            })
            ->addColumn('total_fees', function ($row) {
                $total = 0;
                foreach ($row->student_invoices as $invoice) {
                    $fees_calc = calculate_total_price_by_invoice($invoice);
                    $total += $fees_calc['total'];
                }
                return $total;
            })
            ->addColumn('action', function ($row) {
                return view('students.bulk_invoices.bulk_students_action', ['row' => $row]);
            })
            ->rawColumns(['full_name', 'action'])
            ->make(true);
    }

    public function reportRoyaltyReimbursement(Request $request)
    {
        return view('reports.royalty_reimbursement');
        if ($request->ajax()) {
            $data = Branch::with([
                'students'
            ])->get();

            return DataTables::of($data)
                ->addColumn('students_strength', function ($row) {
                    return count($row->students);
                })
                ->addColumn('total_fees', function ($row) {
                    $totalFees = 0;
                    if (count($row->students) == 0)
                        return 0;

                    foreach ($row->students as $student => $value) {
                        $invoice = $this->getStudentInvoice($value->id);
                        if ($invoice == false)
                            return 0;
                        $calculation = calculate_total_price_by_invoice($invoice);
                        $totalFees += $calculation['total'];
                    }
                    return $totalFees;
                })
                ->addColumn('total_tax', function ($row) {
                    $totalTax = 0;
                    if (count($row->students) == 0)
                        return 0;

                    foreach ($row->students as $student => $value) {
                        $invoice = $this->getStudentInvoice($value->id);
                        if ($invoice == false)
                            return 0;
                        $calculation = calculate_total_price_by_invoice($invoice);
                        $totalTax += $calculation['total'];
                    }
                    return $totalTax;
                })
                ->addColumn('total_royalty', function ($row) {
                    $totalRoyalty = 0;
                    if (count($row->students) == 0)
                        return 0;

                    foreach ($row->students as $student => $value) {
                        $invoice = $this->getStudentInvoice($value->id);
                        if ($invoice == false)
                            return 0;
                        $calculation = calculate_total_price_by_invoice($invoice);
                        $totalRoyalty += $calculation['royalty_amount'];
                    }
                    return $totalRoyalty;
                })
                ->addColumn('action', function ($row) {
                    return view('students.invoice_actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'students_strength', 'total_fees'])
                ->make(true);
        }
    }

    public function paymentStatusModal(Request $request, StudentInvoice $studentInvoice)
    {
        return view('students.invoice_status_modal', ['student_invoice' => $studentInvoice]);
    }

    public function updatePaymentStatus(Request $request, StudentInvoice $studentInvoice)
    {
        // dd($request->all());
        \DB::beginTransaction();
        $this->validate(request(), [
            'paid_date' => 'required',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        $paidAmount = $request->input('paid_amount');
        $paidDate = $request->input('paid_date');
        $remarks = $request->input('remarks');
        $paymentMethod = $request->input('payment_method', 'cash'); // Default to cash if not provided
        $paymentReference = $request->input('payment_reference', 'Manual Payment');

        try {
            if ($request->payment_status == 'paid') {
                // Create payment record in normalized structure
                $payment = StudentPayment::create([
                    'student_id' => $studentInvoice->student_id,
                    'invoice_id' => $studentInvoice->id,
                    'amount' => $paidAmount,
                    'payment_date' => Carbon::parse($paidDate)->format('Y-m-d'),
                    'method' => $paymentMethod,
                    'reference' => $paymentReference,
                    'remarks' => $remarks
                ]);

                // Clear arrears with this payment
                ArrearsService::clearArrearsWithPayment($payment);

                // Check if this is a partial payment or overpayment
                $totalPayable = $studentInvoice->total_payable ?? 0;
                $totalPaid = $studentInvoice->payments()->sum('amount');

                // Determine payment status and handle arrears/advance
                if ($totalPaid >= $totalPayable) {
                    if ($totalPaid > $totalPayable) {
                        // Overpayment - calculate actual overpayment amount
                        $overpayment = $totalPaid - $totalPayable;

                        // Check if there's already an advance record for this invoice
                        $existingAdvance = StudentArrearsHistory::where('from_invoice_id', $studentInvoice->id)
                            ->whereNull('cleared_date')
                            ->where('amount', '<', 0) // Advance records have negative amounts
                            ->first();

                        if ($existingAdvance) {
                            // Update existing advance record
                            $existingAdvance->update(['amount' => -$overpayment]);
                        } else {
                            // Create new advance record only if there isn't one already
                            StudentArrearsHistory::create([
                                'student_id' => $studentInvoice->student_id,
                                'from_invoice_id' => $studentInvoice->id,
                                'to_invoice_id' => null,
                                'amount' => -$overpayment, // Negative amount indicates advance
                                'carried_date' => Carbon::now(),
                                'cleared_by_payment_id' => null,
                                'cleared_date' => null,
                            ]);
                        }

                        $studentInvoice->update([
                            'bank_payment_status' => 'overpaid',
                            'is_paid' => 1,
                            'paid_date' => $paidDate,
                            'remarks' => $remarks . ' (Overpaid by ' . number_format($overpayment, 2) . ')',
                        ]);
                    } else {
                        // Full payment (exact amount)
                        $studentInvoice->update([
                            'bank_payment_status' => 'paid',
                            'is_paid' => 1,
                            'paid_date' => $paidDate,
                            'remarks' => $remarks,
                        ]);

                        // Clear any existing arrears/advance for this invoice
                        $existingArrears = StudentArrearsHistory::where('from_invoice_id', $studentInvoice->id)
                            ->whereNull('cleared_date')->first();
                        if ($existingArrears) {
                            $existingArrears->update(['cleared_date' => Carbon::now()]);
                        }
                    }
                } else {
                    // Partial payment - always track as arrears regardless of validity date
                    $remaining = $totalPayable - $totalPaid;
                    $studentInvoice->update([
                        'bank_payment_status' => 'partially_paid',
                        'is_paid' => 0,
                        'paid_date' => $paidDate,
                        'remarks' => $remarks . ' (Partial payment - ' . number_format($totalPaid, 2) . ' of ' . number_format($totalPayable, 2) . ')',
                    ]);

                    // Create or update arrears record for the remaining amount
                    $arrears = StudentArrearsHistory::where('from_invoice_id', $studentInvoice->id)
                        ->whereNull('cleared_date')
                        ->where('amount', '>', 0) // Arrears records have positive amounts
                        ->first();
                    if ($arrears) {
                        $arrears->update(['amount' => $remaining]);
                    } else {
                        StudentArrearsHistory::create([
                            'student_id' => $studentInvoice->student_id,
                            'from_invoice_id' => $studentInvoice->id,
                            'to_invoice_id' => null,
                            'amount' => $remaining,
                            'carried_date' => Carbon::now(),
                            'cleared_by_payment_id' => null,
                            'cleared_date' => null,
                        ]);
                    }
                }

                // Handle admission-specific logic
                if ($studentInvoice->invoice_frequency == 'Admission') {
                    // Use the new helper method for consistent admission to monthly transition
                    $transitionResult = $this->handleAdmissionToMonthlyTransition($studentInvoice->student_id, $studentInvoice->id);
                    
                    if ($transitionResult['success']) {
                        Log::info('Admission to monthly transition completed successfully in updatePaymentStatus', [
                            'student_id' => $studentInvoice->student_id,
                            'invoice_id' => $studentInvoice->id,
                            'result' => $transitionResult
                        ]);
                    } else {
                        Log::warning('Admission to monthly transition failed in updatePaymentStatus', [
                            'student_id' => $studentInvoice->student_id,
                            'invoice_id' => $studentInvoice->id,
                            'result' => $transitionResult
                        ]);
                    }
                }

            } elseif ($request->payment_status == 'cancelled') {
                // Update invoice status to cancelled
                $studentInvoice->update([
                    'bank_payment_status' => 'cancelled',
                    'is_paid' => 0,
                    'paid_date' => null,
                    'remarks' => $remarks,
                ]);

                // Remove from ledger
                if ($studentInvoice->ledger_entry) {
                    $studentInvoice->ledger_entry->update([
                        'student_invoice_id' => null
                    ]);
                }

                $studentInvoice->arrears_history()->update([
                    'to_invoice_id' => null,
                    'cleared_date' => null,
                    'cleared_by_payment_id' => null,
                ]);

            } else {
                // For other statuses (like 'unpaid'), just update the invoice
                $studentInvoice->update([
                    'bank_payment_status' => $request->payment_status,
                    'is_paid' => 0,
                    'paid_date' => null,
                    'remarks' => $remarks,
                ]);
            }

            \DB::commit();
            return 'Payment Updated Successfully.';

        } catch (Exception $e) {
            \DB::rollback();
            return 'Error updating payment: ' . $e->getMessage();
        }
    }

    /*public function bulkInvoiceView()
    {
        if (Auth::user()->hasRole('super_admin')) {
            $branches = Branch::with('students')->get();
            if ($branch_id = get_branch_id())
                $classes = get_branch_classes($branch_id);
            else
                $classes = ComClass::all();

            $academic_years = AcademicYear::all();
            $data = [
                'branches' => $branches,
                'classes' => $classes,
                'academic_years' => $academic_years,
            ];
            return view('students.bulk_invoices.admin_bulk_invoices', $data);
        } else {
            $branches = Branch::with('students')->get();
            if ($branch_id = get_branch_id())
                $classes = get_branch_classes($branch_id);
            else
                $classes = ComClass::all();

            $data = [
                'branches' => $branches,
                'classes' => $classes,
            ];
            // dd($data);

            // dd($acad->toArray());

            $academic_year = get_current_acad_year_by_branch_id(get_branch_id());
            if (!isset($academic_year))
                $academic_year = BranchAcademicYear::where('start_date', '>', Carbon::now())->orderBy('start_date', 'asc')->first();

            $fee_packages = [];
            $fee_periods = [];

            if (isset($academic_year)) {
                $fee_packages = FeePackage::where(['branch_id' => get_branch_id(), 'academic_year_id' => $academic_year->academic_year_id])->whereHas('fee_package_type', function ($query) {
                    $query->where('name', 'Monthly');
                })->get();
                $fee_periods = FeePeriod::where(['branch_id' => get_branch_id(), 'academic_year_id' => $academic_year->academic_year_id])->get();
                session()->forget('acad_error');
            } else {
                session()->put('acad_error', 'Academic year doesn\'t exist in branch.');
            }

            $academic_years = AcademicYear::all();

            $data['fee_packages'] = $fee_packages;
            $data['fee_periods'] = $fee_periods;
            $data['academic_years'] = $academic_years;

            return view('students.bulk_invoices.bulk_invoices', $data);
        }
    }*/

    public function bulkInvoiceView(Request $request)
    {
        // Check if user wants to access admin interface (for changing invoice status)
        // or regular interface (for generating new invoices)
        $isAdminInterface = $request->has('admin_interface') || $request->is('bulk-invoices');

        if (Auth::user()->hasRole('super_admin') && $isAdminInterface) {
            // Show admin interface for changing invoice status
            $branches = Branch::with('students')->get();
            if ($branch_id = get_branch_id())
                $classes = get_branch_classes($branch_id);
            else
                $classes = ComClass::all();

            $academic_years = AcademicYear::all();
            $data = [
                'branches' => $branches,
                'classes' => $classes,
                'academic_years' => $academic_years,
            ];
            return view('students.bulk_invoices.admin_bulk_invoices', $data);
        } else {
            $branches = Branch::with('students')->get();
            if ($branch_id = get_branch_id())
                $classes = get_branch_classes($branch_id);
            else
                $classes = ComClass::all();

            $data = [
                'branches' => $branches,
                'classes' => $classes,
            ];
            // dd($data);

            // dd($acad->toArray());

            //$academic_year = get_current_acad_year_by_branch_id(get_branch_id());
            $active_academic_year = AcademicYear::where('active', 1)->first();
            $academic_year = $active_academic_year->id;
            //dd($academic_year);
            if (!isset($academic_year))
                $academic_year = BranchAcademicYear::where('start_date', '>', Carbon::now())->orderBy('start_date', 'asc')->first();

            $fee_packages = [];
            $fee_periods = [];

            if (isset($academic_year)) {
                $fee_packages = FeePackage::where(['branch_id' => get_branch_id(), 'academic_year_id' => $academic_year])->whereHas('fee_package_type', function ($query) {
                    $query->where('name', 'Monthly');
                })->get();
                $fee_periods = FeePeriod::where(['branch_id' => get_branch_id(), 'academic_year_id' => $academic_year])->get();
                session()->forget('acad_error');
            } else {
                session()->put('acad_error', 'Academic year doesn\'t exist in branch.');
            }

            $academic_years = AcademicYear::all();

            $data['fee_packages'] = $fee_packages;
            $data['fee_periods'] = $fee_periods;
            $data['academic_years'] = $academic_years;

            return view('students.bulk_invoices.bulk_invoices', $data);
        }
    }

    public function superAdminBulkInvoiceView(Request $request)
    {
        // Super Admin specific method for bulk invoice generation (not status change)
        $branches = Branch::with('students')->get();
        if ($branch_id = get_branch_id())
            $classes = get_branch_classes($branch_id);
        else
            $classes = ComClass::all();

        $data = [
            'branches' => $branches,
            'classes' => $classes,
        ];

        //$academic_year = get_current_acad_year_by_branch_id(get_branch_id());
        $active_academic_year = AcademicYear::where('active', 1)->first();
        $academic_year = $active_academic_year->id;
        //dd($academic_year);
        if (!isset($academic_year))
            $academic_year = BranchAcademicYear::where('start_date', '>', Carbon::now())->orderBy('start_date', 'asc')->first();

        $fee_packages = [];
        $fee_periods = [];

        if (isset($academic_year)) {
            $fee_packages = FeePackage::where(['branch_id' => get_branch_id(), 'academic_year_id' => $academic_year])->whereHas('fee_package_type', function ($query) {
                $query->where('name', 'Monthly');
            })->get();
            $fee_periods = FeePeriod::where(['branch_id' => get_branch_id(), 'academic_year_id' => $academic_year])->get();
            session()->forget('acad_error');
        } else {
            session()->put('acad_error', 'Academic year doesn\'t exist in branch.');
        }

        $academic_years = AcademicYear::all();

        $data['fee_packages'] = $fee_packages;
        $data['fee_periods'] = $fee_periods;
        $data['academic_years'] = $academic_years;

        return view('students.bulk_invoices.bulk_invoices', $data);
    }

    public function getDataForAdmin(Request $request)
    {
        $branch_id = $request->branch_id;
        $academic_year_id = $request->academic_year_id;
        $fee_packages = FeePackage::where(['branch_id' => $branch_id, 'academic_year_id' => $academic_year_id])->whereHas('fee_package_type', function ($query) {
            $query->where('name', 'Monthly');
        })->get();
        $fee_periods = FeePeriod::where(['branch_id' => $branch_id, 'academic_year_id' => $academic_year_id])->get();
        $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
        // dd($classes->toArray());
        $data = [
            'fee_packages' => $fee_packages,
            'fee_periods' => $fee_periods,
            'classes' => $classes,
        ];
        return view('students.bulk_invoices.admin_data', $data);
    }

    public function getInvoiceStudents(Request $request)
    {
        $student_ids = ClassStudent::where('is_valid', 1)->whereHas('branch_class_sections', function ($query) use ($request) {
            $query->whereHas('com_classes', function ($subquery) use ($request) {
                $subquery->where('branch_id', $request->branches)->where('class_id', $request->classes);
            });
        })->get()->pluck('students.id');
        $students = [];
        foreach ($student_ids as $student_id) {
            if (!empty($student_id)) {
                $student_active_class = Student::where('id', $student_id)->with('active_class.branch_class_sections')->first();

                $student = Student::where('id', $student_id)->whereHas('student_invoices', function ($query) use ($student_active_class) {
                    $query->where(['is_paid' => 1, 'invoice_frequency' => 'Admission'])->whereHas('student_fee_package', function ($query) use ($student_active_class) {
                        $query->where([
                            'academic_year_id' => $student_active_class->active_class->academic_year_id,
                            'com_class_id' => $student_active_class->active_class->branch_class_sections->com_classes->id,
                            'section_id' => $student_active_class->active_class->branch_class_sections->sections->id
                        ]);
                    });
                })->with(['active_class.branch_class_sections.sections', 'active_class.branch_class_sections.com_classes'])->first();

                if (isset($student))
                    $students = array_merge($students, [$student->toArray()]);
            }
        }
        return DataTables::of($students)
            ->addIndexColumn()
            ->addColumn('full_name', function ($row) {
                return view('students.student_image_tr', ['row' => $row]);
            })
            ->addColumn('invoice_status', function ($row) use ($request) {
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice))
                    return '<span class="badge bg-danger">Already Generated</span>';
                return '<span class="badge bg-primary">No Invoice</span>';
            })
            ->addColumn('invoice_no', function ($row) use ($request) {
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice))
                    return view('students.invoice_list_link', ['row' => $checkInvoice]);
                return '-';
            })
            ->addColumn('action', function ($row) use ($request) {
                $check_disable = true;
                $checkInvoice = StudentInvoice::where(['student_id' => $row['id'], 'fee_period_id' => $request['filters']['feePeriodInput']])->where('bank_payment_status', '!=', 'cancelled')->first();
                if (isset($checkInvoice))
                    $check_disable = false;

                return view('students.bulk_invoices.admin_bulk_students_action', ['row' => $row, 'check_disable', $check_disable]);
            })
            ->rawColumns(['full_name', 'invoice_status', 'action'])
            ->make(true);
    }

    public function changeBulkInvoiceStatus(Request $request)
    {

        $paidDate = $request->input('paid_date');
        $request->validate([
            "students" => 'required',
            "fee_period" => 'required',
        ]);
        // dd($request->all());



        foreach ($request->students as $student) {
            $studentInvoice = StudentInvoice::where([
                'student_id' => $student,
                //'student_fee_package_id' => $request->student_fee_package_id,
                'fee_period_id' => $request->fee_period,
            ])->where('bank_payment_status', '!=', 'cancelled')->first();
            if ($studentInvoice)
                $studentInvoice->update([
                    'bank_payment_status' => 'paid',
                    'is_paid' => 1,
                    'paid_date' => $paidDate
                ]);
            else
                dump("Hello");
        }
        ;
    }
    public function generateBulkInvoices(Request $request)
    {
        $request->validate([
            "students" => 'required',
            "fee_package" => 'required',
            "fee_period" => 'required',
            "academic_year_id" => 'required',
        ]);

        $fee_period = FeePeriod::where('id', $request->fee_period)->first();
        $fee_package = FeePackage::where('id', $request->fee_package)->first();
        $fee_package_charges = FeePackagesFeeCharges::where(['fee_package_id' => $request->fee_package, 'status' => 1])->with(['fee_charges.fee_charges_type'])->get();
        $invoice_type = InvoiceType::where('name', 'Receivable')->first();
        //$academic_year = get_current_acad_year_by_branch_id(get_branch_id());
        $academic_year = $request->academic_year_id;
        $invoice_month = Carbon::parse($fee_period->from_date)->format('m');

        // $invoices = [];
        foreach ($request->students as $student) {
            $check_existing_invoice = StudentInvoice::where([
                'student_id' => $student,
                //'student_fee_package_id' => $request->student_fee_package_id,
                'fee_period_id' => $request->fee_period,
            ])->where('bank_payment_status', '!=', 'cancelled')->first();

            if (!$check_existing_invoice) {
                $student_active_class = ClassStudent::where([
                    'academic_year_id' => $academic_year,
                    'student_id' => $student,
                    'is_valid' => 1
                ])->with([
                            'branch_class_sections.sections',
                            'branch_class_sections.com_classes'
                        ])->first();

                $check_fee_package = StudentFeePackage::where(['student_id' => $student, 'fee_package_id' => $request->fee_package, 'is_valid' => 1]);
                if ($check_fee_package->exists()) {
                    $student_fee_package = $check_fee_package->first();
                } else {
                    $student_fee_package_input = [
                        "fee_package_id" => $request->fee_package,
                        "fee_concession_id" => null,
                        "academic_year_id" => $academic_year,
                        "com_class_id" => $student_active_class->branch_class_sections->com_classes->id,
                        "section_id" => $student_active_class->branch_class_sections->sections->id,
                        "student_id" => $student
                    ];

                    StudentFeePackage::where(['student_id' => $student, 'is_valid' => 1])->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
                    $student_fee_package = StudentFeePackage::create($student_fee_package_input);
                }


                // "issue_date" => 'required',
                // "due_date" => 'required',
                // "validity_date" => 'required'

                // Calculate totals for the selected items
                $subtotal = 0;
                $totalDiscount = 0;
                $totalPayable = 0;

                foreach ($fee_package_charges as $fee_package_charge) {
                    $originalAmount = $fee_package_charge->fee_charges->amount ?? 0;
                    $subtotal += $originalAmount;

                    // Get student concession for this charge
                    $charge_concession = StudentConcession::where([
                        'student_id' => $student,
                        'fee_charge_id' => $fee_package_charge->fee_charge_id,
                        'is_valid' => 1
                    ])->first();

                    $discountPercentage = $charge_concession ? $charge_concession->fee_concession->concession_percentage : 0;
                    $discountAmount = ($originalAmount * $discountPercentage) / 100;
                    $totalDiscount += $discountAmount;
                }

                $totalPayable = $subtotal - $totalDiscount;

                // --- ARREARS AND ADVANCE PAYMENT LOGIC ---
                // 1. Get all uncleared arrears for this student (not yet assigned to a new invoice)
                $arrears = StudentArrearsHistory::where('student_id', $student)
                    ->whereNull('cleared_date')
                    ->whereNull('to_invoice_id')
                    ->where('amount', '>', 0) // Only positive amounts (arrears)
                    ->sum('amount');

                // 2. Get available advance payment for this student
                $advance = ArrearsService::getAdvancePaymentAmount($student);

                // 3. Calculate the new total payable
                $totalPayableWithArrears = $totalPayable + $arrears - $advance;
                $finalTotalPayable = max(0, $totalPayableWithArrears);
                $carriedAdvance = $advance > ($totalPayable + $arrears) ? $advance - ($totalPayable + $arrears) : 0;

                $student_invoice_input = [
                    'student_id' => $student,
                    'student_fee_package_id' => $student_fee_package->id,
                    'promo_id' => null,
                    'invoice_type_id' => $invoice_type->id,
                    'invoice_frequency' => 'Monthly',
                    'payment_source_id' => null,
                    // 'invoice_no' => rand(100000, 999999),
                    'is_paid' => false,
                    'paid_date' => null,
                    'fee_period_id' => $request->fee_period,
                    'due_date' => isset($request->due_date) ? $request->due_date : $fee_period['due_date'],
                    'issue_date' => isset($request->issue_date) ? $request->issue_date : $fee_period['issue_date'],
                    'validity_date' => isset($request->validity_date) ? $request->validity_date : $fee_period['valid_date'],
                    'bank_payment_status' => 'unpaid',
                    'subtotal' => $subtotal,
                    'total_discount' => $totalDiscount,
                    'total_payable' => $finalTotalPayable,
                    // Store arrears and advance information for reporting/UI
                    'arrears_included' => $arrears,
                    'advance_applied' => $advance,
                    'carried_advance' => $carriedAdvance,
                ];

                // $invoices = array_push($input);
                $request->student_id = $student;

                $student_invoice = StudentInvoice::create($student_invoice_input);

                // Carry forward all open arrears to this new invoice
                StudentArrearsHistory::where('student_id', $student)
                    ->whereNull('to_invoice_id')
                    ->whereNull('cleared_date')
                    ->update(['to_invoice_id' => $student_invoice->id]);

                // Check for advance payments and apply to this invoice
                $advanceAmount = ArrearsService::getAdvancePaymentAmount($student);
                if ($advanceAmount > 0) {
                    $amountToApply = min($advanceAmount, $finalTotalPayable);

                    // Apply advance to this invoice and clear the advance records
                    $actualAmountApplied = ArrearsService::applyAdvanceToInvoice($student, $student_invoice->id, $amountToApply);

                    if ($actualAmountApplied > 0) {
                        // Create a payment record for the advance amount
                        StudentPayment::create([
                            'student_id' => $student,
                            'invoice_id' => $student_invoice->id,
                            'amount' => $actualAmountApplied,
                            'payment_date' => Carbon::now(),
                            'method' => 'advance',
                            'reference' => 'Advance Payment',
                            'remarks' => 'Applied from previous overpayment'
                        ]);
                    }
                }

                foreach ($fee_package_charges as $fee_package_charge) {
                    $originalAmount = $fee_package_charge->fee_charges->amount ?? 0;

                    $charge_concession = StudentConcession::where([
                        'student_id' => $student,
                        'fee_charge_id' => $fee_package_charge->fee_charge_id,
                        'is_valid' => 1
                    ])->first();

                    $discountPercentage = $charge_concession ? $charge_concession->fee_concession->concession_percentage : 0;
                    $discountAmount = ($originalAmount * $discountPercentage) / 100;
                    $finalAmount = $originalAmount - $discountAmount;

                    $item_input = [
                        'student_invoice_id' => $student_invoice->id,
                        'fee_charge_id' => $fee_package_charge->fee_charge_id,
                        'debit' => $invoice_type->id == 1 ? $originalAmount : null,
                        'credit' => $invoice_type->id == 2 ? $originalAmount : null,
                        'concession' => $discountPercentage,
                        'concession_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                    ];

                    StudentInvoiceItem::create($item_input);
                }

                // Update student status from 'processing' to 'registered'
                $student = Student::find($request->student_id);
                if ($student && $student->status === 'processing') {
                    $student->update(['status' => 'registered']);
                }

                $current_ledger = StudentLedger::where(['student_id' => $request->student_id, 'class_student_id' => $student_active_class->id])->first();


                StudentLedgerInvoice::where([
                    'student_ledger_id' => $current_ledger->id,
                    'month' => $invoice_month
                ])->update([
                            'student_invoice_id' => $student_invoice->id,
                        ]);
            } else {
                dump("Hello");
            }
        }

        return redirect()->back()->with('success', 'Invoices created succesfully.');
    }

    public function generateBulkChallans(Request $request)
    {
        // Ensure Super Admin and other authorized roles have access
        if (
            !Auth::user()->hasRole(['super_admin', 'network_associate', 'finance-manager', 'accountant']) &&
            !Auth::user()->hasPermission(['list-generate-invoice', 'list-preview-invoice'])
        ) {
            abort(403, 'Unauthorized access to bulk challan generation.');
        }

        ini_set('max_execution_time', 180);
        $request->validate([
            'invoices' => 'required'
        ]);

        $invoicesIds = explode(',', $request->invoices);
        $invoices = generate_challans_pdf($invoicesIds);

        $pdf = Pdf::loadView('students.student_challan', ['invoices' => $invoices])->setPaper('a4', 'portrait');
        // return $pdf->download($data['studentInvoice']['student']['last_name'] . '_challan.pdf');
        return $pdf->stream('Invoice Challan.pdf');
    }

    /**
     * Enhanced bulk challan generation with package-based student selection
     */
    public function generateEnhancedBulkChallans(Request $request)
    {
        // Ensure Super Admin and other authorized roles have access
        if (
            !Auth::user()->hasRole(['super_admin', 'network_associate', 'finance-manager', 'accountant']) &&
            !Auth::user()->hasPermission(['list-generate-invoice', 'list-preview-invoice'])
        ) {
            abort(403, 'Unauthorized access to enhanced bulk challan generation.');
        }

        ini_set('max_execution_time', 300); // 5 minutes for large operations

        $request->validate([
            'fee_package_id' => 'required|exists:fee_packages,id',
            'fee_period_id' => 'required|exists:fee_periods,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'branch_id' => 'required|exists:branches,id',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'validity_date' => 'nullable|date'
        ]);

        try {
            DB::beginTransaction();

            $feePackage = FeePackage::with(['fee_package_type', 'from_class_id', 'to_class_id'])->findOrFail($request->fee_package_id);
            $feePeriod = FeePeriod::findOrFail($request->fee_period_id);
            $academicYear = AcademicYear::findOrFail($request->academic_year_id);

            // Ensure branch_id is set
            $branchId = $request->branch_id ?? get_branch_id();

            // Get fee package charges
            $feePackageCharges = FeePackagesFeeCharges::where('fee_package_id', $feePackage->id)
                ->with('fee_charges.fee_charges_type')
                ->get();

            if ($feePackageCharges->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No fee charges found for the selected package.'
                ], 400);
            }

            $generatedInvoices = [];
            $errors = [];
            $successCount = 0;

            foreach ($request->students as $studentId) {
                try {
                    $student = Student::with(['branch', 'active_class.branch_class_sections.com_classes', 'active_class.branch_class_sections.sections', 'active_class.branch_class_sections.branches'])
                        ->findOrFail($studentId);

                    // Check if student has active class
                    if (!$student->active_class) {
                        $errors[] = "Student {$student->full_name} has no active class.";
                        continue;
                    }

                    // Check if invoice already exists for this period
                    $existingInvoice = StudentInvoice::where([
                        'student_id' => $studentId,
                        'fee_period_id' => $request->fee_period_id,
                    ])->where('bank_payment_status', '!=', 'cancelled')->first();

                    if ($existingInvoice) {
                        $errors[] = "Invoice already exists for student {$student->full_name} for this period.";
                        continue;
                    }

                    // Handle fee package assignment - check if student needs new package
                    $studentFeePackage = $this->assignOrUpdateFeePackage($student, $feePackage, $academicYear);

                    if (!$studentFeePackage) {
                        $errors[] = "Failed to assign fee package to student {$student->full_name}.";
                        continue;
                    }

                    // Calculate total amount from package charges
                    $totalAmount = $this->calculatePackageAmount($student, $feePackageCharges);

                    // --- ARREARS AND ADVANCE PAYMENT LOGIC ---
                    // 1. Get all uncleared arrears for this student (not yet assigned to a new invoice)
                    $arrears = StudentArrearsHistory::where('student_id', $studentId)
                        ->whereNull('cleared_date')
                        ->whereNull('to_invoice_id')
                        ->where('amount', '>', 0) // Only positive amounts (arrears)
                        ->sum('amount');

                    // 2. Get available advance payment for this student
                    $advance = ArrearsService::getAdvancePaymentAmount($studentId);

                    // 3. Calculate the new total payable
                    $totalPayableWithArrears = $totalAmount + $arrears - $advance;
                    $finalTotalPayable = max(0, $totalPayableWithArrears);
                    $carriedAdvance = $advance > ($totalAmount + $arrears) ? $advance - ($totalAmount + $arrears) : 0;

                    // Create invoice
                    $invoiceData = [
                        'student_id' => $studentId,
                        'student_fee_package_id' => $studentFeePackage->id,
                        'promo_id' => null,
                        'invoice_type_id' => 1, // Assuming 1 is for debit invoices
                        'invoice_frequency' => $feePackage->fee_package_type->name,
                        'payment_source_id' => null,
                        'is_paid' => false,
                        'paid_date' => null,
                        'fee_period_id' => $request->fee_period_id,
                        'due_date' => $request->due_date ?? $feePeriod->due_date,
                        'issue_date' => $request->issue_date ?? $feePeriod->issue_date,
                        'validity_date' => $request->validity_date ?? $feePeriod->valid_date,
                        'bank_payment_status' => 'unpaid',
                        'subtotal' => $totalAmount,
                        'total_discount' => 0, // Will be calculated if concessions exist
                        'total_payable' => $finalTotalPayable,
                        // Store arrears and advance information for reporting/UI
                        'arrears_included' => $arrears,
                        'advance_applied' => $advance,
                        'carried_advance' => $carriedAdvance,
                    ];

                    $studentInvoice = StudentInvoice::create($invoiceData);

                    // Carry forward all open arrears to this new invoice
                    StudentArrearsHistory::where('student_id', $studentId)
                        ->whereNull('to_invoice_id')
                        ->whereNull('cleared_date')
                        ->update(['to_invoice_id' => $studentInvoice->id]);

                    // Check for advance payments and apply to this invoice
                    $advanceAmount = ArrearsService::getAdvancePaymentAmount($studentId);
                    if ($advanceAmount > 0) {
                        $amountToApply = min($advanceAmount, $finalTotalPayable);

                        // Apply advance to this invoice and clear the advance records
                        $actualAmountApplied = ArrearsService::applyAdvanceToInvoice($studentId, $studentInvoice->id, $amountToApply);

                        if ($actualAmountApplied > 0) {
                            // Create a payment record for the advance amount
                            StudentPayment::create([
                                'student_id' => $studentId,
                                'invoice_id' => $studentInvoice->id,
                                'amount' => $actualAmountApplied,
                                'payment_date' => Carbon::now(),
                                'method' => 'advance',
                                'reference' => 'Advance Payment',
                                'remarks' => 'Applied from previous overpayment'
                            ]);
                        }
                    }

                    // Create invoice items
                    foreach ($feePackageCharges as $feePackageCharge) {
                        $chargeConcession = StudentConcession::where([
                            'student_id' => $studentId,
                            'fee_charge_id' => $feePackageCharge->fee_charge_id,
                            'is_valid' => 1
                        ])->first();

                        $originalAmount = $feePackageCharge->fee_charges->amount ?? 0;
                        $discountPercentage = $chargeConcession ? $chargeConcession->fee_concession->concession_percentage : 0;
                        $discountAmount = ($originalAmount * $discountPercentage) / 100;
                        $finalAmount = $originalAmount - $discountAmount;

                        $itemData = [
                            'student_invoice_id' => $studentInvoice->id,
                            'fee_charge_id' => $feePackageCharge->fee_charge_id,
                            'debit' => $originalAmount,
                            'credit' => null,
                            'concession' => $discountPercentage,
                            'concession_amount' => $discountAmount,
                            'final_amount' => $finalAmount,
                        ];

                        StudentInvoiceItem::create($itemData);
                    }

                    // Update student ledger if exists
                    $currentLedger = StudentLedger::where([
                        'student_id' => $studentId,
                        'class_student_id' => $student->active_class->id
                    ])->first();

                    if ($currentLedger) {
                        $invoiceMonth = Carbon::parse($feePeriod->from_date)->format('m');
                        StudentLedgerInvoice::where([
                            'student_ledger_id' => $currentLedger->id,
                            'month' => $invoiceMonth
                        ])->update(['student_invoice_id' => $studentInvoice->id]);
                    }

                    // Update student status from 'processing' to 'registered'
                    if ($student->status === 'processing') {
                        $student->update(['status' => 'registered']);
                    }

                    // Handle admission-specific logic
                    if ($feePackage->fee_package_type->name === 'Admission') {
                        // Change status from 'processing' to 'registered'
                        if ($student->status === 'processing') {
                            $student->update(['status' => 'registered']);
                        }
                    }

                    $generatedInvoices[] = $studentInvoice->id;
                    $successCount++;

                } catch (Exception $e) {
                    $errors[] = "Error processing student ID {$studentId}: " . $e->getMessage();
                    Log::error('Bulk challan generation error for student ' . $studentId, [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully generated {$successCount} challans." .
                    (count($errors) > 0 ? " Errors: " . implode(', ', $errors) : ''),
                'generated_invoices' => $generatedInvoices,
                'errors' => $errors,
                'total_processed' => count($request->students),
                'success_count' => $successCount,
                'error_count' => count($errors)
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Bulk challan generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate bulk challans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign or update fee package for student
     */
    private function assignOrUpdateFeePackage($student, $feePackage, $academicYear)
    {
        try {
            // Check if student already has an active fee package
            $existingPackage = StudentFeePackage::where([
                'student_id' => $student->id,
                'is_valid' => 1
            ])->first();

            // If same package, return existing
            if ($existingPackage && $existingPackage->fee_package_id == $feePackage->id) {
                return $existingPackage;
            }

            // Deactivate existing package if different
            if ($existingPackage) {
                $existingPackage->update(['is_valid' => 0, 'active_till' => now()]);
            }

            // Create new package
            return StudentFeePackage::create([
                'student_id' => $student->id,
                'fee_package_id' => $feePackage->id,
                'fee_concession_id' => null,
                'academic_year_id' => $academicYear->id,
                'com_class_id' => $student->active_class->branch_class_sections->com_classes->id,
                'section_id' => $student->active_class->branch_class_sections->sections->id,
                'is_valid' => 1
            ]);

        } catch (Exception $e) {
            Log::error('Error assigning fee package to student', [
                'student_id' => $student->id,
                'fee_package_id' => $feePackage->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Calculate total amount from package charges
     */
    private function calculatePackageAmount($student, $feePackageCharges)
    {
        $totalAmount = 0;

        foreach ($feePackageCharges as $feePackageCharge) {
            $originalAmount = $feePackageCharge->fee_charges->amount ?? 0;

            // Check for student concession
            $chargeConcession = StudentConcession::where([
                'student_id' => $student->id,
                'fee_charge_id' => $feePackageCharge->fee_charge_id,
                'is_valid' => 1
            ])->first();

            if ($chargeConcession) {
                $discountPercentage = $chargeConcession->fee_concession->concession_percentage;
                $discountAmount = ($originalAmount * $discountPercentage) / 100;
                $finalAmount = $originalAmount - $discountAmount;
            } else {
                $finalAmount = $originalAmount;
            }

            $totalAmount += $finalAmount;
        }

        return $totalAmount;
    }

    /**
     * Get students for a specific fee package based on class range
     */
    public function getStudentsForFeePackage(Request $request)
    {
        $request->validate([
            'fee_package_id' => 'required|exists:fee_packages,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'fee_period_id' => 'required|exists:fee_periods,id',
            'branch_id' => 'required|exists:branches,id',
            'class_id' => 'nullable|exists:com_classes,id',
            'section_id' => 'nullable|exists:sections,id'
        ]);

        try {
            $feePackage = FeePackage::with(['from_class_id', 'to_class_id', 'fee_package_type', 'classes'])->findOrFail($request->fee_package_id);
            $academicYear = AcademicYear::findOrFail($request->academic_year_id);
            $feePeriod = FeePeriod::findOrFail($request->fee_period_id);
            // Use the branch_id from the request
            $branchId = $request->branch_id;

            // Build the student query aligned with studentListingQuery logic
            $studentQuery = Student::with([
                'active_class.branch_class_sections.com_classes',
                'active_class.branch_class_sections.sections',
                'class_students.branch_class_sections'
            ])
                ->whereHas('class_students', function ($query) use ($academicYear) {
                    $query->where('academic_year_id', $academicYear->id)
                        ->where('is_valid', 1);
                })
                ->where('branch_id', $branchId)
                ->where('status', '!=', 'left');

            // Filter by classes if provided - aligned with studentListingQuery
            if (!empty($request->class_id)) {
                $studentQuery->whereHas('active_class.branch_class_sections', function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                });
            } else {
                // If no classes specified, use the same logic as getClassesForFeePackage
                $targetClassIds = collect();

                // First, check if the fee package has classes defined via many-to-many relationship
                $classes = $feePackage->classes;
                if ($classes->isNotEmpty()) {
                    $targetClassIds = $classes->pluck('id');
                }
                // If no classes found in the relationship, use the from_class_id to to_class_id range
                elseif ($feePackage->from_class_id && $feePackage->to_class_id) {
                    $targetClassIds = ComClass::whereBetween('id', [$feePackage->from_class_id, $feePackage->to_class_id])
                        ->pluck('id');
                }

                if ($targetClassIds->isNotEmpty()) {
                    $studentQuery->whereHas('active_class.branch_class_sections', function ($query) use ($targetClassIds) {
                        $query->whereIn('class_id', $targetClassIds);
                    });
                }
            }

            // Filter by sections if provided - aligned with studentListingQuery
            if (!empty($request->section_id)) {
                $studentQuery->whereHas('class_students', function ($query) use ($request, $academicYear) {
                    $query->whereHas('branch_class_sections', function ($subQuery) use ($request) {
                        $subQuery->where('section_id', $request->section_id);

                        // If class_id is provided, also filter by class
                        if (!empty($request->class_id)) {
                            $subQuery->where('class_id', $request->class_id);
                        }
                    });

                    $query->where('academic_year_id', $academicYear->id);
                });
            }

            $students = $studentQuery->get();

            // Filter students based on package type and student status
            $filteredStudents = $students->filter(function ($student) use ($feePeriod, $feePackage) {
                // Check for existing invoice for this EXACT period AND fee package combination
                $existingInvoice = StudentInvoice::where([
                    'student_id' => $student->id,
                    'fee_period_id' => $feePeriod->id
                ])
                    ->where('bank_payment_status', '!=', 'cancelled')
                    ->first();

                if ($existingInvoice) {
                    return false; // Exclude this student
                }

                // Filter based on package type and student status
                $packageType = $feePackage->fee_package_type->name;
                $studentStatus = $student->status;

                if ($packageType == 'Admission') {
                    // Check for processing status (case insensitive)
                    $isProcessing = strtolower($studentStatus) === 'processing';
                    $isNull = $studentStatus === null;
                    $shouldInclude = $isProcessing || $isNull;

                    // For Admission packages: only show students with status 'processing' or null
                    return $shouldInclude;
                } else {
                    // For Monthly/Other packages: show students with any status EXCEPT 'processing' and null
                    $isProcessing = strtolower($studentStatus) === 'processing';
                    $isNull = $studentStatus === null;
                    return !($isProcessing || $isNull);
                }
            });

            // Process students to include package information
            $processedStudents = $filteredStudents->map(function ($student) use ($feePackage, $academicYear) {
                // Get the current active fee package for this academic year
                // Use a fresh query to ensure we get the most up-to-date data
                $currentPackage = StudentFeePackage::where([
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'is_valid' => 1
                ])->with('fee_package')->first();

                $packageName = $currentPackage ? $currentPackage->fee_package->package_name : 'None';
                $hasActivePackage = $currentPackage && $currentPackage->is_valid;

                // Additional debug info for students without active class
                $activeClassInfo = null;
                if ($student->active_class && $student->active_class->branch_class_sections) {
                    $activeClassInfo = [
                        'class_name' => $student->active_class->branch_class_sections->com_classes->class_name ?? 'N/A',
                        'section_name' => $student->active_class->branch_class_sections->sections->section_name ?? 'N/A'
                    ];
                }

                return [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'last_name' => $student->last_name,
                    'active_class' => $student->active_class,
                    'active_class_info' => $activeClassInfo,
                    'current_package' => $packageName,
                    'has_active_package' => $hasActivePackage,
                    'needs_package_assignment' => !$hasActivePackage,
                    'status' => $student->status
                ];
            });

            $response = [
                'success' => true,
                'students' => $processedStudents,
                'total_count' => $processedStudents->count(),
                'package_type' => $feePackage->fee_package_type->name,
                'package_name' => $feePackage->package_name ?? 'N/A',
                'students_without_packages' => $processedStudents->where('needs_package_assignment', true)->count(),
                'students_with_packages' => $processedStudents->where('has_active_package', true)->count(),
                'filtering_applied' => [
                    'package_type' => $feePackage->fee_package_type->name,
                    'admission_students_shown' => $feePackage->fee_package_type->name === 'Admission' ? 'Only students with status "processing" or null' : 'Students with any status except "processing" and null',
                    'monthly_students_shown' => $feePackage->fee_package_type->name !== 'Admission' ? 'Students with any status except "processing" and null' : 'Only students with status "processing" or null'
                ]
            ];

            Log::info('Final response', [
                'total_count' => $response['total_count'],
                'package_type' => $response['package_type'],
                'students_without_packages' => $response['students_without_packages'],
                'students_with_packages' => $response['students_with_packages']
            ]);

            return response()->json($response);

        } catch (Exception $e) {
            Log::error('Error fetching students for fee package', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'fee_package_id' => $request->fee_package_id,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students. Please try again.'
            ], 500);
        }
    }

    public function royaltyComputationExport(Request $request)
    {
        return Excel::download(new ExportRoyaltyReport($request), 'royalty_report.xlsx');
    }

    /**
     * Show enhanced bulk challan generation view
     */
    public function enhancedBulkChallanView()
    {
        // Ensure Super Admin and other authorized roles have access
        if (
            !Auth::user()->hasRole(['super_admin', 'network_associate', 'finance-manager', 'accountant']) &&
            !Auth::user()->hasPermission(['list-generate-invoice', 'list-preview-invoice'])
        ) {
            abort(403, 'Unauthorized access to enhanced bulk challan generation.');
        }

        // Get all branches for selection
        $branches = Branch::all();
        $academicYears = AcademicYear::all();

        // Get fee packages for the current branch and active academic year
        $currentBranchId = get_branch_id();
        $activeAcademicYear = AcademicYear::where('active', 1)->first();

        $feePackages = collect();
        if ($activeAcademicYear) {
            $feePackages = FeePackage::with(['fee_package_type', 'classes'])
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('branch_id', $currentBranchId)
                ->whereHas('fee_packages_fee_charges', function ($query) {
                    $query->where('status', 1) // Active status
                        ->whereNotNull('fee_charge_id'); // Ensure charge exists
                })
                ->get();
        }

        return view('students.bulk_invoices.enhanced_bulk_challans', [
            'branches' => $branches,
            'academicYears' => $academicYears,
            'feePackages' => $feePackages
        ]);
    }

    /**
     * Get fee packages for academic year and branch
     */
    public function getFeePackages(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'branch_id' => 'required|exists:branches,id'
        ]);

        try {
            // Use the branch_id from the request
            $branchId = $request->branch_id;

            $feePackages = FeePackage::with(['fee_package_type', 'classes'])
                ->where('academic_year_id', $request->academic_year_id)
                ->where('branch_id', $branchId)
                ->whereHas('fee_packages_fee_charges', function ($query) {
                    $query->where('status', 1) // Active status
                        ->whereNotNull('fee_charge_id'); // Ensure charge exists
                })
                ->get();

            return response()->json([
                'success' => true,
                'fee_packages' => $feePackages
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching fee packages', [
                'error' => $e->getMessage(),
                'academic_year_id' => $request->academic_year_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch fee packages: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get fee periods for academic year and branch
     */
    public function getFeePeriods(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'branch_id' => 'required|exists:branches,id'
        ]);

        try {
            // Use the branch_id from the request
            $branchId = $request->branch_id;
            
            // Get fee periods for the academic year
            $feePeriods = FeePeriod::where('academic_year_id', $request->academic_year_id)
                ->where('branch_id', $branchId)
                ->select('id', 'period_name', 'from_date', 'to_date', 'due_date', 'issue_date', 'valid_date')
                ->orderBy('from_date')
                ->get();

            return response()->json([
                'success' => true,
                'fee_periods' => $feePeriods
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching fee periods', [
                'error' => $e->getMessage(),
                'academic_year_id' => $request->academic_year_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch fee periods: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get classes for a fee package
     */
    public function getClassesForFeePackage(Request $request)
    {
        $request->validate([
            'fee_package_id' => 'required|exists:fee_packages,id'
        ]);

        try {
            $feePackage = FeePackage::with(['classes', 'from_class_id', 'to_class_id'])->findOrFail($request->fee_package_id);

            // Get classes associated with this fee package
            $classes = $feePackage->classes;

            // If no classes found in the relationship, use the from_class_id to to_class_id range
            if ($classes->isEmpty() && $feePackage->from_class_id && $feePackage->to_class_id) {
                $classes = ComClass::whereBetween('id', [$feePackage->from_class_id, $feePackage->to_class_id])
                    ->orderBy('id')
                    ->get();
            }

            // If still no classes found, return empty array with message
            if ($classes->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'classes' => [],
                    'message' => 'No classes found for this fee package. Please contact administrator.'
                ]);
            }

            return response()->json([
                'success' => true,
                'classes' => $classes,
                'package_name' => $feePackage->package_name
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching classes for fee package', [
                'error' => $e->getMessage(),
                'fee_package_id' => $request->fee_package_id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch classes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sections for a class
     */
    public function getSectionsForClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:com_classes,id',
            'branch_id' => 'required|exists:branches,id'
        ]);

        try {
            // Use the branch_id from the request
            $branchId = $request->branch_id;
            
            $sections = BranchClassSection::with('sections')
                ->where('class_id', $request->class_id)
                ->where('branch_id', $branchId)
                ->get()
                ->pluck('sections')
                ->flatten()
                ->unique('id')
                ->values();

            return response()->json([
                'success' => true,
                'sections' => $sections
            ]);

        } catch (Exception $e) {
            Log::error('Error fetching sections for class', [
                'error' => $e->getMessage(),
                'class_id' => $request->class_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch sections: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateSimpleBulkChallans(Request $request)
    {
        // Validate request
        $request->validate([
            'fee_package_id' => 'required|exists:fee_packages,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'fee_period_id' => 'required|exists:fee_periods,id',
            'branch_id' => 'required|exists:branches,id',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
        ]);

        $feePackage = FeePackage::with('fee_package_type')->find($request->fee_package_id);
        $feePeriod = FeePeriod::find($request->fee_period_id);
        $academicYear = AcademicYear::find($request->academic_year_id);
        $branch = Branch::find($request->branch_id);

        // Get students with their current fee packages
        $students = Student::with([
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections'
        ])->whereIn('id', $request->students)
            ->where('branch_id', $request->branch_id)
            ->where(function ($query) {
                $query->where('status', '!=', 'left')
                    ->orWhereNull('status');
            })
            ->get();

        // Get fee charges from the selected fee package
        $feeCharges = FeePackagesFeeCharges::where('fee_package_id', $request->fee_package_id)
            ->where('status', 1) // Only active charges
            ->whereNotNull('fee_charge_id') // Ensure charge exists
            ->with('fee_charges.fee_charges_type')
            ->get()
            ->pluck('fee_charges')
            ->filter(); // Remove any null values

        $successCount = 0;
        $errors = [];
        $generatedInvoices = [];
        $packageAssignments = [];
        $skippedStudents = [];

        // Set execution time for bulk operations
        ini_set('max_execution_time', 300);

        foreach ($students as $student) {
            try {
                // Check if student already has an invoice for this period
                $existingInvoice = StudentInvoice::where([
                    'student_id' => $student->id,
                    'fee_period_id' => $request->fee_period_id
                ])->where('bank_payment_status', '!=', 'cancelled')->first();

                if ($existingInvoice) {
                    $skippedStudents[] = [
                        'student_id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name,
                        'reason' => "Invoice already exists for this period (Invoice ID: {$existingInvoice->id})"
                    ];
                    continue;
                }

                // Check for admission invoice if this is an admission package
                if ($feePackage->fee_package_type->name === 'Admission') {
                    $existingAdmissionInvoice = StudentInvoice::where([
                        'student_id' => $student->id,
                        'invoice_frequency' => 'Admission'
                    ])->where('bank_payment_status', '!=', 'cancelled')->first();

                    if ($existingAdmissionInvoice) {
                        $skippedStudents[] = [
                            'student_id' => $student->id,
                            'name' => $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name,
                            'reason' => "Admission invoice already exists (Invoice ID: {$existingAdmissionInvoice->id})"
                        ];
                        continue;
                    }
                }

                // Check if student has an active fee package
                // Use a fresh query to ensure we get the most up-to-date data
                $currentPackage = StudentFeePackage::where([
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'is_valid' => 1
                ])->first();
                $hasActivePackage = $currentPackage && $currentPackage->is_valid;

                // Handle fee package assignment based on business logic
                if (!$hasActivePackage) {
                    // Student has no active package - assign the selected package
                    $studentFeePackage = $this->assignOrUpdateFeePackageForStudent($student, $feePackage, $academicYear);
                    $packageAssignments[] = "Student ID {$student->id}: Assigned package '{$feePackage->package_name}'";
                } else {
                    // Student has an active package - use their existing package for challan generation
                    $studentFeePackage = $currentPackage;
                }

                // Calculate totals
                $subtotal = 0;
                $totalDiscount = 0;
                $totalPayable = 0;

                foreach ($feeCharges as $charge) {
                    $originalAmount = $charge->amount ?? 0;
                    $subtotal += $originalAmount;

                    // Get student concession for this charge
                    $chargeConcession = StudentConcession::where([
                        'student_id' => $student->id,
                        'fee_charge_id' => $charge->id,
                        'is_valid' => 1
                    ])->first();

                    $discountPercentage = $chargeConcession ? $chargeConcession->fee_concession->concession_percentage : 0;
                    $discountAmount = ($originalAmount * $discountPercentage) / 100;
                    $totalDiscount += $discountAmount;
                }

                $totalPayable = $subtotal - $totalDiscount;

                // --- ARREARS AND ADVANCE PAYMENT LOGIC ---
                // 1. Get all uncleared arrears for this student (not yet assigned to a new invoice)
                $arrears = StudentArrearsHistory::where('student_id', $student->id)
                    ->whereNull('cleared_date')
                    ->whereNull('to_invoice_id')
                    ->where('amount', '>', 0) // Only positive amounts (arrears)
                    ->sum('amount');

                // 2. Get available advance payment for this student
                $advance = ArrearsService::getAdvancePaymentAmount($student->id);

                // 3. Calculate the new total payable
                $totalPayableWithArrears = $totalPayable + $arrears - $advance;
                $finalTotalPayable = max(0, $totalPayableWithArrears);
                $carriedAdvance = $advance > ($totalPayable + $arrears) ? $advance - ($totalPayable + $arrears) : 0;

                // Create invoice
                $invoiceData = [
                    'student_id' => $student->id,
                    'student_fee_package_id' => $studentFeePackage->id,
                    'promo_id' => null,
                    'invoice_type_id' => 1,
                    'invoice_frequency' => $feePackage->fee_package_type->name,
                    'payment_source_id' => null,
                    'is_paid' => 0,
                    'paid_date' => null,
                    'fee_period_id' => $request->fee_period_id,
                    'due_date' => $feePeriod->due_date,
                    'issue_date' => $feePeriod->issue_date,
                    'validity_date' => $feePeriod->valid_date,
                    'bank_payment_status' => 'unpaid',
                    'subtotal' => $subtotal,
                    'total_discount' => $totalDiscount,
                    'total_payable' => $finalTotalPayable,
                    // Store arrears and advance information for reporting/UI
                    'arrears_included' => $arrears,
                    'advance_applied' => $advance,
                    'carried_advance' => $carriedAdvance,
                ];

                $studentInvoice = StudentInvoice::create($invoiceData);

                // Carry forward all open arrears to this new invoice
                StudentArrearsHistory::where('student_id', $student->id)
                    ->whereNull('to_invoice_id')
                    ->whereNull('cleared_date')
                    ->update(['to_invoice_id' => $studentInvoice->id]);

                // Check for advance payments and apply to this invoice
                $advanceAmount = ArrearsService::getAdvancePaymentAmount($student->id);
                if ($advanceAmount > 0) {
                    $amountToApply = min($advanceAmount, $finalTotalPayable);

                    // Apply advance to this invoice and clear the advance records
                    $actualAmountApplied = ArrearsService::applyAdvanceToInvoice($student->id, $studentInvoice->id, $amountToApply);

                    if ($actualAmountApplied > 0) {
                        // Create a payment record for the advance amount
                        StudentPayment::create([
                            'student_id' => $student->id,
                            'invoice_id' => $studentInvoice->id,
                            'amount' => $actualAmountApplied,
                            'payment_date' => Carbon::now(),
                            'method' => 'advance',
                            'reference' => 'Advance Payment',
                            'remarks' => 'Applied from previous overpayment'
                        ]);
                    }
                }

                foreach ($feeCharges as $charge) {
                    $chargeConcession = StudentConcession::where([
                        'student_id' => $student->id,
                        'fee_charge_id' => $charge->id,
                        'is_valid' => 1
                    ])->first();

                    $originalAmount = $charge->amount ?? 0;
                    $discountPercentage = $chargeConcession ? $chargeConcession->fee_concession->concession_percentage : 0;
                    $discountAmount = ($originalAmount * $discountPercentage) / 100;
                    $finalAmount = $originalAmount - $discountAmount;

                    $itemData = [
                        'student_invoice_id' => $studentInvoice->id,
                        'fee_charge_id' => $charge->id,
                        'debit' => $originalAmount,
                        'credit' => null,
                        'concession' => $discountPercentage,
                        'concession_amount' => $discountAmount,
                        'final_amount' => $finalAmount,
                    ];

                    StudentInvoiceItem::create($itemData);
                }

                // Update student status from 'processing' or null to 'registered'
                if ($student->status === 'processing' || $student->status === null) {
                    $student->update(['status' => 'registered']);
                }

                // Handle admission to monthly package transition logic
                if ($feePackage->fee_package_type->name === 'Admission') {
                    // For admission packages, we need to check if this is the first admission invoice
                    // and if so, prepare for monthly package transition when payment is made
                    $admissionInvoiceCount = StudentInvoice::where([
                        'student_id' => $student->id,
                        'invoice_frequency' => 'Admission'
                    ])->where('bank_payment_status', '!=', 'cancelled')->count();

                    // If this is the first admission invoice, we'll handle the transition when it's paid
                    if ($admissionInvoiceCount == 1) {
                        // Log that this student is ready for monthly package transition
                        Log::info('Student ready for admission to monthly package transition', [
                            'student_id' => $student->id,
                            'invoice_id' => $studentInvoice->id,
                            'fee_package_id' => $feePackage->id
                        ]);
                    }
                }

                // Update student ledger if exists
                $currentLedger = StudentLedger::where([
                    'student_id' => $student->id,
                    'class_student_id' => $student->active_class->id
                ])->first();

                if ($currentLedger) {
                    $invoiceMonth = Carbon::parse($feePeriod->from_date)->format('m');
                    StudentLedgerInvoice::where([
                        'student_ledger_id' => $currentLedger->id,
                        'month' => $invoiceMonth
                    ])->update(['student_invoice_id' => $studentInvoice->id]);
                }

                $generatedInvoices[] = $studentInvoice->id;
                $successCount++;

            } catch (Exception $e) {
                $errors[] = [
                    'student_id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name,
                    'reason' => "Error processing student: " . $e->getMessage()
                ];
                Log::error('Bulk challan generation error for student ' . $student->id, [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        // Prepare response message
        $message = "Challan is generated successfully. {$successCount} challans generated.";

        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            // Prepare detailed response for AJAX
            $createdChallans = [];
            $failedStudents = [];
            
            // Get details of created challans
            if ($successCount > 0) {
                $createdInvoices = StudentInvoice::with(['student:id,first_name,middle_name,last_name,active_class'])
                    ->whereIn('id', $generatedInvoices)
                    ->get();
                
                foreach ($createdInvoices as $invoice) {
                    $createdChallans[] = [
                        'student_id' => $invoice->student->id,
                        'name' => $invoice->student->first_name . ' ' . $invoice->student->middle_name . ' ' . $invoice->student->last_name,
                        'class' => $invoice->student->active_class->branch_class_sections->com_classes->class_name ?? 'N/A',
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->total_payable
                    ];
                }
            }
            
            // Prepare failed students list
            foreach ($errors as $error) {
                if (is_array($error)) {
                    $failedStudents[] = $error;
                } else {
                    $failedStudents[] = [
                        'student_id' => 'N/A',
                        'name' => 'N/A',
                        'reason' => $error
                    ];
                }
            }
            
            // Prepare skipped students list
            $skippedStudentsList = [];
            foreach ($skippedStudents as $skipped) {
                if (is_array($skipped)) {
                    $skippedStudentsList[] = $skipped;
                } else {
                    $skippedStudentsList[] = [
                        'student_id' => 'N/A',
                        'name' => 'N/A',
                        'reason' => $skipped
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'created_challans' => $createdChallans,
                'skipped_students' => $skippedStudentsList,
                'failed_students' => $failedStudents,
                'summary' => [
                    'total_processed' => count($request->students),
                    'created_count' => $successCount,
                    'skipped_count' => count($skippedStudents),
                    'failed_count' => count($errors),
                    'package_assignments' => $packageAssignments
                ]
            ]);
        }

        // Return redirect for non-AJAX requests
        return redirect()->back()->with([
            'success' => $message,
            'generated_count' => $successCount,
            'error_count' => count($errors),
            'skipped_count' => count($skippedStudents),
            'package_assignments' => $packageAssignments
        ]);
    }

    private function assignOrUpdateFeePackageForStudent($student, $feePackage, $academicYear)
    {
        // Check if student already has an active fee package
        $existingPackage = StudentFeePackage::where([
            'student_id' => $student->id,
            'is_valid' => 1
        ])->first();

        // If student has the same package, return it
        if ($existingPackage && $existingPackage->fee_package_id == $feePackage->id) {
            return $existingPackage;
        }

        // If student has a different package, deactivate it
        if ($existingPackage) {
            $existingPackage->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
        }

        // Validate that student has an active class with proper relationships
        if (!$student->active_class || !$student->active_class->branch_class_sections) {
            throw new Exception("Student ID {$student->id} does not have an active class or branch class section assigned.");
        }

        // Validate that the branch class section has the required relationships
        if (!$student->active_class->branch_class_sections->com_classes || !$student->active_class->branch_class_sections->sections) {
            throw new Exception("Student ID {$student->id} has incomplete class/section information. Please ensure the student is properly assigned to a class and section.");
        }

        // Create new fee package assignment
        $studentFeePackage = StudentFeePackage::create([
            'fee_package_id' => $feePackage->id,
            'fee_concession_id' => $existingPackage ? $existingPackage->fee_concession_id : null,
            'academic_year_id' => $academicYear->id,
            'com_class_id' => $student->active_class->branch_class_sections->com_classes->id,
            'section_id' => $student->active_class->branch_class_sections->sections->id,
            'student_id' => $student->id,
            'is_valid' => 1,
            'active_from' => Carbon::now(),
        ]);

        return $studentFeePackage;
    }

    /**
     * Handle admission to monthly package transition and status change to 'on_roll'
     * 
     * @param int $studentId
     * @param int $invoiceId
     * @return array
     */
    private function handleAdmissionToMonthlyTransition($studentId, $invoiceId)
    {
        $result = [
            'success' => false,
            'message' => '',
            'monthly_package_id' => null,
            'status_changed' => false
        ];

        try {
            // Check if this is the first paid admission invoice for this student
            $paidAdmissionInvoiceCount = StudentInvoice::where([
                'student_id' => $studentId,
                'invoice_frequency' => 'Admission',
                'bank_payment_status' => 'paid'
            ])->count();

            // Only process if this is the first paid admission invoice
            if ($paidAdmissionInvoiceCount == 1) {
                // Apply monthly package transition
                $monthlyPackageResult = StudentInvoice::apply_monthly_package($studentId);
                
                if ($monthlyPackageResult) {
                    // Update student status to 'on_roll'
                    $student = Student::find($studentId);
                    if ($student && in_array($student->status, ['processing', 'registered'])) {
                        $oldStatus = $student->status;
                        $student->update(['status' => 'on_roll']);
                        
                        // Update system ID and roll number if not already set
                        if (!$student->system_id) {
                            Student::update_student_id($studentId);
                        }
                        if (!$student->roll_no) {
                            Student::update_roll_no($studentId);
                        }
                        
                        $result = [
                            'success' => true,
                            'message' => 'Student status changed to on_roll and monthly package applied successfully',
                            'monthly_package_id' => $monthlyPackageResult->id,
                            'status_changed' => true,
                            'old_status' => $oldStatus,
                            'new_status' => 'on_roll'
                        ];
                        
                        Log::info('Student status changed to on_roll and monthly package applied', [
                            'student_id' => $studentId,
                            'invoice_id' => $invoiceId,
                            'old_status' => $oldStatus,
                            'new_status' => 'on_roll',
                            'monthly_package_id' => $monthlyPackageResult->id
                        ]);
                    } else {
                        $result['message'] = 'Student status not eligible for change to on_roll';
                    }
                } else {
                    $result['message'] = 'Failed to apply monthly package';
                    Log::warning('Failed to apply monthly package for student', [
                        'student_id' => $studentId,
                        'invoice_id' => $invoiceId
                    ]);
                }
            } else {
                $result['message'] = 'Not the first paid admission invoice';
            }
        } catch (Exception $e) {
            $result['message'] = 'Error during transition: ' . $e->getMessage();
            Log::error('Error during admission to monthly package transition', [
                'student_id' => $studentId,
                'invoice_id' => $invoiceId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return $result;
    }

    public function getFeePackageCharges(Request $request)
    {
        $request->validate([
            'fee_package_id' => 'required|exists:fee_packages,id'
        ]);

        $feePackage = FeePackage::with([
            'fee_packages_fee_charges' => function ($query) {
                $query->where('status', 1) // Only active charges
                    ->whereNotNull('fee_charge_id'); // Ensure charge exists
            },
            'fee_packages_fee_charges.fee_charges.fee_charges_type'
        ])->find($request->fee_package_id);

        if (!$feePackage) {
            return response()->json(['error' => 'Fee package not found'], 404);
        }

        $charges = $feePackage->fee_packages_fee_charges->map(function ($item) {
            return $item->fee_charges;
        })->filter();

        return response()->json([
            'success' => true,
            'charges' => $charges
        ]);
    }

    /**
     * Show bulk mark as paid screen
     */
    public function bulkMarkAsPaidView()
    {
        // Ensure Super Admin and other authorized roles have access
        if (
            !Auth::user()->hasRole(['super_admin', 'network_associate', 'finance-manager', 'accountant']) &&
            !Auth::user()->hasPermission(['list-generate-invoice', 'list-preview-invoice'])
        ) {
            abort(403, 'Unauthorized access to bulk mark as paid.');
        }

        $branches = Branch::all();

        return view('students.bulk_invoices.bulk_mark_as_paid', compact('branches'));
    }

    /**
     * Get unpaid invoices for bulk mark as paid
     */
    public function getUnpaidInvoices(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'fee_period_id' => 'nullable|exists:fee_periods,id',
        ]);

        $query = StudentInvoice::with([
            'student:id,first_name,last_name,registration_no,system_id',
            'student_fee_package.fee_package.fee_package_type',
            'fee_period',
            'payments'
        ])
            ->whereHas('student', function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            })
            ->where('bank_payment_status', 'unpaid');

        if ($request->academic_year_id) {
            $query->whereHas('student_fee_package', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        if ($request->fee_period_id) {
            $query->where('fee_period_id', $request->fee_period_id);
        }

        $invoices = $query->get();

        // Calculate totals for each invoice
        $invoices->each(function ($invoice) {
            $totalPaid = $invoice->payments->sum('amount');
            $invoice->total_paid = $totalPaid;
            $invoice->remaining_amount = max(0, $invoice->total_payable - $totalPaid);
        });

        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ]);
    }

    /**
     * Bulk mark invoices as paid
     */
    public function bulkMarkAsPaid(Request $request)
    {
        $request->validate([
            'invoice_ids' => 'required|array|min:1',
            'invoice_ids.*' => 'exists:student_invoices,id',
            'paid_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,cheque,online',
            'payment_reference' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ]);

        $invoiceIds = $request->invoice_ids;
        $paidDate = $request->paid_date;
        $paymentMethod = $request->payment_method;
        $paymentReference = $request->payment_reference ?? null;
        $remarks = $request->remarks ?? null;

        ini_set('max_execution_time', 300); // 5 minutes for large operations

        try {
            DB::beginTransaction();

            $successCount = 0;
            $errors = [];
            $processedStudents = [];

            foreach ($invoiceIds as $invoiceId) {
                try {
                    $studentInvoice = StudentInvoice::with(['student', 'payments'])->findOrFail($invoiceId);

                    // Skip if already paid
                    if ($studentInvoice->bank_payment_status === 'paid' || $studentInvoice->bank_payment_status === 'overpaid') {
                        continue;
                    }

                    $totalPayable = $studentInvoice->total_payable ?? 0;
                    $totalPaid = $studentInvoice->payments->sum('amount');
                    $remainingAmount = max(0, $totalPayable - $totalPaid);

                    // Create payment record for the full remaining amount
                    $payment = StudentPayment::create([
                        'student_id' => $studentInvoice->student_id,
                        'invoice_id' => $studentInvoice->id,
                        'amount' => $remainingAmount,
                        'payment_date' => Carbon::parse($paidDate)->format('Y-m-d'),
                        'method' => $paymentMethod,
                        'reference' => $paymentReference ?? 'Bulk Payment',
                        'remarks' => $remarks ?? 'Bulk mark as paid'
                    ]);

                    // Clear arrears with this payment
                    ArrearsService::clearArrearsWithPayment($payment);

                    // Update invoice status to paid
                    $studentInvoice->update([
                        'bank_payment_status' => 'paid',
                        'is_paid' => 1,
                        'paid_date' => $paidDate,
                        'remarks' => ($remarks ?? 'Bulk mark as paid') . ' - Full payment of ' . number_format($remainingAmount, 2),
                    ]);

                    // Clear any existing arrears/advance for this invoice
                    $existingArrears = StudentArrearsHistory::where('from_invoice_id', $studentInvoice->id)
                        ->whereNull('cleared_date')->first();
                    if ($existingArrears) {
                        $existingArrears->update(['cleared_date' => Carbon::now()]);
                    }

                    // Handle student status updates based on invoice type and payment
                    if ($studentInvoice->bank_payment_status === 'paid') {
                        $student = $studentInvoice->student;
                        
                        if ($studentInvoice->invoice_frequency === 'Admission') {
                            // Handle admission to monthly package transition and status change to 'on_roll'
                            $transitionResult = $this->handleAdmissionToMonthlyTransition($studentInvoice->student_id, $studentInvoice->id);
                            
                            if ($transitionResult['success']) {
                                Log::info('Admission to monthly transition completed successfully', [
                                    'student_id' => $studentInvoice->student_id,
                                    'invoice_id' => $studentInvoice->id,
                                    'result' => $transitionResult
                                ]);
                            } else {
                                Log::warning('Admission to monthly transition failed', [
                                    'student_id' => $studentInvoice->student_id,
                                    'invoice_id' => $studentInvoice->id,
                                    'result' => $transitionResult
                                ]);
                            }
                        } else {
                            // For monthly invoices, update status to 'on_roll' if student is 'registered'
                            if ($student && $student->status === 'registered') {
                                $oldStatus = $student->status;
                                $student->update(['status' => 'on_roll']);
                                
                                // Update system ID and roll number if not already set
                                if (!$student->system_id) {
                                    Student::update_student_id($student->id);
                                }
                                if (!$student->roll_no) {
                                    Student::update_roll_no($student->id);
                                }
                                
                                Log::info('Student status changed to on_roll for monthly invoice payment', [
                                    'student_id' => $student->id,
                                    'invoice_id' => $studentInvoice->id,
                                    'old_status' => $oldStatus,
                                    'new_status' => 'on_roll',
                                    'invoice_frequency' => $studentInvoice->invoice_frequency
                                ]);
                            }
                        }
                    }

                    $successCount++;
                    $processedStudents[] = $studentInvoice->student_id;

                } catch (Exception $e) {
                    $errors[] = "Error processing invoice ID {$invoiceId}: Processing failed";
                    Log::error('Bulk mark as paid error for invoice ' . $invoiceId, [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully marked {$successCount} invoices as paid." .
                    (count($errors) > 0 ? " Some items could not be processed." : ''),
                'processed_count' => $successCount,
                'errors' => $errors,
                'processed_students' => array_unique($processedStudents)
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Bulk mark as paid failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark invoices as paid. Please try with fewer items or contact support.'
            ], 500);
        }
    }

    /**
     * Show view for importing previous arrears and advance payments
     */
    public function importPreviousDataView()
    {
        $students = Student::where('status', '=', 'on_roll')
            ->orderByDesc('id')
            ->get();
            
        return view('students.import_previous_data', compact('students'));
    }

    /**
     * Import previous arrears data for students
     */
    public function importPreviousArrears(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'arrears_amount' => 'required|numeric|min:0',
            'arrears_date' => 'required|date|before_or_equal:today',
        ]);

        try {
            // Check if student is on_roll
            $student = Student::find($request->student_id);
            if (!$student || $student->status !== 'on_roll') {
                return redirect()->back()->with('error', 'Student is not currently enrolled (on_roll status required).');
            }

            DB::transaction(function () use ($request) {
                // Check if arrears already exist for this student for the same month and year
                $existingArrears = StudentArrearsHistory::where('student_id', $request->student_id)
                    ->whereYear('carried_date', date('Y', strtotime($request->arrears_date)))
                    ->whereMonth('carried_date', date('m', strtotime($request->arrears_date)))
                    ->whereNull('cleared_date') // Only check uncleared arrears
                    ->first();

                if ($existingArrears) {
                    // Update existing record
                    $existingArrears->update([
                        'amount' => $request->arrears_amount,
                        'carried_date' => $request->arrears_date,
                        'updated_at' => now(),
                    ]);
                } else {
                    // Create new record
                    StudentArrearsHistory::create([
                        'student_id' => $request->student_id,
                        'from_invoice_id' => null, // No original invoice since this is historical data
                        'to_invoice_id' => null, // Will be assigned when next invoice is created
                        'amount' => $request->arrears_amount,
                        'carried_date' => $request->arrears_date,
                        'cleared_by_payment_id' => null,
                        'cleared_date' => null,
                    ]);
                }
            });

            // Check if this was an update or new record
            $existingArrears = StudentArrearsHistory::where('student_id', $request->student_id)
                ->whereYear('carried_date', date('Y', strtotime($request->arrears_date)))
                ->whereMonth('carried_date', date('m', strtotime($request->arrears_date)))
                ->whereNull('cleared_date')
                ->first();

            $message = $existingArrears ? 
                'Previous arrears updated successfully. Amount: ' . number_format($request->arrears_amount, 2) :
                'Previous arrears imported successfully. Amount: ' . number_format($request->arrears_amount, 2);

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to import previous arrears. Please try again.');
        }
    }

    /**
     * Import previous advance payments data for students
     */
    public function importPreviousAdvance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'advance_amount' => 'required|numeric|min:0',
            'advance_date' => 'required|date|before_or_equal:today',
        ]);

        try {
            // Check if student is on_roll
            $student = Student::find($request->student_id);
            if (!$student || $student->status !== 'on_roll') {
                return redirect()->back()->with('error', 'Student is not currently enrolled (on_roll status required).');
            }

            DB::transaction(function () use ($request) {
                // Check if advance payment already exists for this student for the same month and year
                                        $existingAdvance = StudentArrearsHistory::where('student_id', $request->student_id)
                            ->whereYear('carried_date', date('Y', strtotime($request->advance_date)))
                            ->whereMonth('carried_date', date('m', strtotime($request->advance_date)))
                            ->where('amount', '<', 0) // Only check advance payments (negative amounts)
                            ->whereNull('from_invoice_id') // Only check unapplied advances
                            ->whereNull('to_invoice_id') // Only check unapplied advances
                            ->first();

                if ($existingAdvance) {
                    // Update existing record
                    $existingAdvance->update([
                        'amount' => -$request->advance_amount, // Negative amount for advance payments
                        'carried_date' => $request->advance_date,
                        'updated_at' => now(),
                    ]);
                } else {
                    // Create new record
                    StudentArrearsHistory::create([
                        'student_id' => $request->student_id,
                        'from_invoice_id' => null, // No original invoice since this is historical data
                        'to_invoice_id' => null, // Will be assigned when next invoice is created
                        'amount' => -$request->advance_amount, // Negative amount for advance payments
                        'carried_date' => $request->advance_date,
                        'cleared_date' => null,
                    ]);
                }
            });

            // Check if this was an update or new record
            $existingAdvance = StudentArrearsHistory::where('student_id', $request->student_id)
                ->whereYear('carried_date', date('Y', strtotime($request->advance_date)))
                ->whereMonth('carried_date', date('m', strtotime($request->advance_date)))
                ->whereNull('cleared_date')
                ->where('amount', '<', 0)
                ->first();

            $message = $existingAdvance ? 
                'Previous advance payment updated successfully. Amount: ' . number_format($request->advance_amount, 2) :
                'Previous advance payment imported successfully. Amount: ' . number_format($request->advance_amount, 2);

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to import previous advance payment. Please try again.');
        }
    }

    /**
     * Bulk import previous arrears from CSV/Excel
     */
    public function bulkImportPreviousArrears(Request $request)
    {
        $request->validate([
            'arrears_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {
            $file = $request->file('arrears_file');
            $extension = $file->getClientOriginalExtension();
            
            $imported = 0;
            $errors = [];
            $successRows = [];
            $errorRows = [];

            if ($extension === 'csv') {
                $data = array_map('str_getcsv', file($file->getPathname()));
                $headers = array_shift($data); // Remove header row
                
                foreach ($data as $index => $row) {
                    $rowNumber = $index + 1;
                    $rowData = array_combine($headers, $row);
                    
                    try {
                        $wasUpdated = $this->processArrearsRow($rowData);
                        $imported++;
                        $successRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'action' => $wasUpdated ? 'Updated' : 'Created'
                        ];
                    } catch (Exception $e) {
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                        $errorRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'error' => $e->getMessage()
                        ];
                    }
                }
            } else {
                // Handle Excel files
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getPathname());
                $spreadsheet = $reader->load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $data = $worksheet->toArray();
                
                $headers = array_shift($data); // Remove header row
                
                foreach ($data as $index => $row) {
                    $rowNumber = $index + 1;
                    $rowData = array_combine($headers, $row);
                    
                    try {
                        $wasUpdated = $this->processArrearsRow($rowData);
                        $imported++;
                        $successRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'action' => $wasUpdated ? 'Updated' : 'Created'
                        ];
                    } catch (Exception $e) {
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                        $errorRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'error' => $e->getMessage()
                        ];
                    }
                }
            }

            // Calculate updated vs created counts
            $updatedCount = 0;
            $createdCount = 0;
            foreach ($successRows as $row) {
                if (isset($row['action']) && $row['action'] === 'Updated') {
                    $updatedCount++;
                } else {
                    $createdCount++;
                }
            }

            // Prepare import statistics
            $importStats = [
                'total_rows' => count($data),
                'successful_imports' => $imported,
                'failed_imports' => count($errorRows),
                'updated_count' => $updatedCount,
                'created_count' => $createdCount,
                'success_rows' => $successRows,
                'error_rows' => $errorRows,
                'type' => 'arrears'
            ];

            return redirect()->back()->with('import_stats', $importStats);
        } catch (Exception $e) {
            Log::error('Bulk import arrears failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to process file. Please check the format and try again.');
        }
    }

    /**
     * Process a single row of arrears data
     */
    private function processArrearsRow($rowData)
    {
        // Validate required fields
        if (empty($rowData['first_name']) || empty($rowData['last_name']) || empty($rowData['cnic']) || empty($rowData['amount']) || empty($rowData['date'])) {
            throw new Exception('Missing required fields: first_name, last_name, cnic, amount, or date');
        }

        // Validate CNIC format (5 digits-7 digits-1 digit)
        if (!preg_match('/^\d{5}-\d{7}-\d$/', $rowData['cnic'])) {
            throw new Exception("Invalid CNIC format: {$rowData['cnic']}. Expected format: 35201-1234567-1");
        }

        // Validate student exists by CNIC and has on_roll status
        $student = Student::where('cnic', $rowData['cnic'])
                         ->where('status', 'on_roll')
                         ->first();
        if (!$student) {
            // Check if student exists but has different status
            $studentExists = Student::where('cnic', $rowData['cnic'])->first();
            if ($studentExists) {
                throw new Exception("Student with CNIC {$rowData['cnic']} exists but is not currently enrolled (status: {$studentExists->status}). Only 'on_roll' students are allowed.");
            } else {
                throw new Exception("Student with CNIC {$rowData['cnic']} not found in the system.");
            }
        }

        // Validate amount
        if (!is_numeric($rowData['amount']) || $rowData['amount'] <= 0) {
            throw new Exception("Invalid amount: {$rowData['amount']}");
        }

        // Validate date format and ensure it's in the past
        $inputDate = DateTime::createFromFormat('m/d/Y', $rowData['date']);
        if (!$inputDate) {
            // Try alternative format Y-m-d
            $inputDate = DateTime::createFromFormat('Y-m-d', $rowData['date']);
            if (!$inputDate) {
                throw new Exception("Invalid date format: {$rowData['date']}. Please use MM/DD/YYYY or YYYY-MM-DD format.");
            }
        }

        // Ensure the date is in the past
        $today = new DateTime();
        $today->setTime(23, 59, 59); // End of today
        
        if ($inputDate > $today) {
            throw new Exception("Date must be in the past: {$rowData['date']}");
        }

        $wasUpdated = false;
        DB::transaction(function () use ($rowData, $student, $inputDate, &$wasUpdated) {
            // Check if arrears already exist for this student for the same month and year
            $existingArrears = StudentArrearsHistory::where('student_id', $student->id)
                ->whereYear('carried_date', $inputDate->format('Y'))
                ->whereMonth('carried_date', $inputDate->format('m'))
                ->whereNull('cleared_date') // Only check uncleared arrears
                ->first();

            if ($existingArrears) {
                // Update existing record
                $existingArrears->update([
                    'amount' => $rowData['amount'],
                    'carried_date' => $inputDate->format('Y-m-d'),
                    'updated_at' => now(),
                ]);
                $wasUpdated = true;
            } else {
                // Create new record
                StudentArrearsHistory::create([
                    'student_id' => $student->id,
                    'from_invoice_id' => null,
                    'to_invoice_id' => null,
                    'amount' => $rowData['amount'],
                    'carried_date' => $inputDate->format('Y-m-d'),
                    'cleared_by_payment_id' => null,
                    'cleared_date' => null,
                ]);
            }
        });
        
        return $wasUpdated;
    }

    /**
     * Bulk import previous advance payments from CSV/Excel
     */
    public function bulkImportPreviousAdvance(Request $request)
    {
        $request->validate([
            'advance_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
        ]);

        try {
            $file = $request->file('advance_file');
            $extension = $file->getClientOriginalExtension();
            
            $imported = 0;
            $errors = [];
            $successRows = [];
            $errorRows = [];

            if ($extension === 'csv') {
                $data = array_map('str_getcsv', file($file->getPathname()));
                $headers = array_shift($data);
                
                foreach ($data as $index => $row) {
                    $rowNumber = $index + 1;
                    $rowData = array_combine($headers, $row);
                    
                    try {
                        $wasUpdated = $this->processAdvanceRow($rowData);
                        $imported++;
                        $successRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'action' => $wasUpdated ? 'Updated' : 'Created'
                        ];
                    } catch (Exception $e) {
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                        $errorRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'error' => $e->getMessage()
                        ];
                    }
                }
            } else {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getPathname());
                $spreadsheet = $reader->load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $data = $worksheet->toArray();
                
                $headers = array_shift($data);
                
                foreach ($data as $index => $row) {
                    $rowNumber = $index + 1;
                    $rowData = array_combine($headers, $row);
                    
                    try {
                        $wasUpdated = $this->processAdvanceRow($rowData);
                        $imported++;
                        $successRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'action' => $wasUpdated ? 'Updated' : 'Created'
                        ];
                    } catch (Exception $e) {
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                        $errorRows[] = [
                            'row' => $rowNumber,
                            'data' => $rowData,
                            'error' => $e->getMessage()
                        ];
                    }
                }
            }

            // Calculate updated vs created counts
            $updatedCount = 0;
            $createdCount = 0;
            foreach ($successRows as $row) {
                if (isset($row['action']) && $row['action'] === 'Updated') {
                    $updatedCount++;
                } else {
                    $createdCount++;
                }
            }

            // Prepare import statistics
            $importStats = [
                'total_rows' => count($data),
                'successful_imports' => $imported,
                'failed_imports' => count($errorRows),
                'updated_count' => $updatedCount,
                'created_count' => $createdCount,
                'success_rows' => $successRows,
                'error_rows' => $errorRows,
                'type' => 'advance'
            ];

            return redirect()->back()->with('import_stats', $importStats);
        } catch (Exception $e) {
            Log::error('Bulk import advance payments failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to process file. Please check the format and try again.');
        }
    }

    /**
     * Process a single row of advance payment data
     */
    private function processAdvanceRow($rowData)
    {
        // Validate required fields
        if (empty($rowData['first_name']) || empty($rowData['last_name']) || empty($rowData['cnic']) || empty($rowData['amount']) || empty($rowData['date'])) {
            throw new Exception('Missing required fields: first_name, last_name, cnic, amount, or date');
        }

        // Validate CNIC format (5 digits-7 digits-1 digit)
        if (!preg_match('/^\d{5}-\d{7}-\d$/', $rowData['cnic'])) {
            throw new Exception("Invalid CNIC format: {$rowData['cnic']}. Expected format: 35201-1234567-1");
        }

        // Validate student exists by CNIC and has on_roll status
        $student = Student::where('cnic', $rowData['cnic'])
                         ->where('status', 'on_roll')
                         ->first();
        if (!$student) {
            // Check if student exists but has different status
            $studentExists = Student::where('cnic', $rowData['cnic'])->first();
            if ($studentExists) {
                throw new Exception("Student with CNIC {$rowData['cnic']} exists but is not currently enrolled (status: {$studentExists->status}). Only 'on_roll' students are allowed.");
            } else {
                throw new Exception("Student with CNIC {$rowData['cnic']} not found in the system.");
            }
        }

        // Validate amount
        if (!is_numeric($rowData['amount']) || $rowData['amount'] <= 0) {
            throw new Exception("Invalid amount: {$rowData['amount']}");
        }

        // Validate date format and ensure it's in the past
        $inputDate = DateTime::createFromFormat('m/d/Y', $rowData['date']);
        if (!$inputDate) {
            // Try alternative format Y-m-d
            $inputDate = DateTime::createFromFormat('Y-m-d', $rowData['date']);
            if (!$inputDate) {
                throw new Exception("Invalid date format: {$rowData['date']}. Please use MM/DD/YYYY or YYYY-MM-DD format.");
            }
        }

        // Ensure the date is in the past
        $today = new DateTime();
        $today->setTime(23, 59, 59); // End of today
        
        if ($inputDate > $today) {
            throw new Exception("Date must be in the past: {$rowData['date']}");
        }

        $wasUpdated = false;
        DB::transaction(function () use ($rowData, $student, $inputDate, &$wasUpdated) {
            // Check if advance payment already exists for this student for the same month and year
                                        $existingAdvance = StudentArrearsHistory::where('student_id', $student->id)
                                ->whereYear('carried_date', $inputDate->format('Y'))
                                ->whereMonth('carried_date', $inputDate->format('m'))
                                ->where('amount', '<', 0) // Only check advance payments (negative amounts)
                                ->whereNull('from_invoice_id') // Only check unapplied advances
                                ->whereNull('to_invoice_id') // Only check unapplied advances
                                ->first();

            if ($existingAdvance) {
                // Update existing record
                $existingAdvance->update([
                    'amount' => -$rowData['amount'], // Negative amount for advance payments
                    'carried_date' => $inputDate->format('Y-m-d'),
                    'updated_at' => now(),
                ]);
                $wasUpdated = true;
            } else {
                // Create new record
                StudentArrearsHistory::create([
                    'student_id' => $student->id,
                    'from_invoice_id' => null,
                    'to_invoice_id' => null,
                    'amount' => -$rowData['amount'], // Negative amount for advance payments
                    'carried_date' => $inputDate->format('Y-m-d'),
                    'cleared_by_payment_id' => null,
                    'cleared_date' => null,
                ]);
            }
        });
        
        return $wasUpdated;
    }


    /**
     * Download template files for bulk import
     */
    public function downloadTemplate($type)
    {
        if (!in_array($type, ['arrears', 'advance'])) {
            return redirect()->back()->with('error', 'Invalid template type.');
        }

        $filename = $type . '_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($type) {
            $file = fopen('php://output', 'w');
            
            // Add headers with new column structure
            fputcsv($file, ['first_name', 'middle_name', 'last_name', 'cnic', 'amount', 'date']);
            
            // Add sample data with MM/DD/YYYY format
            fputcsv($file, ['Ahmed', 'Ali', 'Khan', '35201-1234567-1', '5000.00', '01/15/2024']);
            fputcsv($file, ['Fatima', 'Bibi', 'Hussain', '35201-1234567-2', '3000.00', '02/01/2024']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
