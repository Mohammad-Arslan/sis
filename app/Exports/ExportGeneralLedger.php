<?php

namespace App\Exports;

use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use App\Models\Payroll;
use App\Models\Asset;
use App\Models\StudentArrearsHistory;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ExportGeneralLedger implements WithMultipleSheets
{
    protected $fromDate;
    protected $toDate;
    protected $branchId;
    protected $accountType;
    protected $transactionType;
    protected $searchTerm;

    public function __construct($fromDate = null, $toDate = null, $branchId = null, $accountType = null, $transactionType = null, $searchTerm = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->branchId = $branchId;
        $this->accountType = $accountType;
        $this->transactionType = $transactionType;
        $this->searchTerm = $searchTerm;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        
        // Define transaction types and their sheet names
        $transactionTypes = [
            'Student Fee' => 'Student Fees',
            'Late Fee' => 'Late Fees', 
            'Arrears Fine' => 'Arrears Fines',
            'Payroll' => 'Payroll',
            'Asset Purchase' => 'Asset Purchases'
        ];
        
        // Define which transaction types belong to which account types
        $accountTypeMapping = [
            'Revenue' => ['Student Fee', 'Late Fee', 'Arrears Fine'],
            'Expense' => ['Payroll', 'Asset Purchase']
        ];
        
        // Determine which transaction types to include based on filters
        $transactionTypesToInclude = [];
        
        if ($this->transactionType) {
            // If specific transaction type is selected, only create that sheet
            $transactionTypesToInclude = [$this->transactionType];
        } elseif ($this->accountType) {
            // If account type is selected, include all transaction types under that account type
            $transactionTypesToInclude = $accountTypeMapping[$this->accountType] ?? [];
        } else {
            // If no filters, include all transaction types
            $transactionTypesToInclude = array_keys($transactionTypes);
        }
        
        // Create sheets only for the determined transaction types
        foreach ($transactionTypesToInclude as $transactionType) {
            if (isset($transactionTypes[$transactionType])) {
                $sheets[] = new GeneralLedgerSheet($transactionType, $transactionTypes[$transactionType], $this->fromDate, $this->toDate, $this->branchId, $this->accountType, $this->transactionType, $this->searchTerm);
            }
        }
        
        return $sheets;
    }
}

class GeneralLedgerSheet implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithTitle
{
    protected $transactionType;
    protected $sheetName;
    protected $fromDate;
    protected $toDate;
    protected $branchId;
    protected $filterAccountType;
    protected $filterTransactionType;
    protected $searchTerm;

    public function __construct($transactionType, $sheetName, $fromDate = null, $toDate = null, $branchId = null, $filterAccountType = null, $filterTransactionType = null, $searchTerm = null)
    {
        $this->transactionType = $transactionType;
        $this->sheetName = $sheetName;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->branchId = $branchId;
        $this->filterAccountType = $filterAccountType;
        $this->filterTransactionType = $filterTransactionType;
        $this->searchTerm = $searchTerm;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 300);
        
        // Apply branch filter for non-admin users
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $this->branchId = get_branch_id();
        }
        
        // Collect all financial transactions
        $transactions = collect();
        
        // 1. Student Fee Revenue
        $feeQuery = StudentInvoice::with([
            'student:id,first_name,last_name,registration_no,roll_no,branch_id',
            'student.branch:id,br_name,branch_code'
        ]);
        
        // Apply date filtering only if dates are provided
        if ($this->fromDate && $this->toDate) {
            $feeQuery->whereBetween('issue_date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $feeQuery->where('issue_date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $feeQuery->where('issue_date', '<=', $this->toDate);
        }
        
        if ($this->branchId) {
            $feeQuery->whereHas('student', function ($q) {
                $q->where('branch_id', $this->branchId);
            });
        }
        
        $studentInvoices = $feeQuery->get();
        
        foreach ($studentInvoices as $invoice) {
            // Main fee revenue
            if ($invoice->total_payable > 0) {
                $transactions->push([
                    'date' => $invoice->issue_date,
                    'account' => 'Student Fee Revenue',
                    'account_type' => 'Revenue',
                    'description' => 'Fee Invoice #' . $invoice->invoice_no . ' - ' . ($invoice->student->first_name ?? '') . ' ' . ($invoice->student->last_name ?? ''),
                    'reference' => $invoice->invoice_no,
                    'debit' => 0,
                    'credit' => $invoice->total_payable,
                    'branch' => $invoice->student->branch->br_name ?? '-',
                    'transaction_type' => 'Student Fee'
                ]);
            }
            
            // Calculate Late Fee (2.5% of total_payable if due date passed)
            if ($invoice->due_date && $invoice->due_date < now()->toDateString()) {
                $lateFeeAmount = $invoice->total_payable * 0.025; // 2.5% of total payable
                if ($lateFeeAmount > 0) {
                    $transactions->push([
                        'date' => $invoice->due_date,
                        'account' => 'Late Fee Revenue',
                        'account_type' => 'Revenue',
                        'description' => 'Late Fee (2.5%) for Invoice #' . $invoice->invoice_no . ' - Due: ' . date('d-m-Y', strtotime($invoice->due_date)),
                        'reference' => $invoice->invoice_no,
                        'debit' => 0,
                        'credit' => $lateFeeAmount,
                        'branch' => $invoice->student->branch->br_name ?? '-',
                        'transaction_type' => 'Late Fee'
                    ]);
                }
            }
        }
        
        // 2.5. Arrears Fines from arrears_history table
        $arrearsQuery = StudentArrearsHistory::with([
            'student:id,first_name,last_name,branch_id',
            'student.branch:id,br_name',
            'from_invoice:id,invoice_no'
        ])->whereNull('cleared_date'); // Only uncleared arrears
        
        // Apply date filtering
        if ($this->fromDate && $this->toDate) {
            $arrearsQuery->whereBetween('carried_date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $arrearsQuery->where('carried_date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $arrearsQuery->where('carried_date', '<=', $this->toDate);
        }
        
        if ($this->branchId) {
            $arrearsQuery->whereHas('student', function($query) {
                $query->where('branch_id', $this->branchId);
            });
        }
        
        $arrears = $arrearsQuery->get();
        
        foreach ($arrears as $arrear) {
            $transactions->push([
                'date' => $arrear->carried_date,
                'account' => 'Arrears Fine Revenue',
                'account_type' => 'Revenue',
                'description' => 'Arrears Fine for ' . ($arrear->student->first_name ?? '') . ' ' . ($arrear->student->last_name ?? '') . ' - Invoice #' . ($arrear->from_invoice->invoice_no ?? 'N/A'),
                'reference' => $arrear->from_invoice->invoice_no ?? 'ARR-' . $arrear->id,
                'debit' => 0,
                'credit' => $arrear->amount,
                'branch' => $arrear->student->branch->br_name ?? '-',
                'transaction_type' => 'Arrears Fine'
            ]);
        }
        
        // 3. Payroll Expenses
        $payrollQuery = Payroll::with([
            'employee:id,preferred_name,branch_id',
            'employee.branch:id,br_name,branch_code'
        ]);
        
        // Apply date filtering only if dates are provided
        if ($this->fromDate && $this->toDate) {
            $payrollQuery->whereBetween('processed_at', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $payrollQuery->where('processed_at', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $payrollQuery->where('processed_at', '<=', $this->toDate);
        }
        
        if ($this->branchId) {
            $payrollQuery->whereHas('employee', function ($q) {
                $q->where('branch_id', $this->branchId);
            });
        }
        
        $payrolls = $payrollQuery->get();
        
        foreach ($payrolls as $payroll) {
            $transactions->push([
                'date' => $payroll->processed_at,
                'account' => 'Employee Salaries',
                'account_type' => 'Expense',
                'description' => 'Salary for ' . ($payroll->employee->preferred_name ?? '') . ' - ' . $payroll->month . '/' . $payroll->year,
                'reference' => 'PAY-' . $payroll->id,
                'debit' => $payroll->net_salary,
                'credit' => 0,
                'branch' => $payroll->employee->branch->br_name ?? '-',
                'transaction_type' => 'Payroll'
            ]);
        }
        
        // 4. Asset Purchases
        $assetQuery = Asset::with([
            'currentBranch:id,br_name,branch_code',
            'category:id,name'
        ]);
        
        // Apply date filtering only if dates are provided
        if ($this->fromDate && $this->toDate) {
            $assetQuery->whereBetween('purchase_date', [$this->fromDate, $this->toDate]);
        } elseif ($this->fromDate) {
            $assetQuery->where('purchase_date', '>=', $this->fromDate);
        } elseif ($this->toDate) {
            $assetQuery->where('purchase_date', '<=', $this->toDate);
        }
        
        if ($this->branchId) {
            $assetQuery->where('current_branch_id', $this->branchId);
        }
        
        $assets = $assetQuery->get();
        
        foreach ($assets as $asset) {
            // Asset purchase as expense (business expense)
            $transactions->push([
                'date' => $asset->purchase_date,
                'account' => 'Asset Purchase Expense',
                'account_type' => 'Expense',
                'description' => 'Asset Purchase: ' . $asset->name . ' (' . $asset->asset_tag . ') - Assigned to ' . ($asset->currentBranch->br_name ?? 'Unknown Branch'),
                'reference' => $asset->asset_tag,
                'debit' => $asset->purchase_price,
                'credit' => 0,
                'branch' => $asset->currentBranch->br_name ?? '-',
                'transaction_type' => 'Asset Purchase'
            ]);
        }
        
        // Filter by transaction type for this sheet
        $transactions = $transactions->filter(function($transaction) {
            return $transaction['transaction_type'] === $this->transactionType;
        });
        
        // Apply additional filters if specified
        if ($this->filterTransactionType && $this->filterTransactionType !== $this->transactionType) {
            return collect(); // Return empty collection if filter doesn't match this sheet
        }
        
        if ($this->filterAccountType) {
            $transactions = $transactions->filter(function($transaction) {
                return $transaction['account_type'] === $this->filterAccountType;
            });
        }
        
        if ($this->searchTerm) {
            $searchTerm = strtolower($this->searchTerm);
            $transactions = $transactions->filter(function($transaction) use ($searchTerm) {
                return strpos(strtolower($transaction['account']), $searchTerm) !== false ||
                       strpos(strtolower($transaction['description']), $searchTerm) !== false ||
                       strpos(strtolower($transaction['reference']), $searchTerm) !== false;
            });
        }
        
        // Sort transactions by date
        return $transactions->sortBy('date');
    }

    /**
     * @param mixed $transaction
     * @return array
     */
    public function map($transaction): array
    {
        return [
            $transaction['date'] ? date('d-m-Y', strtotime($transaction['date'])) : '-',
            $transaction['account'],
            $transaction['account_type'],
            $transaction['description'],
            $transaction['reference'],
            $transaction['debit'] > 0 ? number_format($transaction['debit'], 2) : '-',
            $transaction['credit'] > 0 ? number_format($transaction['credit'], 2) : '-',
            $transaction['branch'],
            $transaction['transaction_type'],
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->sheetName;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Account',
            'Account Type',
            'Description',
            'Reference',
            'Debit',
            'Credit',
            'Branch',
            'Transaction Type',
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Style the header row with different colors for each transaction type
                $headerColors = [
                    'Student Fee' => '28A745',    // Green
                    'Late Fee' => 'FFC107',       // Yellow
                    'Arrears Fine' => 'FD7E14',   // Orange
                    'Payroll' => 'DC3545',        // Red
                    'Asset Purchase' => '6F42C1'  // Purple
                ];
                
                $headerColor = $headerColors[$this->transactionType] ?? '4472C4';
                
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $headerColor],
                    ],
                ]);

                // Auto-size columns
                $event->sheet->getColumnDimension('A')->setWidth(12);
                $event->sheet->getColumnDimension('B')->setWidth(20);
                $event->sheet->getColumnDimension('C')->setWidth(15);
                $event->sheet->getColumnDimension('D')->setWidth(40);
                $event->sheet->getColumnDimension('E')->setWidth(15);
                $event->sheet->getColumnDimension('F')->setWidth(12);
                $event->sheet->getColumnDimension('G')->setWidth(12);
                $event->sheet->getColumnDimension('H')->setWidth(15);
                $event->sheet->getColumnDimension('I')->setWidth(18);

                // Add borders to all cells
                $event->sheet->getStyle('A1:I' . ($event->sheet->getHighestRow()))
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);

                // Format number columns
                $event->sheet->getStyle('F2:G' . ($event->sheet->getHighestRow()))
                    ->getNumberFormat()
                    ->setFormatCode('#,##0.00');
            },
        ];
    }
}
