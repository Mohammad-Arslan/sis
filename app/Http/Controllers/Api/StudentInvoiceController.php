<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentArrearsHistory;
use App\Models\StudentInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentInvoiceController extends Controller
{
    public function studentChallan(Request $request)
    {
        $data = StudentInvoice::where(
            'student_id',
            $request->student_id
        )->latest()->get();

        //dd($data->toArray());

        foreach ($data as $row) {
            $paid_date = Carbon::parse($row->paid_date)->format('M d-Y');
            $due_date = Carbon::parse($row->due_date)->format('m-d-Y');
            $date = Carbon::parse($row->due_date);
            $now = Carbon::now();

            $days_left = $date->diffInDays($now);
            $issue_date = Carbon::parse($row->issue_date)->format('d M');
            $validity_date = Carbon::parse($row->validity_date)->format('M d-Y');

            $row['paid_due_format'] = $paid_date;
            $row['due_date_format'] = $due_date;
            $row['issue_date_format'] = $issue_date;
            $row['validity_date_format'] = $validity_date;
            $row['days_left'] = $days_left;

            $total = calculate_total_price_by_invoice($row);
            $row['total'] = number_format($total['total']);
        }
        return response($data, 200);
    }

    // public function showInvoice($id)
    // {
    //     $studentInvoice = StudentInvoice::where(
    //         'id',
    //         $id
    //     )->with([
    //         'student',
    //         'student_fee_package.fee_package',
    //         'student_fee_package.fee_concession.fee_concession_type',
    //         'student_fee_package.academic_year',
    //         'student_fee_package.com_class',
    //         'student_fee_package.section',
    //         'student_invoice_items.fee_charges.fee_charges_type',
    //         'invoice_type',
    //         'payment_source',
    //         'promo.promo_type',
    //         'fee_period'
    //     ])->first();

    //     if (get_month_name($studentInvoice->fee_period->from_date) == 'February') {
    //         $calculations = calculate_total_price_by_invoice_index($studentInvoice);
    //     } else {
    //         $calculations = calculate_total_price_by_invoice($studentInvoice);
    //     }
    //     // $calculations = calculate_total_price_by_invoice($studentInvoice);
    //     return view('students.api_invoice_detail_modal', ['student_invoice' => $studentInvoice, 'total' => $calculations]);
    // }


    public function showInvoice($id)
    {
        $studentInvoice = StudentInvoice::where(
            'id',
            $id
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

        return view('students.api_invoice_detail_modal', ['student_invoice' => $studentInvoice, 'total' => $calculations]);
    }

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
}
