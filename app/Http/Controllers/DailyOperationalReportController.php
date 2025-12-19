<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Branch;
use App\Models\ClassStudent;
use App\Models\StudentWithdrawal;
use App\Models\ElectricityMeterReading;
use App\Models\GeneratorInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailyOperationalReportController extends Controller
{
    /**
     * Display the daily operational report
     */
    public function index(Request $request)
    {
        // Get filter data
        $branches = Branch::all();

        // Set default date if not provided (current date)
        $selectedDate = $request->get('report_date', Carbon::today()->format('Y-m-d'));

        // Handle both single and multiple branch IDs
        $branchId = $request->get('branch_id');
        if (empty($branchId)) {
            // If no branches selected, get all branches
            $branchId = Branch::pluck('id')->toArray();
        } elseif (! is_array($branchId)) {
            $branchId = [$branchId];
        }

        // Calculate date range for the selected date
        $startDate = Carbon::parse($selectedDate)->startOfDay();
        $endDate = Carbon::parse($selectedDate)->endOfDay();

        // Get all report data
        $reportData = $this->getAllReportData($startDate, $endDate, $branchId);

        return view('reports.daily_operational_report', compact(
            'branches',
            'reportData',
            'selectedDate',
            'branchId',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get all report data for the daily operational report
     */
    private function getAllReportData($startDate, $endDate, $branchId)
    {
        return [
            'campusData' => $this->getCampusData($startDate, $endDate, $branchId),
            'grandTotal' => $this->getGrandTotal($startDate, $endDate, $branchId)
        ];
    }

    /**
     * Get campus-wise data for the report
     */
    private function getCampusData($startDate, $endDate, $branchId)
    {
        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $campuses = Branch::whereIn('id', $branchIds)->get();
        $campusData = [];

        foreach ($campuses as $campus) {
            $campusData[] = [
                'campus_name' => $campus->br_name,
                'employee_attendance' => $this->getEmployeeAttendanceData($startDate, $endDate, $campus->id),
                'student_attendance' => $this->getStudentAttendanceData($startDate, $endDate, $campus->id),
                'admissions_withdrawal' => $this->getAdmissionsWithdrawalData($startDate, $endDate, $campus->id),
                'meter_reading' => $this->getMeterReadingData($startDate, $endDate, $campus->id)
            ];
        }

        return $campusData;
    }

    /**
     * Get employee attendance data for a campus
     */
    private function getEmployeeAttendanceData($startDate, $endDate, $branchId)
    {
        // Get total employees in the branch
        $totalEmployees = Employee::where('branch_id', $branchId)->count();

        // Get present employees (those who marked attendance)
        $presentEmployees = EmployeeAttendance::whereHas('employees', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->where('attendance_type', 1)
        ->whereNotNull('time_in')
        ->distinct('employee_id')
        ->count('employee_id');

        // Get absent employees
        $absentEmployees = $totalEmployees - $presentEmployees;

        // Get late arrivals
        $lateArrivals = $this->getLateArrivalsCount($startDate, $endDate, $branchId);

        // Get early departures
        $earlyDepartures = $this->getEarlyDeparturesCount($startDate, $endDate, $branchId);

        return [
            'total' => $totalEmployees,
            'present' => $presentEmployees,
            'absent' => $absentEmployees,
            'late_arrival' => $lateArrivals,
            'early_pack_up' => $earlyDepartures
        ];
    }

    /**
     * Get student attendance data for a campus
     */
    private function getStudentAttendanceData($startDate, $endDate, $branchId)
    {
        // Get total students in the branch
        $totalStudents = Student::whereHas('class_students', function ($query) use ($branchId) {
            $query->whereHas('branch_class_sections', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        })->count();

        // Get present students
        $presentStudents = StudentAttendance::whereHas('student.class_students', function ($query) use ($branchId) {
            $query->whereHas('branch_class_sections', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->where('attendance_status_id', 1)
        ->distinct('student_id')
        ->count('student_id');

        // Get absent students
        $absentStudents = $totalStudents - $presentStudents;

        return [
            'total' => $totalStudents,
            'present' => $presentStudents,
            'absent' => $absentStudents
        ];
    }

    /**
     * Get admissions and withdrawal data for a campus
     */
    private function getAdmissionsWithdrawalData($startDate, $endDate, $branchId)
    {
        // Get new admissions
        $newAdmissions = Student::whereHas('class_students', function ($query) use ($branchId) {
            $query->whereHas('branch_class_sections', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

        // Get withdrawals
        $withdrawals = StudentWithdrawal::whereHas('student.class_students', function ($query) use ($branchId) {
            $query->whereHas('branch_class_sections', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->count();

        return [
            'new_admissions' => $newAdmissions,
            'withdrawals' => $withdrawals
        ];
    }

    /**
     * Get meter reading data for a campus
     */
    private function getMeterReadingData($startDate, $endDate, $branchId)
    {
        // Get electricity meter reading filtered by branch
        // Calculate consumption: closing_day_reading - opening_day_reading
        $meterReadings = ElectricityMeterReading::where('branch_id', $branchId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalConsumption = 0;
        foreach ($meterReadings as $reading) {
            $opening = floatval($reading->opening_day_reading);
            $closing = floatval($reading->closing_day_reading ?? $reading->opening_day_reading);
            $consumption = $closing - $opening;
            if ($consumption > 0) {
                $totalConsumption += $consumption;
            }
        }

        // Get generator info filtered by branch - sum all quantity_liter
        $generatorTotal = GeneratorInfo::where('branch_id', $branchId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity_liter');

        return [
            'el_unit_consumed' => $totalConsumption > 0 ? $totalConsumption : 0,
            'generator' => floatval($generatorTotal ?? 0)
        ];
    }

    /**
     * Get grand total data
     */
    private function getGrandTotal($startDate, $endDate, $branchId)
    {
        $campusData = $this->getCampusData($startDate, $endDate, $branchId);

        $grandTotal = [
            'employee_attendance' => [
                'total' => 0,
                'present' => 0,
                'absent' => 0,
                'late_arrival' => 0,
                'early_pack_up' => 0
            ],
            'student_attendance' => [
                'total' => 0,
                'present' => 0,
                'absent' => 0
            ],
            'admissions_withdrawal' => [
                'new_admissions' => 0,
                'withdrawals' => 0
            ],
            'meter_reading' => [
                'el_unit_consumed' => 0,
                'generator' => 0
            ]
        ];

        foreach ($campusData as $campus) {
            // Employee attendance totals
            $grandTotal['employee_attendance']['total'] += $campus['employee_attendance']['total'];
            $grandTotal['employee_attendance']['present'] += $campus['employee_attendance']['present'];
            $grandTotal['employee_attendance']['absent'] += $campus['employee_attendance']['absent'];
            $grandTotal['employee_attendance']['late_arrival'] += $campus['employee_attendance']['late_arrival'];
            $grandTotal['employee_attendance']['early_pack_up'] += $campus['employee_attendance']['early_pack_up'];

            // Student attendance totals
            $grandTotal['student_attendance']['total'] += $campus['student_attendance']['total'];
            $grandTotal['student_attendance']['present'] += $campus['student_attendance']['present'];
            $grandTotal['student_attendance']['absent'] += $campus['student_attendance']['absent'];

            // Admissions and withdrawal totals
            $grandTotal['admissions_withdrawal']['new_admissions'] += $campus['admissions_withdrawal']['new_admissions'];
            $grandTotal['admissions_withdrawal']['withdrawals'] += $campus['admissions_withdrawal']['withdrawals'];

            // Meter reading totals
            $grandTotal['meter_reading']['el_unit_consumed'] += $campus['meter_reading']['el_unit_consumed'];
            $grandTotal['meter_reading']['generator'] += $campus['meter_reading']['generator'];
        }

        return $grandTotal;
    }

    /**
     * Get late arrivals count
     */
    private function getLateArrivalsCount($startDate, $endDate, $branchId)
    {
        $lateCount = 0;

        $attendances = EmployeeAttendance::with(['employees', 'employees.employeeWorkingDays.workingShift'])
            ->whereHas('employees', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('attendance_type', 1)
            ->whereNotNull('time_in')
            ->get();

        foreach ($attendances as $attendance) {
            $employee = $attendance->employees;
            $dayName = $attendance->created_at->format('l');
            $workingDayId = array_search($dayName, ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']) + 1;

            $workingDay = $employee->employeeWorkingDays()
                ->where('working_day_id', $workingDayId)
                ->whereHas('workingShift', function ($query) {
                    $query->where('status', 1);
                })
                ->with('workingShift')
                ->first();

            if ($workingDay && $workingDay->workingShift) {
                $late = calculateTimeDifference($workingDay->workingShift->start_time, $attendance->time_in);
                if ($late > 0) {
                    $lateCount++;
                }
            }
        }

        return $lateCount;
    }

    /**
     * Get early departures count
     */
    private function getEarlyDeparturesCount($startDate, $endDate, $branchId)
    {
        $earlyCount = 0;

        $attendances = EmployeeAttendance::with(['employees', 'employees.employeeWorkingDays.workingShift'])
            ->whereHas('employees', function ($query) use ($branchId) {
                $query->where('branch_id', $branchId);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('attendance_type', 1)
            ->whereNotNull('time_out')
            ->get();

        foreach ($attendances as $attendance) {
            $employee = $attendance->employees;
            $dayName = $attendance->created_at->format('l');
            $workingDayId = array_search($dayName, ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']) + 1;

            $workingDay = $employee->employeeWorkingDays()
                ->where('working_day_id', $workingDayId)
                ->whereHas('workingShift', function ($query) {
                    $query->where('status', 1);
                })
                ->with('workingShift')
                ->first();

            if ($workingDay && $workingDay->workingShift) {
                $extra = calculateTimeDifference($workingDay->workingShift->end_time, $attendance->time_out);
                if ($extra < 0) {
                    $earlyCount++;
                }
            }
        }

        return $earlyCount;
    }
}
