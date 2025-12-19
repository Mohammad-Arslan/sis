<?php

namespace App\Services;

use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use App\Models\StudentArrearsHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ArrearsService
{
    /**
     * Check and create arrears for overdue invoices
     * Uses validity_date instead of due_date for overdue check
     */
    public static function checkAndCreateArrears()
    {
        $studentsWithInvoices = StudentInvoice::distinct('student_id')->pluck('student_id');

        foreach ($studentsWithInvoices as $studentId) {
            self::processStudentArrears($studentId);
        }
    }

    /**
     * Process arrears for a specific student
     */
    public static function processStudentArrears($studentId)
    {
        // Get all invoices for the student that are overdue (validity_date passed)
        // Include unpaid and partially_paid invoices that are overdue
        // Note: overpaid invoices should not create arrears since they have excess payment
        $studentInvoices = StudentInvoice::where('student_id', $studentId)
            ->whereIn('bank_payment_status', ['unpaid', 'partially_paid'])
            ->where('validity_date', '<', now()->startOfDay()) // Use validity_date for overdue check
            ->orderBy('validity_date', 'asc')
            ->get();

        foreach ($studentInvoices as $invoice) {
            self::createArrearsForInvoice($invoice);
        }
    }

    /**
     * Create arrears record for a specific invoice
     */
    public static function createArrearsForInvoice(StudentInvoice $invoice)
    {
        // Check if arrears already exist for this invoice
        $existingArrears = StudentArrearsHistory::where('from_invoice_id', $invoice->id)
            ->whereNull('cleared_date')
            ->first();

        // Calculate outstanding amount
        $outstandingAmount = self::calculateOutstandingAmount($invoice);

        if ($existingArrears) {
            if ($outstandingAmount <= 0) {
                // No outstanding amount, mark as cleared
                $existingArrears->update([
                    'cleared_date' => Carbon::now(),
                ]);
                //\Log::info("ArrearsService: Cleared existing arrears for Invoice #{$invoice->id}");
                return null;
            } else {
                // Update amount if different
                if ($existingArrears->amount != $outstandingAmount) {
                    $existingArrears->update(['amount' => $outstandingAmount]);
                    \Log::info("ArrearsService: Updated arrears amount for Invoice #{$invoice->id} to ₹{$outstandingAmount}");
                }
                return $existingArrears;
            }
        }

        if ($outstandingAmount <= 0) {
            //\Log::info("ArrearsService: No arrears to create for Invoice #{$invoice->id} - no outstanding amount");
            return null; // No arrears to create
        }

        // Create arrears record
        try {
            $arrears = StudentArrearsHistory::create([
                'student_id' => $invoice->student_id,
                'from_invoice_id' => $invoice->id,
                'to_invoice_id' => null, // Will be set when carried to another invoice
                'amount' => $outstandingAmount,
                'carried_date' => Carbon::now(),
                'cleared_by_payment_id' => null,
                'cleared_date' => null,
            ]);

            //\Log::info("ArrearsService: Created arrears record #{$arrears->id} for Invoice #{$invoice->id} with amount ₹{$outstandingAmount}");
            return $arrears;
        } catch (\Exception $e) {
            \Log::error("ArrearsService: Failed to create arrears for Invoice #{$invoice->id}", [
                'error' => $e->getMessage(),
                'student_id' => $invoice->student_id,
                'amount' => $outstandingAmount
            ]);
            return null;
        }
    }

    /**
     * Calculate outstanding amount for an invoice
     * Formula: total_payable - payments_made
     */
    public static function calculateOutstandingAmount(StudentInvoice $invoice)
    {
        $totalPayable = $invoice->total_payable ?? 0;
        $totalPaid = $invoice->payments()->sum('amount');

        return max(0, $totalPayable - $totalPaid);
    }

    /**
     * Carry arrears to a new invoice
     */
    public static function carryArrearsToInvoice($studentId, StudentInvoice $newInvoice)
    {
        $unclearedArrears = StudentArrearsHistory::where('student_id', $studentId)
            ->whereNull('cleared_date')
            ->whereNull('to_invoice_id')
            ->get();

        $totalCarried = 0;
        foreach ($unclearedArrears as $arrears) {
            $fromInvoice = StudentInvoice::find($arrears->from_invoice_id);
            if ($fromInvoice && $fromInvoice->validity_date && Carbon::parse($fromInvoice->validity_date)->lt(now()->startOfDay())) {
                $arrears->update([
                    'to_invoice_id' => $newInvoice->id,
                ]);
                $totalCarried += $arrears->amount;
            }
        }

        // Update the invoice's total_payable to include the carried arrears
        if ($totalCarried > 0) {
            $newInvoice->total_payable += $totalCarried;
            $newInvoice->save();
        }

        return $unclearedArrears;
    }

    /**
     * Clear arrears when payment is made
     */
    public static function clearArrearsWithPayment(StudentPayment $payment)
    {
        $invoice = $payment->invoice;
        if (! $invoice) {
            return false;
        }

        $paymentAmount = $payment->amount;
        $remainingAmount = $paymentAmount;

        // Get all uncleared arrears for this student (only positive amounts)
        $unclearedArrears = StudentArrearsHistory::where('student_id', $invoice->student_id)
            ->whereNull('cleared_date')
            ->where('amount', '>', 0) // Only process arrears (positive amounts), not advances
            ->orderBy('carried_date', 'asc')
            ->get();

        foreach ($unclearedArrears as $arrears) {
            if ($remainingAmount <= 0) {
                break;
            }

            $amountToClear = min($remainingAmount, $arrears->amount);

            if ($amountToClear >= $arrears->amount) {
                // Clear entire arrears record
                $arrears->update([
                    'cleared_by_payment_id' => $payment->id,
                    'cleared_date' => Carbon::now(),
                ]);
            } else {
                // Partial clearance - create new arrears record for remaining amount
                $remainingArrears = $arrears->amount - $amountToClear;

                $arrears->update([
                    'amount' => $amountToClear,
                    'cleared_by_payment_id' => $payment->id,
                    'cleared_date' => Carbon::now(),
                ]);

                // Create new record for remaining amount
                StudentArrearsHistory::create([
                    'student_id' => $arrears->student_id,
                    'from_invoice_id' => $arrears->from_invoice_id,
                    'to_invoice_id' => $arrears->to_invoice_id,
                    'amount' => $remainingArrears,
                    'carried_date' => $arrears->carried_date,
                    'cleared_by_payment_id' => null,
                    'cleared_date' => null,
                ]);
                \Log::info("ArrearsService: Created arrears record for Invoice #{$arrears->from_invoice_id} with amount {$remainingArrears}");
            }

            $remainingAmount -= $amountToClear;
        }
        \Log::info("ArrearsService: Remaining payment amount after clearing arrears: {$remainingAmount}");

        return true;
    }

    /**
     * Get total arrears for a student
     */
    public static function getStudentArrears($studentId)
    {
        return StudentArrearsHistory::where('student_id', $studentId)
            ->whereNull('cleared_date')
            ->where('amount', '>', 0) // Only count arrears (positive amounts), not advances
            ->sum('amount');
    }

    /**
     * Get arrears breakdown for a student
     */
    public static function getStudentArrearsBreakdown($studentId)
    {
        return StudentArrearsHistory::where('student_id', $studentId)
            ->whereNull('cleared_date')
            ->with(['from_invoice', 'to_invoice'])
            ->get();
    }

    /**
     * Get arrears for a specific invoice
     */
    public static function getInvoiceArrears($invoiceId)
    {
        return StudentArrearsHistory::where('from_invoice_id', $invoiceId)
            ->whereNull('cleared_date')
            ->sum('amount');
    }

    /**
     * Calculate total outstanding amount for an invoice (including carried arrears)
     */
    public static function calculateInvoiceOutstanding(StudentInvoice $invoice)
    {
        // Base outstanding for this invoice
        $baseOutstanding = self::calculateOutstandingAmount($invoice);

        // Add carried arrears from previous invoices
        $carriedArrears = StudentArrearsHistory::where('to_invoice_id', $invoice->id)
            ->whereNull('cleared_date')
            ->sum('amount');

        return $baseOutstanding + $carriedArrears;
    }

    /**
     * Handle overpayment and advance payments
     * If student pays more than invoice amount, it should reduce next month's invoice
     */
    public static function handleOverpayment(StudentPayment $payment)
    {
        $invoice = $payment->invoice;
        if (! $invoice) {
            return false;
        }

        $invoiceAmount = $invoice->total_payable ?? 0;
        $totalPaidForInvoice = $invoice->payments()->sum('amount');
        $overpayment = $totalPaidForInvoice - $invoiceAmount;

        if ($overpayment > 0) {
            // Create advance payment record
            return self::createAdvancePayment($invoice->student_id, $overpayment, $payment);
        }

        return false;
    }

    /**
     * Create advance payment record for overpayment
     */
    public static function createAdvancePayment($studentId, $amount, StudentPayment $sourcePayment)
    {
        // Check if there are any uncleared arrears to apply this advance to
        $unclearedArrears = StudentArrearsHistory::where('student_id', $studentId)
            ->whereNull('cleared_date')
            ->orderBy('carried_date', 'asc')
            ->get();

        $remainingAdvance = $amount;

        foreach ($unclearedArrears as $arrears) {
            if ($remainingAdvance <= 0) {
                break;
            }

            $amountToClear = min($remainingAdvance, $arrears->amount);

            if ($amountToClear >= $arrears->amount) {
                // Clear entire arrears record
                $arrears->update([
                    'cleared_by_payment_id' => $sourcePayment->id,
                    'cleared_date' => Carbon::now(),
                ]);
            } else {
                // Partial clearance
                $remainingArrears = $arrears->amount - $amountToClear;
                \Log::info("ArrearsService: Remaining arrears amount: {$remainingArrears}");
                $arrears->update([
                    'amount' => $amountToClear,
                    'cleared_by_payment_id' => $sourcePayment->id,
                    'cleared_date' => Carbon::now(),
                ]);

                // Create new record for remaining amount
                $arrears =  StudentArrearsHistory::create([
                    'student_id' => $arrears->student_id,
                    'from_invoice_id' => $arrears->from_invoice_id,
                    'to_invoice_id' => $arrears->to_invoice_id,
                    'amount' => $remainingArrears,
                    'carried_date' => $arrears->carried_date,
                    'cleared_by_payment_id' => null,
                    'cleared_date' => null,
                ]);
                \Log::info("ArrearsService: Created arrears record #{$arrears->id} for Invoice #{$arrears->from_invoice_id} with amount {$remainingArrears}");
            }

            $remainingAdvance -= $amountToClear;
        }
        \Log::info("ArrearsService: Remaining advance amount: {$remainingAdvance}");

        return $remainingAdvance; // Return any remaining advance amount
    }

    /**
     * Get advance payment amount for a student (overpayment that can be applied to future invoices)
     */
    public static function getAdvancePaymentAmount($studentId)
    {
        // Get all uncleared advance records (negative amounts in arrears history)
        // Only count advances that haven't been applied to any invoice yet
        $totalAdvance = abs(StudentArrearsHistory::where('student_id', $studentId)
            ->whereNull('cleared_date')
            ->whereNull('to_invoice_id') // Only count advances not yet applied to invoices
            ->where('amount', '<', 0) // Advance records have negative amounts
            ->sum('amount'));

        return $totalAdvance;
    }

    /**
     * Apply advance payment to an invoice and clear the advance record
     */
    public static function applyAdvanceToInvoice($studentId, $invoiceId, $amountToApply)
    {
        // Get available advance records
        $advanceRecords = StudentArrearsHistory::where('student_id', $studentId)
            ->whereNull('cleared_date')
            ->whereNull('to_invoice_id')
            ->where('amount', '<', 0) // Advance records have negative amounts
            ->orderBy('carried_date', 'asc')
            ->get();

        $remainingAmountToApply = $amountToApply;

        foreach ($advanceRecords as $advance) {
            if ($remainingAmountToApply <= 0) {
                break;
            }

            $advanceAmount = abs($advance->amount);
            $amountToUse = min($remainingAmountToApply, $advanceAmount);

            if ($amountToUse >= $advanceAmount) {
                // Use entire advance record
                $advance->update([
                    'to_invoice_id' => $invoiceId,
                    'cleared_date' => Carbon::now(),
                ]);
            } else {
                // Use partial advance record
                $remainingAdvance = $advanceAmount - $amountToUse;

                // Update existing record with remaining amount
                $advance->update([
                    'amount' => -$remainingAdvance, // Keep negative for advance
                ]);

                // Create new record for the used portion
                StudentArrearsHistory::create([
                    'student_id' => $studentId,
                    'from_invoice_id' => $advance->from_invoice_id,
                    'to_invoice_id' => $invoiceId,
                    'amount' => -$amountToUse, // Negative amount for advance
                    'carried_date' => $advance->carried_date,
                    'cleared_date' => Carbon::now(), // Mark as applied
                    'cleared_by_payment_id' => null,
                ]);
            }

            $remainingAmountToApply -= $amountToUse;
        }

        return $amountToApply - $remainingAmountToApply; // Return actual amount applied
    }
}
