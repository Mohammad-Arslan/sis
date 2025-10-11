<?php

namespace App\Http\Controllers;

use App\Models\DeductionType;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeaveQuota;
use App\Models\EmployeeOfficialLeaveDay;
use App\Models\EmployeeWorkingDay;
use App\Models\EmployeeSalaryStructure;
use App\Models\EmployeeTaxPreference;
use App\Models\EmployeeDeductionPreference;
use App\Models\IncomeTaxSlab;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\ProvidentFundDefinition;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Payroll::with(['employee', 'processedBy']);
            return datatables()->of($data)
                ->addColumn('employee', function($row) {
                    $empName = $row->employee ? $row->employee->full_name ?? $row->employee->preferred_name : '-';
                    $empId = $row->employee ? $row->employee->employee_id : '-';
                    return '<div class="fw-bold">' . $empName . '</div><small class="text-muted">' . $empId . '</small>';
                })
                ->addColumn('period', function($row) {
                    $monthName = Carbon::createFromFormat('!m', $row->month)->format('F');
                    return $monthName . ' - ' . $row->year;
                })
                ->addColumn('salary_breakdown', function($row) {
                    $taxable = $row->taxable_gross_salary ?? null;
                    $taxableText = $taxable ? number_format($taxable, 2) : 'N/A';
                    return '<div class="text-end">'
                        . '<div class="fw-bold text-primary">' . number_format($row->gross_salary, 2) . '</div>'
                        . '<small class="text-muted">Taxable: ' . $taxableText . '</small>'
                        . '</div>';
                })
                ->addColumn('attendance', function($row) {
                    $present = $row->present_days ?? null;
                    $absent = $row->absent_days ?? null;
                    
                    if ($present === null && $absent === null) {
                        return '<div class="text-center text-muted"><small>No data</small></div>';
                    }
                    
                    return '<div class="text-center">'
                        . '<span class="badge bg-success">' . ($present ?? 0) . ' Present</span> '
                        . '<span class="badge bg-danger">' . ($absent ?? 0) . ' Absent</span>'
                        . '</div>';
                })
                ->addColumn('deductions', function($row) {
                    $totalDed = $row->total_deductions ?? null;
                    $tax = $row->income_tax ?? null;
                    
                    if ($totalDed === null) {
                        return '<div class="text-end text-muted"><small>N/A</small></div>';
                    }
                    
                    $taxText = $tax !== null ? number_format($tax, 2) : 'N/A';
                    return '<div class="text-end">'
                        . '<div class="text-danger">-' . number_format($totalDed, 2) . '</div>'
                        . '<small class="text-muted">Tax: ' . $taxText . '</small>'
                        . '</div>';
                })
                ->addColumn('net_salary', function($row) {
                    return '<div class="text-end fw-bold text-success">' . number_format($row->net_salary, 2) . '</div>';
                })
                ->addColumn('status', function($row) {
                    $badge = $row->status === 'paid' ? 'success' : ($row->status === 'processed' ? 'info' : 'secondary');
                    return '<span class="badge bg-' . $badge . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('processed_by', function($row) {
                    return $row->processedBy ? $row->processedBy->name : '-';
                })
                ->addColumn('action', function($row) {
                    return '<a href="#" class="btn btn-sm btn-outline-primary view-payroll" data-id="'.$row->id.'" title="View Details"><i class="ri-eye-line"></i></a> '
                        .'<a href="#" class="btn btn-sm btn-outline-danger delete-btn" data-id="'.$row->id.'" title="Delete"><i class="ri-delete-bin-line"></i></a>';
                })
                ->rawColumns(['employee', 'salary_breakdown', 'attendance', 'deductions', 'net_salary', 'status', 'action'])
                ->make(true);
        }
        return view('payrolls.index');
    }

    public function create()
    {
        // Default to current month/year
        $currentMonth = now()->format('m');
        $currentYear = now()->format('Y');
        
        // Get employees with salary structures for payroll creation
        // Initially show all employees, will be filtered by AJAX based on year/month
        $employees = Employee::with(['user', 'currentSalaryStructure', 'taxPreferences', 'deductionPreferences.deductionType'])
            ->whereHas('currentSalaryStructure')
            ->get();
        
        $providentFund = ProvidentFundDefinition::where('status', 1)->orderByDesc('id')->first();
        $taxSlabs = IncomeTaxSlab::where('status', 1)->orderBy('min_salary')->get();
        $deductionTypes = DeductionType::where('status', 1)->get();
        if (!$deductionTypes->contains('name', 'EOBI')) {
            $deductionTypes->push((object)[
                'id' => 0,
                'name' => 'EOBI',
                'description' => 'EOBI Deduction',
                'status' => 1
            ]);
        }
        
        return view('payrolls.create', [
            'employees' => $employees,
            'providentFund' => $providentFund,
            'taxSlabs' => $taxSlabs,
            'deductionTypes' => $deductionTypes,
            'currentMonth' => $currentMonth,
            'currentYear' => $currentYear,
        ]);
    }

    /**
     * Get available employees for payroll processing (excludes already processed)
     */
    public function getAvailableEmployees(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        
        if (!$month || !$year) {
            return response()->json([
                'success' => false,
                'message' => 'Month and year are required'
            ]);
        }
        
        // Get employees with salary structures
        $employees = Employee::with(['user', 'currentSalaryStructure'])
            ->whereHas('currentSalaryStructure')
            ->get();
        
        // Get employee IDs that already have payroll for this month/year
        $processedEmployeeIds = Payroll::where('month', $month)
            ->where('year', $year)
            ->pluck('employee_id')
            ->toArray();
        
        // Filter out already processed employees
        $availableEmployees = $employees->filter(function($employee) use ($processedEmployeeIds) {
            return !in_array($employee->id, $processedEmployeeIds);
        })->values(); // Reset array keys to ensure proper array structure
        
        return response()->json([
            'success' => true,
            'employees' => $availableEmployees->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->user->name ?? $employee->preferred_name,
                    'employee_id' => $employee->employee_id,
                    'probation_end_date' => $employee->probation_end_date,
                    'salary_structure' => $employee->currentSalaryStructure ? [
                        'basic_salary' => $employee->currentSalaryStructure->basic_salary,
                        'gross_salary' => $employee->currentSalaryStructure->gross_salary,
                        'house_rent_allowance' => $employee->currentSalaryStructure->house_rent_allowance,
                        'medical_allowance' => $employee->currentSalaryStructure->medical_allowance,
                        'transport_allowance' => $employee->currentSalaryStructure->transport_allowance,
                        'other_allowances' => $employee->currentSalaryStructure->other_allowances,
                    ] : null
                ];
            })->values()->toArray(), // Use values() to ensure proper array indexing
            'processed_count' => count($processedEmployeeIds),
            'available_count' => $availableEmployees->count()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);
        
        // Check for duplicate payroll
        $exists = Payroll::where('employee_id', $validated['employee_id'])
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['employee_id' => 'Payroll for this employee in the selected month and year already exists.'])->withInput();
        }
        
        // Get employee with salary structure and preferences
        $employee = Employee::with(['user', 'company', 'department', 'designation', 'currentSalaryStructure', 'taxPreferences', 'deductionPreferences.deductionType'])
            ->findOrFail($validated['employee_id']);
        
        // Check if employee has salary structure
        if (!$employee->currentSalaryStructure) {
            return back()->withErrors(['employee_id' => 'Employee does not have a salary structure. Please assign salary first.'])->withInput();
        }
        
        $company_name = $employee->company ? $employee->company->company_name : '';
        
        // Get salary from employee salary structure
        $salaryStructure = $employee->currentSalaryStructure;
        $basic_salary = $salaryStructure->basic_salary;
        $gross_salary = $salaryStructure->gross_salary; // This is Basic + Permanent allowances from salary structure
        
        // Separate permanent and temporary allowances
        $allowances = $request->input('allowances', []);
        $permanentAllowances = collect($allowances)->filter(function($a) {
            return isset($a['is_permanent']) && $a['is_permanent'] == '1';
        });
        $temporaryAllowances = collect($allowances)->filter(function($a) {
            return !isset($a['is_permanent']) || $a['is_permanent'] == '0';
        });
        
        // Calculate sums
        $permanentAllowanceSum = $permanentAllowances->sum(function($a) { return floatval($a['amount'] ?? 0); });
        $temporaryAllowanceSum = $temporaryAllowances->sum(function($a) { return floatval($a['amount'] ?? 0); });
        
        $month = $validated['month'];
        $year = $validated['year'];
        $period_start = Carbon::create($year, $month, 1)->toDateString();
        $period_end = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        
        // Check probation status - Permanent allowances only for regular employees
        $probationEnd = $employee->probation_end_date;
        $allowances_applicable = !$probationEnd || $period_end > $probationEnd;
        
        // If employee is on probation, permanent allowances are NOT applicable
        if (!$allowances_applicable) {
            $permanentAllowanceSum = 0;
            // Filter out permanent allowances from the allowances array (only keep temporary)
            $allowances = $temporaryAllowances->toArray();
            // Clear permanent allowances collection
            $permanentAllowances = collect([]);
        }
        
        // Taxable gross salary = Basic + Permanent allowances (only if not on probation)
        $taxable_gross_salary = $basic_salary + $permanentAllowanceSum;
        
        // Total gross salary = Taxable gross + Temporary allowances (non-taxable)
        $total_gross_salary = $taxable_gross_salary + $temporaryAllowanceSum;

        // Use attendance summary logic
        $attendanceSummary = $this->getAttendanceSummary($employee->id, $month, $year);
        $presents = $attendanceSummary['presents'];
        $absents = $attendanceSummary['absents'];
        $lateMinutes = $attendanceSummary['late_minutes'];
        $extraHours = $attendanceSummary['extra_hours'];
        $approvedLeaves = $attendanceSummary['approved_leaves'];
        $leaveBreakdown = $attendanceSummary['leave_breakdown'];

        // Count approved leaves with pay
        $approvedPaidLeaves = 0;
        foreach ($leaveBreakdown as $leave) {
            if (!empty($leave['with_pay']) && $leave['with_pay']) {
                $approvedPaidLeaves += $leave['days'];
            }
        }

        // If no present days and no approved paid leaves, prevent payroll calculation
        if ($presents == 0 && $approvedPaidLeaves == 0) {
            return back()->withErrors(['employee_id' => 'Cannot process payroll: Employee has no attendance or approved paid leave for this month.'])->withInput();
        }

        // For salary calculation, treat approved paid leaves as present days
        $salaryDays = $presents + $approvedPaidLeaves;

        // Get employee working days first
        $workingDays = EmployeeWorkingDay::where('employee_id', $employee->id)
            ->whereHas('workingShift', function($query) {
                $query->where('status', 1);
            })
            ->pluck('working_day_id')->toArray();

        // Calculate actual working days for the month
        $period = CarbonPeriod::create($period_start, $period_end);
        $totalWorkingDays = 0;
        foreach ($period as $date) {
            $dayName = $date->format('l');
            $workingDayId = array_search($dayName, [1=>'Sunday',2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday']);
            if (in_array($workingDayId, $workingDays)) {
                $totalWorkingDays++;
            }
        }

        // Calculate per-day salary based on 30 days standard (not working days)
        // Use total gross salary (including temporary allowances) for per-day calculation
        $fullGrossSalary = $total_gross_salary;
        $perDaySalary = $fullGrossSalary / 30; // Standard 30-day calculation
        
        // Calculate actual gross salary based on present days out of 30 days
        $gross_salary = $salaryDays * $perDaySalary;
        
        // Calculate taxable portion (FULL MONTHLY SALARY - Industry Standard)
        // Tax is calculated on full monthly salary, not proportional to attendance
        $taxable_gross_proportional = $taxable_gross_salary;

        // --- Late Minutes Deduction Logic ---
        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        $shifts = EmployeeWorkingDay::where('employee_id', $employee->id)
            ->with(['workingShift' => function($query) {
                $query->where('status', 1);
            }])
            ->get()
            ->filter(function($item) {
                return $item->workingShift && $item->workingShift->status == 1;
            })
            ->keyBy('working_day_id');
        $attendances = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();
        // Try multiple variations of casual leave type name
        $casualLeaveType = \App\Models\LeaveType::where('name', 'Casual')
            ->orWhere('name', 'casual')
            ->orWhere('name', 'CASUAL')
            ->orWhere('name', 'Casual Leave')
            ->orWhere('name', 'casual leave')
            ->first();
        $casualLeaveQuota = 0;
        $leaveQuota = null;
        $leaveDebugInfo = [];
        
        // Add debugging for all available leave types
        $allLeaveTypes = LeaveType::all();
        $leaveDebugInfo['all_leave_types'] = $allLeaveTypes->pluck('name')->toArray();
        
        // Add debugging for ALL employee leave quotas (like dashboard does)
        $allEmployeeLeaveQuotas = EmployeeLeaveQuota::with('leaveType')
            ->where('employee_id', $employee->id)
            ->get();
        $leaveDebugInfo['all_employee_leave_quotas'] = $allEmployeeLeaveQuotas->map(function($quota) {
            return [
                'leave_type_name' => $quota->leaveType ? $quota->leaveType->name : 'Unknown',
                'leave_type_id' => $quota->leave_type_id,
                'allowed' => $quota->no_of_allowed_leaves,
                'balance' => $quota->no_of_balanced_leaves
            ];
        })->toArray();
        
        if ($casualLeaveType) {
            $leaveQuota = \App\Models\EmployeeLeaveQuota::where('employee_id', $employee->id)
                ->where('leave_type_id', $casualLeaveType->id)
                ->first();
            if ($leaveQuota) {
                // Calculate available leaves: Allowed - Acquired (Used)
                $allowed = $leaveQuota->no_of_allowed_leaves ?? 0;
                $acquired = $leaveQuota->no_of_balanced_leaves ?? 0; // This is actually acquired/used leaves
                $casualLeaveQuota = $allowed - $acquired; // Available leaves
            }
            $leaveDebugInfo['casual_leave_type_id'] = $casualLeaveType->id;
            $leaveDebugInfo['casual_leave_type_name'] = $casualLeaveType->name;
            $leaveDebugInfo['leave_quota_found'] = $leaveQuota ? 'Yes' : 'No';
            $leaveDebugInfo['leave_quota_balance'] = $casualLeaveQuota;
            if ($leaveQuota) {
                $leaveDebugInfo['leave_quota_allowed'] = $allowed ?? 0;
                $leaveDebugInfo['leave_quota_acquired'] = $acquired ?? 0;
                $leaveDebugInfo['leave_quota_available'] = $casualLeaveQuota;
                $leaveDebugInfo['calculation'] = "Available = Allowed ({$allowed}) - Acquired ({$acquired}) = {$casualLeaveQuota}";
            }
        } else {
            $leaveDebugInfo['casual_leave_type_found'] = 'No';
        }
        $singleDayLateFullDay = 0;
        $monthlyLateAccum = 0;
        foreach ($attendances as $attendance) {
            $attendanceDate = Carbon::parse($attendance->created_at);
            $dayName = $attendanceDate->format('l');
            $workingDayId = array_search($dayName, [1=>'Sunday',2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday']);
            if (!in_array($workingDayId, $workingDays)) continue;
            $shift = $shifts[$workingDayId]->workingShift ?? null;
            if (!$shift) continue;
            if ($attendance->time_in) {
                $late = calculateTimeDifference($shift->start_time, $attendance->time_in);
                if ($late >= 61 && $late <= 120) {
                    $singleDayLateFullDay++;
                } elseif ($late > 0 && $late <= 60) {
                    $monthlyLateAccum += $late;
                } elseif ($late > 120) {
                    $singleDayLateFullDay++;
                    $monthlyLateAccum += ($late - 120);
                }
            }
        }
        // Monthly late deduction table
        $lateDeductionTable = [
            60 => 0,
            120 => 1,
            180 => 2,
            240 => 3,
            300 => 4,
            360 => 5,
            420 => 6,
            480 => 7,
            540 => 8,
            600 => 9,
            660 => 10,
            720 => 11,
            780 => 12
        ];
        $monthlyLateDays = 0;
        foreach ($lateDeductionTable as $max => $days) {
            if ($monthlyLateAccum <= $max) {
                $monthlyLateDays = $days;
                break;
            }
        }
        if ($monthlyLateAccum > 780) $monthlyLateDays = 12;
        $totalLateDays = $singleDayLateFullDay + $monthlyLateDays;
        
        // Add debugging information for late deduction calculation
        $leaveDebugInfo['single_day_late_full_day'] = $singleDayLateFullDay;
        $leaveDebugInfo['monthly_late_accum'] = $monthlyLateAccum;
        $leaveDebugInfo['monthly_late_days'] = $monthlyLateDays;
        $leaveDebugInfo['total_late_days'] = $totalLateDays;
        $leaveDebugInfo['per_day_salary'] = $perDaySalary;
        
        // Deduct from casual leave if available, else from salary
        $lateDeductFromLeave = min($totalLateDays, $casualLeaveQuota);
        $lateDeductFromSalary = $totalLateDays - $lateDeductFromLeave;
        
        $leaveDebugInfo['late_deduct_from_leave'] = $lateDeductFromLeave;
        $leaveDebugInfo['late_deduct_from_salary'] = $lateDeductFromSalary;
        
        // Update leave quota if deduction from leave
        if ($lateDeductFromLeave > 0 && $leaveQuota) {
            $leaveQuota->no_of_balanced_leaves = max(0, $leaveQuota->no_of_balanced_leaves - $lateDeductFromLeave);
            $leaveQuota->save();
        }
        $lateDeduction = $lateDeductFromSalary * $perDaySalary;
        
        $leaveDebugInfo['final_late_deduction'] = $lateDeduction;

        // Salary deduction for unexcused absents 
        // Note: $absents already excludes:
        // - Off days (weekends, holidays) - only working days are counted
        // - Approved paid leaves - already subtracted in attendance calculation
        // - Official leave days - already subtracted in attendance calculation
        $absentDeduction = $absents * $perDaySalary;
        if ($absentDeduction < 0) $absentDeduction = 0;

        // --- Additional Deductions ---
        $additionalDeductions = [];
        $inputDeductions = $request->input('deductions', []);
        foreach ($inputDeductions as $id => $deduction) {
            if (!empty($deduction['amount'])) {
                $deductionType = DeductionType::find($id);
                $deductionName = $deductionType ? $deductionType->name : 'Additional Deduction ' . $id;
                $additionalDeductions[$id] = [
                    'name' => $deductionName,
                    'amount' => floatval($deduction['amount'])
                ];
            }
        }

        // --- Deductions Array (only absent and late, not additional deductions) ---
        $deductions = [
            ['label' => 'Absent', 'amount' => $absentDeduction],
            ['label' => 'Late', 'amount' => $lateDeduction],
        ];

        // Tax calculation using the new calculateTax method
        // IMPORTANT: Tax is calculated on FULL MONTHLY SALARY (Basic + Permanent allowances)
        // NOT on temporary allowances (travel, bonus, etc.)
        // Industry Standard: Tax is calculated on full monthly salary regardless of attendance
        $providentFund = ProvidentFundDefinition::where('status', 1)->orderByDesc('id')->first();
        
        $taxResult = $this->calculateTax($employee, $taxable_gross_proportional);
        $income_tax = $taxResult['amount'];
        $tax_slab_info = $taxResult['info'] . ' (Tax on Basic + Permanent allowances only)';

        // Provident Fund logic
        $probationEnd = $employee->probation_end_date;
        $periodEnd = $period_end;
        $pf_applicable = $probationEnd && $periodEnd > $probationEnd && $providentFund;
        $pf_employee = $pf_employer = 0;
        if ($pf_applicable) {
            // Calculate PF based on actual salary earned (proportional to present days out of 30)
            $actualBasicSalary = ($salaryDays / 30) * $basic_salary;
            $pf_employee = $actualBasicSalary * ($providentFund->employee_contribution_percent / 100);
            $pf_employer = $actualBasicSalary * ($providentFund->employer_contribution_percent / 100);
        }

        // Calculate employee-specific deductions
        $employeeDeductions = [];
        $employeeDeductionsTotal = 0;
        foreach ($employee->deductionPreferences as $preference) {
            if ($preference->is_active) {
                $amount = $preference->calculateAmount($gross_salary);
                $employeeDeductions[] = [
                    'label' => $preference->deductionType->name,
                    'amount' => $amount
                ];
                $employeeDeductionsTotal += $amount;
            }
        }

        // Calculate total deductions - FIXED: Include employee deductions in main deductions array
        // to avoid double counting in the view
        $allDeductions = $deductions; // Start with absent and late deductions
        
        // Add employee-specific deductions to the main deductions array
        $employeeDeductionLabels = [];
        foreach ($employeeDeductions as $empDeduction) {
            $allDeductions[] = [
                'label' => $empDeduction['label'],
                'amount' => $empDeduction['amount']
            ];
            $employeeDeductionLabels[] = strtolower($empDeduction['label']);
        }
        
        // Add additional deductions to the main deductions array
        // Skip if already exists in employee deductions to avoid duplicates
        foreach ($additionalDeductions as $id => $deduction) {
            if (!empty($deduction['amount'])) {
                // Check if this deduction already exists in employee preferences
                if (!in_array(strtolower($deduction['name']), $employeeDeductionLabels)) {
                    $allDeductions[] = [
                        'label' => $deduction['name'],
                        'amount' => $deduction['amount']
                    ];
                }
            }
        }
        
        // Calculate total deductions from the combined array
        $totalDeductions = array_sum(array_column($allDeductions, 'amount')); // All deductions
        $totalDeductions += $pf_employee; // Provident Fund (Employee)
        $totalDeductions += $income_tax; // Income Tax
        
        // Net salary is gross salary minus all deductions
        $net_salary = $gross_salary - $totalDeductions;
        $net_salary = max(0, $net_salary); // Ensure net salary is never negative

        if (!$request->has('confirm')) {
            return view('payrolls.preview', [
                'employee' => $employee,
                'company_name' => $company_name,
                'period_start' => $period_start,
                'period_end' => $period_end,
                'basic_salary' => $basic_salary,
                'gross_salary' => $gross_salary,
                'taxable_gross_salary' => $taxable_gross_proportional,
                'temporary_allowances_total' => $temporaryAllowanceSum,
                'income_tax' => $income_tax,
                'tax_slab_info' => $tax_slab_info,
                'allowances' => $allowances,
                'permanent_allowances' => $permanentAllowances->values()->toArray(),
                'temporary_allowances' => $temporaryAllowances->values()->toArray(),
                'deductions' => $allDeductions, // FIXED: Use combined deductions array
                'employeeDeductions' => [], // Empty to avoid double counting
                'additionalDeductions' => [], // Empty to avoid double counting
                'provident_fund' => $pf_employee,
                'pf_employer' => $pf_employer,
                'pf_applicable' => $pf_applicable,
                'allowances_applicable' => $allowances_applicable,
                'probation_end_date' => $probationEnd,
                'net_salary' => $net_salary,
                'month' => $month,
                'year' => $year,
                'presents' => $presents,
                'absents' => $absents,
                'late_minutes' => $lateMinutes,
                'extra_hours' => $extraHours,
                'approved_leaves' => $approvedLeaves,
                'leave_breakdown' => $leaveBreakdown,
                'late_deduction' => $lateDeduction,
                'total_working_days' => $totalWorkingDays,
                'leave_debug_info' => $leaveDebugInfo, // Add debugging information
            ]);
        }

        // If confirmed, use the values from hidden fields instead of recalculating
        // Note: We don't override calculated values - we use the freshly calculated ones
        // $income_tax = $request->input('income_tax', $income_tax);
        // $lateDeduction = $request->input('late_deduction', $lateDeduction);
        
        // Recalculate net salary with the confirmed values - FIXED: Use combined deductions
        $totalDeductions = array_sum(array_column($allDeductions, 'amount')); // All deductions from combined array
        $totalDeductions += $pf_employee; // Provident Fund (Employee)
        $totalDeductions += $income_tax; // Income Tax
        
        $net_salary = $gross_salary - $totalDeductions;
        $net_salary = max(0, $net_salary); // Ensure net salary is never negative

        // If confirmed, save payroll and details
        $payroll = Payroll::create([
            'employee_id' => $employee->id,
            'month' => $month,
            'year' => $year,
            
            // Salary Breakdown
            'basic_salary' => $basic_salary,
            'permanent_allowances_total' => $permanentAllowanceSum,
            'temporary_allowances_total' => $temporaryAllowanceSum,
            'taxable_gross_salary' => $taxable_gross_proportional,
            'gross_salary' => $gross_salary, // Attendance-adjusted total
            
            // Deductions Breakdown
            'total_deductions' => $totalDeductions,
            'absent_deduction' => $absentDeduction,
            'late_deduction' => $lateDeduction,
            'provident_fund_employee' => $pf_employee,
            'provident_fund_employer' => $pf_employer,
            'income_tax' => $income_tax,
            'other_deductions_total' => array_sum(array_column($allDeductions, 'amount')) - ($absentDeduction + $lateDeduction),
            
            // Attendance Data
            'present_days' => $presents,
            'absent_days' => $absents,
            'late_minutes' => $lateMinutes,
            'extra_minutes' => $extraHours,
            'approved_leaves' => $approvedLeaves,
            'total_working_days' => $totalWorkingDays,
            
            // Tax Information
            'tax_slab_applied' => $tax_slab_info,
            'tax_exemption_applied' => $taxResult['exemption_applied'] ?? 0,
            
            'net_salary' => $net_salary,
            'status' => 'processed',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);
        
        // Save allowances
        foreach ($allowances as $a) {
            if (!empty($a['label']) && !empty($a['amount'])) {
                $isPermanent = isset($a['is_permanent']) && $a['is_permanent'] == '1';
                $payroll->details()->create([
                    'type' => 'allowance',
                    'label' => $a['label'],
                    'amount' => $a['amount'],
                    'is_permanent' => $isPermanent,
                    'is_taxable' => $isPermanent, // Permanent allowances are taxable, temporary are not
                    'category' => $isPermanent ? 'salary_component' : 'temporary_benefit',
                ]);
            }
        }
        
        // Save all deductions from combined array - FIXED: Avoid duplicate entries
        foreach ($allDeductions as $deduction) {
            // Determine category based on deduction label (case-insensitive)
            $labelLower = strtolower($deduction['label']);
            $category = 'other';
            
            if (in_array($labelLower, ['absent', 'late', 'absent days', 'late arrival'])) {
                $category = 'attendance';
            } elseif (in_array($labelLower, ['eobi', 'sessi', 'social security', 'essi'])) {
                $category = 'statutory';
            } elseif (stripos($labelLower, 'loan') !== false || stripos($labelLower, 'advance') !== false) {
                $category = 'loan';
            }
            
            $payroll->details()->create([
                'type' => 'deduction',
                'label' => $deduction['label'],
                'amount' => $deduction['amount'],
                'is_permanent' => false,
                'is_taxable' => false,
                'category' => $category,
            ]);
        }
        
        // Save Provident Fund (Statutory)
        if ($pf_employee > 0) {
            $payroll->details()->create([
                'type' => 'deduction',
                'label' => 'Provident Fund',
                'amount' => $pf_employee,
                'is_permanent' => false,
                'is_taxable' => false,
                'category' => 'statutory',
            ]);
        }
        
        // Save Income Tax (Statutory)
        if ($income_tax > 0) {
            $payroll->details()->create([
                'type' => 'deduction',
                'label' => 'Income Tax',
                'amount' => $income_tax,
                'is_permanent' => false,
                'is_taxable' => false,
                'category' => 'statutory',
            ]);
        }

        return redirect()->route('payrolls.index')->with('success', 'Payroll processed and saved successfully.');
    }

    public function show($id)
    {
        $payroll = Payroll::with(['employee.user', 'employee.department', 'employee.designation', 'details', 'processedBy'])->findOrFail($id);
        
        // Format month name
        $monthName = Carbon::createFromFormat('!m', $payroll->month)->format('F');
        
        // Get allowances directly from payroll_details - SIMPLIFIED
        $allowances = $payroll->details()->where('type', 'allowance')->get();
        $permanentAllowances = [];
        $temporaryAllowances = [];
        
        foreach ($allowances as $allowance) {
            $item = [
                'label' => $allowance->label,
                'amount' => number_format($allowance->amount, 2),
            ];
            
            if ($allowance->is_permanent == 1) {
                $permanentAllowances[] = $item;
            } else {
                $temporaryAllowances[] = $item;
            }
        }
        
        // Get all deductions directly from payroll_details - SIMPLIFIED
        $deductions = $payroll->details()->where('type', 'deduction')->get();
        $allDeductions = [];
        
        foreach ($deductions as $deduction) {
            $allDeductions[] = [
                'label' => $deduction->label,
                'amount' => number_format($deduction->amount, 2),
                'category' => $deduction->category ?? 'other',
            ];
        }
        
        $data = [
            'id' => $payroll->id,
            'employee_id' => $payroll->employee->employee_id,
            'employee_name' => $payroll->employee->full_name ?? $payroll->employee->preferred_name,
            'department' => $payroll->employee->department->department_name ?? '-',
            'designation' => $payroll->employee->designation->designation_name ?? '-',
            'period' => $monthName . ' - ' . $payroll->year,
            'month' => $payroll->month,
            'year' => $payroll->year,
            
            // Salary Breakdown
            'basic_salary' => number_format($payroll->basic_salary ?? 0, 2),
            'permanent_allowances_total' => number_format($payroll->permanent_allowances_total ?? 0, 2),
            'temporary_allowances_total' => number_format($payroll->temporary_allowances_total ?? 0, 2),
            'taxable_gross_salary' => number_format($payroll->taxable_gross_salary ?? 0, 2),
            'gross_salary' => number_format($payroll->gross_salary, 2),
            'net_salary' => number_format($payroll->net_salary, 2),
            
            // Deductions Summary
            'total_deductions' => number_format($payroll->total_deductions ?? 0, 2),
            
            // Attendance Data
            'present_days' => $payroll->present_days ?? 0,
            'absent_days' => $payroll->absent_days ?? 0,
            'late_minutes' => $payroll->late_minutes ?? 0,
            'extra_minutes' => $payroll->extra_minutes ?? 0,
            'approved_leaves' => $payroll->approved_leaves ?? 0,
            'total_working_days' => $payroll->total_working_days ?? 30,
            
            // Tax Info
            'tax_slab_applied' => $payroll->tax_slab_applied ?? '-',
            'tax_exemption_applied' => number_format($payroll->tax_exemption_applied ?? 0, 2),
            'provident_fund_employee' => number_format($payroll->provident_fund_employee ?? 0, 2),
            'provident_fund_employer' => number_format($payroll->provident_fund_employer ?? 0, 2),
            'income_tax' => number_format($payroll->income_tax ?? 0, 2),
            
            // Details - SIMPLIFIED: Just return the arrays directly
            'permanent_allowances' => $permanentAllowances,
            'temporary_allowances' => $temporaryAllowances,
            'all_deductions' => $allDeductions,
            
            // Status
            'status' => ucfirst($payroll->status),
            'processed_by' => $payroll->processedBy->name ?? '-',
            'processed_at' => $payroll->processed_at ? (is_string($payroll->processed_at) ? $payroll->processed_at : $payroll->processed_at->format('Y-m-d H:i:s')) : '-',
        ];
        
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function edit($id)
    {
        $payroll = Payroll::find($id);
        return response()->json(['success' => true, 'data' => $payroll]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date',
            'gross_salary' => 'required|numeric',
            'net_salary' => 'required|numeric',
        ]);
        $payroll = Payroll::find($id);
        $payroll->update($validated);
        return response()->json(['success' => true, 'data' => $payroll]);
    }

    public function destroy($id)
    {
        $payroll = Payroll::find($id);
        $payroll->delete();
        return response()->json(['success' => true, 'data' => $payroll]);
    }

    public function fetchPayrollSummary(Request $request)
    {
        $employeeId = $request->employee_id;
        $month = $request->month;
        $year = $request->year;
        if (!$employeeId || !$month || !$year) {
            return response()->json(['error' => 'Missing required parameters.'], 422);
        }
        $summary = $this->getAttendanceSummary($employeeId, $month, $year);
        return response()->json($summary);
    }

    private function getAttendanceSummary($employeeId, $month, $year)
    {
        $today = Carbon::today();
        
        // Handle future years - return empty data for future periods
        if ($year > $today->year || ($year == $today->year && $month > $today->month)) {
            return [
                'presents' => 0,
                'absents' => 0,
                'late_minutes' => 0,
                'extra_hours' => 0,
                'approved_leaves' => 0,
                'leave_breakdown' => [],
            ];
        }
        
        $period_start = Carbon::create($year, $month, 1)->startOfDay();
        $period_end = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();
        if ($year == $today->year && $month == $today->month) {
            $period_end = $today->endOfDay();
        }
        $workingDays = EmployeeWorkingDay::where('employee_id', $employeeId)
            ->whereHas('workingShift', function($query) {
                $query->where('status', 1);
            })
            ->pluck('working_day_id')->toArray();
        $shifts = EmployeeWorkingDay::where('employee_id', $employeeId)
            ->with(['workingShift' => function($query) {
                $query->where('status', 1);
            }])
            ->get()
            ->filter(function($item) {
                return $item->workingShift && $item->workingShift->status == 1;
            })
            ->keyBy('working_day_id');
        $attendances = EmployeeAttendance::where('employee_id', $employeeId)
            ->whereBetween('created_at', [$period_start, $period_end])
            ->get();
        $officialLeaves = EmployeeOfficialLeaveDay::where('employee_id', $employeeId)
            ->whereBetween('leave_date', [$period_start, $period_end])
            ->pluck('leave_date')->toArray();
        $leaves = LeaveApplication::with('leaveType')
            ->where('employee_id', $employeeId)
            ->where('status', 1)
            ->where(function($q) use ($period_start, $period_end) {
                $q->whereBetween('from_date', [$period_start, $period_end])
                  ->orWhereBetween('to_date', [$period_start, $period_end]);
            })
            ->get();
        $leaveDates = [];
        $leaveBreakdown = [];
        foreach ($leaves as $leave) {
            $from = $leave->from_date;
            $to = $leave->to_date ?? $leave->from_date;
            $periodLeave = CarbonPeriod::create($from, $to);
            $days = iterator_count($periodLeave);
            $leaveBreakdown[] = [
                'type' => $leave->leaveType ? $leave->leaveType->name : '',
                'with_pay' => $leave->with_pay == 1,
                'days' => $days,
            ];
            foreach ($periodLeave as $date) {
                $leaveDates[] = $date->toDateString();
            }
        }
        $presents = 0;
        $lateMinutes = 0;
        $extraHours = 0;
        foreach ($attendances as $attendance) {
            $attendanceDate = Carbon::parse($attendance->created_at);
            $dayName = $attendanceDate->format('l');
            $workingDayId = array_search($dayName, [1=>'Sunday',2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday']);
            if (!in_array($workingDayId, $workingDays)) continue;
            $shift = $shifts[$workingDayId]->workingShift ?? null;
            if (!$shift) continue;
            $presents++;
            if ($attendance->time_in) {
                $late = calculateTimeDifference($shift->start_time, $attendance->time_in);
                if ($late > 0) $lateMinutes += $late;
            }
            if ($attendance->time_out) {
                $extra = calculateTimeDifference($shift->end_time, $attendance->time_out);
                if ($extra > 0) $extraHours += $extra;
            }
        }
        $period = CarbonPeriod::create($period_start, $period_end);
        $totalWorkingDays = 0;
        foreach ($period as $date) {
            $dayName = $date->format('l');
            $workingDayId = array_search($dayName, [1=>'Sunday',2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday']);
            if (in_array($workingDayId, $workingDays)) {
                $totalWorkingDays++;
            }
        }
        $approvedLeaves = 0;
        foreach ($leaveDates as $leaveDate) {
            $leaveCarbon = Carbon::parse($leaveDate);
            if ($leaveCarbon->between($period_start, $period_end)) {
                $approvedLeaves++;
            }
        }
        $officialLeaveCount = 0;
        foreach ($officialLeaves as $officialLeaveDate) {
            $officialLeaveCarbon = Carbon::parse($officialLeaveDate);
            if ($officialLeaveCarbon->between($period_start, $period_end)) {
                $officialLeaveCount++;
            }
        }
        // Calculate absent days (only for working days, excluding weekends and holidays)
        $absents = $totalWorkingDays - $presents - $approvedLeaves - $officialLeaveCount;
        if ($absents < 0) $absents = 0;
        
        // Calculate absent days breakdown
        $absentDates = [];
        $attendanceDates = $attendances->pluck('created_at')->map(function($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();
        
        $allLeaveDates = array_merge($leaveDates, $officialLeaves);
        
        foreach ($period as $date) {
            $dayName = $date->format('l');
            $workingDayId = array_search($dayName, [1=>'Sunday',2=>'Monday',3=>'Tuesday',4=>'Wednesday',5=>'Thursday',6=>'Friday',7=>'Saturday']);
            
            if (in_array($workingDayId, $workingDays)) {
                $dateStr = $date->format('Y-m-d');
                $isPresent = in_array($dateStr, $attendanceDates);
                $isOnLeave = in_array($dateStr, $allLeaveDates);
                
                if (!$isPresent && !$isOnLeave) {
                    $absentDates[] = [
                        'date' => $dateStr,
                        'day' => $dayName,
                        'reason' => 'Absent'
                    ];
                }
            }
        }
        
        return [
            'presents' => $presents,
            'absents' => $absents,
            'late_minutes' => $lateMinutes,
            'extra_hours' => $extraHours,
            'approved_leaves' => $approvedLeaves,
            'leave_breakdown' => $leaveBreakdown,
            'absent_breakdown' => $absentDates,
            'total_working_days' => $totalWorkingDays,
        ];
    }

    // ===== NEW SALARY MANAGEMENT METHODS =====

    /**
     * Display salary management interface
     */
    public function salaryIndex()
    {
        // Return HTML view for regular browser requests
        return view('payrolls.salary.index');
    }

    /**
     * Get salary data for DataTables
     */
    public function salaryData(Request $request)
    {
        $data = Employee::with(['currentSalaryStructure', 'department', 'designation'])
            ->whereHas('currentSalaryStructure');
        
        return datatables()->of($data)
            ->addColumn('employee_name', function($row) {
                return $row->full_name ?? $row->preferred_name;
            })
            ->addColumn('department', function($row) {
                return $row->department ? $row->department->department_name : '-';
            })
            ->addColumn('designation', function($row) {
                return $row->designation ? $row->designation->designation_name : '-';
            })
            ->addColumn('basic_salary', function($row) {
                return $row->currentSalaryStructure ? number_format($row->currentSalaryStructure->basic_salary, 2) : 'Not Set';
            })
            ->addColumn('gross_salary', function($row) {
                return $row->currentSalaryStructure ? number_format($row->currentSalaryStructure->gross_salary, 2) : 'Not Set';
            })
            ->addColumn('effective_from', function($row) {
                return $row->currentSalaryStructure ? $row->currentSalaryStructure->effective_from->format('Y-m-d') : '-';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('payrolls.salary.edit', $row->id) . '" class="btn btn-sm btn-outline-primary">
                        <i class="ri-edit-line"></i> Edit
                    </a>
                    <a href="' . route('payrolls.salary.history', $row->id) . '" class="btn btn-sm btn-outline-info">
                        <i class="ri-history-line"></i> History
                    </a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show form to assign salary to employee
     */
    public function salaryCreate()
    {
        $employees = Employee::with(['currentSalaryStructure', 'department', 'designation'])
            ->whereDoesntHave('currentSalaryStructure')
            ->get();
        
        $taxSlabs = IncomeTaxSlab::where('status', 1)->orderBy('min_salary')->get();
        $deductionTypes = DeductionType::where('status', 1)->get();
        
        return view('payrolls.salary.create', compact('employees', 'taxSlabs', 'deductionTypes'));
    }

    /**
     * Store employee salary structure
     */
    public function salaryStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'basic_salary' => 'required|numeric|min:0',
                'house_rent_allowance' => 'nullable|numeric|min:0',
                'medical_allowance' => 'nullable|numeric|min:0',
                'transport_allowance' => 'nullable|numeric|min:0',
                'other_allowances' => 'nullable|numeric|min:0',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after:effective_from',
                'notes' => 'nullable|string|max:1000',
                'tax_slab_id' => 'nullable|exists:income_tax_slabs,id',
                'tax_exemption_amount' => 'nullable|numeric|min:0',
                'apply_tax' => 'nullable|in:on',
                'deductions' => 'nullable|array',
                'deductions.*.deduction_type_id' => 'required_with:deductions|exists:deduction_types,id',
                'deductions.*.amount' => 'required_with:deductions|numeric|min:0',
                'deductions.*.type' => 'required_with:deductions|in:fixed,percentage',
                'deductions.*.is_active' => 'nullable|in:on'
            ]);

            // Calculate gross salary
            $grossSalary = $validated['basic_salary'] + 
                          ($validated['house_rent_allowance'] ?? 0) + 
                          ($validated['medical_allowance'] ?? 0) + 
                          ($validated['transport_allowance'] ?? 0) + 
                          ($validated['other_allowances'] ?? 0);

            // Deactivate any existing active salary structure
            EmployeeSalaryStructure::where('employee_id', $validated['employee_id'])
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // Create new salary structure
            $salaryStructure = EmployeeSalaryStructure::create([
                'employee_id' => $validated['employee_id'],
                'basic_salary' => $validated['basic_salary'],
                'house_rent_allowance' => $validated['house_rent_allowance'] ?? 0,
                'medical_allowance' => $validated['medical_allowance'] ?? 0,
                'transport_allowance' => $validated['transport_allowance'] ?? 0,
                'other_allowances' => $validated['other_allowances'] ?? 0,
                'gross_salary' => $grossSalary,
                'effective_from' => $validated['effective_from'],
                'effective_to' => $validated['effective_to'],
                'is_active' => true,
                'notes' => $validated['notes']
            ]);

            // Create or update tax preferences
            if (isset($validated['tax_slab_id']) || isset($validated['tax_exemption_amount']) || isset($validated['apply_tax'])) {
                EmployeeTaxPreference::updateOrCreate(
                    ['employee_id' => $validated['employee_id']],
                    [
                        'tax_slab_id' => $validated['tax_slab_id'] ?? null,
                        'tax_exemption_amount' => $validated['tax_exemption_amount'] ?? 0,
                        'apply_tax' => isset($validated['apply_tax']) ? true : false,
                        'notes' => $validated['notes']
                    ]
                );
            }

            // Create deduction preferences
            if (isset($validated['deductions']) && !empty($validated['deductions'])) {
                foreach ($validated['deductions'] as $deduction) {
                    // Only create deduction if deduction_type_id is provided
                    if (!empty($deduction['deduction_type_id'])) {
                        EmployeeDeductionPreference::create([
                            'employee_id' => $validated['employee_id'],
                            'deduction_type_id' => $deduction['deduction_type_id'],
                            'amount' => $deduction['amount'] ?? 0,
                            'type' => $deduction['type'] ?? 'fixed',
                            'is_active' => ($deduction['is_active'] === 'on') ? true : false
                        ]);
                    }
                }
            }

            return redirect()->route('payrolls.salary.index')
                ->with('success', 'Employee salary structure created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Salary creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'An error occurred while creating the salary structure. Please try again.')
                ->withInput();
        }
    }

    /**
     * Edit employee salary structure
     */
    public function salaryEdit($id)
    {
        $employee = Employee::with(['currentSalaryStructure', 'taxPreferences', 'deductionPreferences.deductionType'])
            ->findOrFail($id);
        
        $taxSlabs = IncomeTaxSlab::where('status', 1)->orderBy('min_salary')->get();
        $deductionTypes = DeductionType::where('status', 1)->get();
        
        return view('payrolls.salary.edit', compact('employee', 'taxSlabs', 'deductionTypes'));
    }

    /**
     * Update employee salary structure
     */
    public function salaryUpdate(Request $request, $id)
    {
        try {
            $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'house_rent_allowance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'transport_allowance' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'nullable|date|after:effective_from',
            'notes' => 'nullable|string|max:1000',
            'tax_slab_id' => 'nullable|exists:income_tax_slabs,id',
            'tax_exemption_amount' => 'nullable|numeric|min:0',
            'apply_tax' => 'nullable|in:on',
            'deductions' => 'array',
            'deductions.*.id' => 'nullable|exists:employee_deduction_preferences,id',
            'deductions.*.deduction_type_id' => 'required_with:deductions|exists:deduction_types,id',
            'deductions.*.amount' => 'required_with:deductions|numeric|min:0',
            'deductions.*.type' => 'required_with:deductions|in:fixed,percentage',
            'deductions.*.is_active' => 'nullable|in:on'
        ]);

        $employee = Employee::findOrFail($id);
        
        // Calculate gross salary
        $grossSalary = $validated['basic_salary'] + 
                      ($validated['house_rent_allowance'] ?? 0) + 
                      ($validated['medical_allowance'] ?? 0) + 
                      ($validated['transport_allowance'] ?? 0) + 
                      ($validated['other_allowances'] ?? 0);

        // Update current salary structure
        if ($employee->currentSalaryStructure) {
            $employee->currentSalaryStructure->update([
                'basic_salary' => $validated['basic_salary'],
                'house_rent_allowance' => $validated['house_rent_allowance'] ?? 0,
                'medical_allowance' => $validated['medical_allowance'] ?? 0,
                'transport_allowance' => $validated['transport_allowance'] ?? 0,
                'other_allowances' => $validated['other_allowances'] ?? 0,
                'gross_salary' => $grossSalary,
                'effective_from' => $validated['effective_from'],
                'effective_to' => $validated['effective_to'],
                'notes' => $validated['notes']
            ]);
        }

        // Update tax preferences
        EmployeeTaxPreference::updateOrCreate(
            ['employee_id' => $id],
            [
                'tax_slab_id' => $validated['tax_slab_id'] ?? null,
                'tax_exemption_amount' => $validated['tax_exemption_amount'] ?? 0,
                'apply_tax' => isset($validated['apply_tax']) ? true : false,
                'notes' => $validated['notes']
            ]
        );

        // Update deduction preferences
        if (isset($validated['deductions'])) {
            // Deactivate all existing deductions
            EmployeeDeductionPreference::where('employee_id', $id)->update(['is_active' => false]);
            
            foreach ($validated['deductions'] as $deduction) {
                if (isset($deduction['id'])) {
                    // Update existing deduction
                    EmployeeDeductionPreference::where('id', $deduction['id'])
                        ->update([
                            'deduction_type_id' => $deduction['deduction_type_id'],
                            'amount' => $deduction['amount'],
                            'type' => $deduction['type'],
                            'is_active' => ($deduction['is_active'] === 'on') ? true : false
                        ]);
                } else {
                    // Create new deduction
                    EmployeeDeductionPreference::create([
                        'employee_id' => $id,
                        'deduction_type_id' => $deduction['deduction_type_id'],
                        'amount' => $deduction['amount'],
                        'type' => $deduction['type'],
                        'is_active' => ($deduction['is_active'] === 'on') ? true : false
                    ]);
                }
            }
        }

            return redirect()->route('payrolls.salary.index')
                ->with('success', 'Employee salary structure updated successfully.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the salary structure. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show salary history for employee
     */
    public function salaryHistory($id)
    {
        $employee = Employee::with(['salaryHistory', 'department', 'designation'])->findOrFail($id);
        
        return view('payrolls.salary.history', compact('employee'));
    }

    /**
     * Get tax slabs for AJAX requests
     */
    public function getTaxSlabs()
    {
        // Cache tax slabs for 1 hour since they don't change frequently
        $taxSlabs = \Cache::remember('tax_slabs_active', 3600, function () {
            return IncomeTaxSlab::where('status', 1)
                ->orderBy('min_salary')
                ->get(['id', 'min_salary', 'max_salary', 'tax_percent', 'fixed_amount']);
        });
        
        return response()->json($taxSlabs);
    }

    /**
     * Get employee salary data for payroll creation
     */
    public function getEmployeeSalaryData(Request $request)
    {
        try {
            $employeeId = $request->input('employee_id');
            
            if (!$employeeId) {
                return response()->json(['success' => false, 'message' => 'Employee ID is required']);
            }

            $employee = Employee::with([
                'currentSalaryStructure',
                'taxPreferences.taxSlab',
                'deductionPreferences.deductionType'
            ])->find($employeeId);

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found']);
            }

            $data = [
                'basic_salary' => 0,
                'allowances' => [],
                'deductions' => [],
                'tax_preferences' => null,
                'gross_salary' => 0
            ];

            // Get current salary structure
            if ($employee->currentSalaryStructure) {
                $salary = $employee->currentSalaryStructure;
                $data['basic_salary'] = $salary->basic_salary;
                $data['gross_salary'] = $salary->gross_salary;
                
                // Add allowances from salary structure
                if ($salary->house_rent_allowance > 0) {
                    $data['allowances'][] = [
                        'label' => 'House Rent Allowance',
                        'amount' => $salary->house_rent_allowance
                    ];
                }
                if ($salary->medical_allowance > 0) {
                    $data['allowances'][] = [
                        'label' => 'Medical Allowance',
                        'amount' => $salary->medical_allowance
                    ];
                }
                if ($salary->transport_allowance > 0) {
                    $data['allowances'][] = [
                        'label' => 'Transport Allowance',
                        'amount' => $salary->transport_allowance
                    ];
                }
                if ($salary->other_allowances > 0) {
                    $data['allowances'][] = [
                        'label' => 'Other Allowances',
                        'amount' => $salary->other_allowances
                    ];
                }
            }

            // Get tax preferences
            if ($employee->taxPreferences) {
                $data['tax_preferences'] = [
                    'tax_slab_id' => $employee->taxPreferences->tax_slab_id,
                    'tax_exemption_amount' => $employee->taxPreferences->tax_exemption_amount,
                    'apply_tax' => $employee->taxPreferences->apply_tax
                ];
            }

            // Get deduction preferences
            if ($employee->deductionPreferences) {
                foreach ($employee->deductionPreferences as $preference) {
                    if ($preference->is_active) {
                        $data['deductions'][] = [
                            'deduction_type_id' => $preference->deduction_type_id,
                            'name' => $preference->deductionType->name,
                            'amount' => $preference->amount,
                            'type' => $preference->type
                        ];
                    }
                }
            }

            return response()->json(['success' => true, 'data' => $data]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error fetching employee data: ' . $e->getMessage()]);
        }
    }

    /**
     * Get employee salary details for AJAX
     */
    public function getEmployeeSalary($id)
    {
        $employee = Employee::with(['currentSalaryStructure', 'taxPreferences.taxSlab', 'deductionPreferences.deductionType'])
            ->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'employee' => $employee,
                'salary_structure' => $employee->currentSalaryStructure,
                'tax_preferences' => $employee->taxPreferences,
                'deduction_preferences' => $employee->deductionPreferences
            ]
        ]);
    }

    /**
     * Bulk salary assignment
     */
    public function bulkSalaryCreate()
    {
        $employees = Employee::with(['department', 'designation'])
            ->whereDoesntHave('currentSalaryStructure')
            ->get();
        
        $taxSlabs = IncomeTaxSlab::where('status', 1)->orderBy('min_salary')->get();
        $deductionTypes = DeductionType::where('status', 1)->get();
        $departments = \App\Models\Department::orderBy('department_name')->get();
        $designations = \App\Models\Designation::orderBy('designation_name')->get();
        
        return view('payrolls.salary.bulk-create', compact('employees', 'taxSlabs', 'deductionTypes', 'departments', 'designations'));
    }

    /**
     * Process bulk salary assignment
     */
    public function bulkSalaryStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'employee_ids' => 'required|array',
                'employee_ids.*' => 'exists:employees,id',
                'basic_salary' => 'required|numeric|min:0',
                'house_rent_allowance' => 'nullable|numeric|min:0',
                'medical_allowance' => 'nullable|numeric|min:0',
                'transport_allowance' => 'nullable|numeric|min:0',
                'other_allowances' => 'nullable|numeric|min:0',
                'effective_from' => 'required|date',
                'notes' => 'nullable|string|max:1000',
                'tax_slab_id' => 'nullable|exists:income_tax_slabs,id',
                'tax_exemption_amount' => 'nullable|numeric|min:0',
                'apply_tax' => 'nullable|in:on',
                'deductions' => 'nullable|array',
                'deductions.*.deduction_type_id' => 'required_with:deductions|exists:deduction_types,id',
                'deductions.*.amount' => 'required_with:deductions|numeric|min:0',
                'deductions.*.type' => 'required_with:deductions|in:fixed,percentage',
                'deductions.*.is_active' => 'nullable|in:on'
            ]);

            $processed = 0;
            $errors = [];

            foreach ($validated['employee_ids'] as $employeeId) {
                try {
                    // Calculate gross salary
                    $grossSalary = $validated['basic_salary'] + 
                                  ($validated['house_rent_allowance'] ?? 0) + 
                                  ($validated['medical_allowance'] ?? 0) + 
                                  ($validated['transport_allowance'] ?? 0) + 
                                  ($validated['other_allowances'] ?? 0);

                    // Deactivate any existing active salary structure
                    EmployeeSalaryStructure::where('employee_id', $employeeId)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);

                    // Create salary structure
                    EmployeeSalaryStructure::create([
                        'employee_id' => $employeeId,
                        'basic_salary' => $validated['basic_salary'],
                        'house_rent_allowance' => $validated['house_rent_allowance'] ?? 0,
                        'medical_allowance' => $validated['medical_allowance'] ?? 0,
                        'transport_allowance' => $validated['transport_allowance'] ?? 0,
                        'other_allowances' => $validated['other_allowances'] ?? 0,
                        'gross_salary' => $grossSalary,
                        'effective_from' => $validated['effective_from'],
                        'is_active' => true,
                        'notes' => $validated['notes']
                    ]);

                    // Create or update tax preferences
                    if (isset($validated['tax_slab_id']) || isset($validated['tax_exemption_amount']) || isset($validated['apply_tax'])) {
                        EmployeeTaxPreference::updateOrCreate(
                            ['employee_id' => $employeeId],
                            [
                                'tax_slab_id' => $validated['tax_slab_id'] ?? null,
                                'tax_exemption_amount' => $validated['tax_exemption_amount'] ?? 0,
                                'apply_tax' => isset($validated['apply_tax']) ? true : false,
                                'notes' => $validated['notes']
                            ]
                        );
                    }

                    // Create deduction preferences
                    if (isset($validated['deductions']) && !empty($validated['deductions'])) {
                        foreach ($validated['deductions'] as $deduction) {
                            // Only create deduction if deduction_type_id is provided
                            if (!empty($deduction['deduction_type_id'])) {
                                EmployeeDeductionPreference::create([
                                    'employee_id' => $employeeId,
                                    'deduction_type_id' => $deduction['deduction_type_id'],
                                    'amount' => $deduction['amount'] ?? 0,
                                    'type' => $deduction['type'] ?? 'fixed',
                                    'is_active' => ($deduction['is_active'] === 'on') ? true : false
                                ]);
                            }
                        }
                    }

                    $processed++;
                } catch (\Exception $e) {
                    $errors[] = "Employee ID {$employeeId}: " . $e->getMessage();
                }
            }

            // Return JSON response for AJAX handling
            return response()->json([
                'success' => true,
                'message' => "Processed {$processed} salary assignments successfully",
                'processed_count' => $processed,
                'total_count' => count($validated['employee_ids']),
                'errors' => $errors
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while processing salary assignments.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate tax for employee based on taxable gross salary
     */
    private function calculateTax($employee, $taxableGrossSalary)
    {
        $taxAmount = 0;
        $slabInfo = '';
        $exemptionApplied = 0;
        
        // Get employee tax preferences
        $taxPreferences = $employee->taxPreferences;
        
        if ($taxPreferences && $taxPreferences->apply_tax) {
            // Calculate taxable income (gross - exemption) - MONTHLY
            $taxExemption = $taxPreferences->tax_exemption_amount ?? 0;
            $exemptionApplied = $taxExemption;
            $monthlyTaxableIncome = max(0, $taxableGrossSalary - $taxExemption);
            $annualTaxableIncome = $monthlyTaxableIncome * 12; // Convert to annual for slab determination
            
            // Get tax slabs
            $taxSlabs = IncomeTaxSlab::where('status', 1)->orderBy('min_salary')->get();
            
            // Find applicable tax slab based on ANNUAL taxable income
            $applicableSlab = null;
            foreach ($taxSlabs as $slab) {
                if ($annualTaxableIncome >= $slab->min_salary && $annualTaxableIncome <= $slab->max_salary) {
                    $applicableSlab = $slab;
                    break;
                }
            }
            
            if ($applicableSlab) {
                // Calculate ANNUAL tax based on annual taxable income
                $annualTaxAmount = ($annualTaxableIncome * $applicableSlab->tax_percent / 100) + ($applicableSlab->fixed_amount ?? 0);
                // Convert back to monthly tax
                $taxAmount = $annualTaxAmount / 12;
                
                $slabInfo = "Slab: {$applicableSlab->min_salary} - {$applicableSlab->max_salary}, Rate: {$applicableSlab->tax_percent}%";
                if ($taxExemption > 0) {
                    $slabInfo .= " (Taxable: " . number_format($monthlyTaxableIncome, 2) . " after {$taxExemption} exemption)";
                }
            } else {
                $slabInfo = 'No applicable tax slab';
            }
        } else {
            $slabInfo = 'Tax not applicable';
        }
        
        return [
            'amount' => $taxAmount,
            'info' => $slabInfo,
            'exemption_applied' => $exemptionApplied
        ];
    }
}
