<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\Section;
use App\Models\AcademicYear;
use App\Models\Designation;
use App\Models\DesignationType;
use App\Models\AttendanceStatus;
use App\Models\ClassStudent;
use App\Models\BranchClassSection;
use App\Models\StudentWithdrawal;
use App\Models\LeaveApplication;
use App\Models\EmployeeWorkingDay;
use App\Models\WorkingDay;
use App\Models\WorkingShift;
use App\Models\EmployeeLeaveQuota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class AttendanceReportController extends Controller
{
    /**
     * Display the comprehensive attendance report with filters
     */
    public function index(Request $request)
    {
        // Get filter data
        $branches = Branch::all();
        $academicYears = AcademicYear::all();
        $designationTypes = DesignationType::all();

        // Set default month if not provided (current month)
        $selectedMonth = $request->get('report_month', Carbon::today()->format('Y-m'));

        // Handle both single and multiple branch IDs
        $branchId = $request->get('branch_id');
        if (empty($branchId)) {
            $branchId = [get_branch_id()];
        } elseif (! is_array($branchId)) {
            $branchId = [$branchId];
        }

        // Use first branch ID for academic year lookup
        $firstBranchId = is_array($branchId) ? ($branchId[0] ?? get_branch_id()) : $branchId;
        $academicYearId = $request->get('academic_year_id', get_current_acad_year_by_branch_id($firstBranchId)->academic_year_id ?? 1);

        // Calculate date range: from start of month to current date
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        // Get all report data
        $reportData = $this->getAllReportData($startDate, $endDate, $branchId, $academicYearId);

        return view('reports.attendance_report', compact(
            'branches',
            'academicYears',
            'designationTypes',
            'reportData',
            'selectedMonth',
            'branchId',
            'academicYearId',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get all report data with pagination support
     */
    private function getAllReportData($startDate, $endDate, $branchId, $academicYearId)
    {
        return [
            'staffSummary' => $this->getStaffAttendanceSummary($startDate, $endDate, $branchId),
            'lateArrivals' => $this->getLateArrivals($startDate, $endDate, $branchId),
            'earlyDepartures' => $this->getEarlyDepartures($startDate, $endDate, $branchId),
            'studentStrength' => $this->getStudentStrengthAndAttendance($startDate, $endDate, $branchId, $academicYearId),
            'absentStudents' => $this->getAbsentStudents($startDate, $endDate, $branchId, $academicYearId),
            'firstLearners' => $this->getFirstLearners($startDate, $endDate, $branchId, $academicYearId),
            'lastLearners' => $this->getLastLearners($startDate, $endDate, $branchId, $academicYearId),
            'newAdmissions' => $this->getNewAdmissions($startDate, $endDate, $branchId, $academicYearId),
            'studentWithdrawals' => $this->getStudentWithdrawals($startDate, $endDate, $branchId, $academicYearId),
        ];
    }

    /**
     * AJAX endpoint for individual report pagination
     */
    public function getReportData(Request $request)
    {
        $reportType = $request->get('report_type');
        $selectedMonth = $request->get('report_month', Carbon::today()->format('Y-m'));

        // Handle both single and multiple branch IDs
        $branchId = $request->get('branch_id');
        if (empty($branchId)) {
            $branchId = [get_branch_id()];
        } elseif (! is_array($branchId)) {
            $branchId = [$branchId];
        }

        // Use first branch ID for academic year lookup
        $firstBranchId = is_array($branchId) ? ($branchId[0] ?? get_branch_id()) : $branchId;
        $academicYearId = $request->get('academic_year_id', get_current_acad_year_by_branch_id($firstBranchId)->academic_year_id ?? 1);
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        // Calculate date range: from start of month to current date
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        switch ($reportType) {
            case 'late_arrivals':
                return $this->getLateArrivalsPaginated($startDate, $endDate, $branchId, $page, $perPage);
            case 'early_departures':
                return $this->getEarlyDeparturesPaginated($startDate, $endDate, $branchId, $page, $perPage);
            case 'absent_students':
                return $this->getAbsentStudentsPaginated($startDate, $endDate, $branchId, $academicYearId, $page, $perPage);
            case 'first_learners':
                return $this->getFirstLearnersPaginated($startDate, $endDate, $branchId, $academicYearId, $page, $perPage);
            case 'last_learners':
                return $this->getLastLearnersPaginated($startDate, $endDate, $branchId, $academicYearId, $page, $perPage);
            case 'new_admissions':
                return $this->getNewAdmissionsPaginated($startDate, $endDate, $branchId, $academicYearId, $page, $perPage);
            case 'student_withdrawals':
                return $this->getStudentWithdrawalsPaginated($startDate, $endDate, $branchId, $academicYearId, $page, $perPage);
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }
    }

    /**
     * Get staff attendance summary by designation
     */
    private function getStaffAttendanceSummary($startDate, $endDate, $branchId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $summary = [];

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        // Get all designations that have employees in these branches
        $designations = Designation::whereIn('id', function ($query) use ($branchIds) {
            $query->select('designation_id')
                  ->from('employees')
                  ->whereIn('branch_id', $branchIds)
                  ->whereNotNull('designation_id');
        })->get();

        foreach ($designations as $designation) {
            $totalEmployees = Employee::whereIn('branch_id', $branchIds)
                ->where('designation_id', $designation->id)
                ->count();

            if ($totalEmployees > 0) {
                $presentCount = 0;
                $lateCount = 0;

                // Get employees for this designation
                $employees = Employee::whereIn('branch_id', $branchIds)
                    ->where('designation_id', $designation->id)
                    ->get();

                foreach ($employees as $employee) {
                    // Get all attendances for this employee in the date range
                    $attendances = EmployeeAttendance::where('employee_id', $employee->id)
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->where('attendance_type', 1)
                        ->whereNotNull('time_in')
                        ->get();

                    foreach ($attendances as $attendance) {
                        $dayName = $attendance->created_at->format('l');
                        $workingDayId = array_search($dayName, ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']) + 1;

                        // Get employee working days and shifts
                        $workingDays = EmployeeWorkingDay::where('employee_id', $employee->id)
                        ->whereHas('workingShift', function ($query) {
                            $query->where('status', 1);
                        })
                        ->pluck('working_day_id')->toArray();

                        if (! in_array($workingDayId, $workingDays)) {
                            continue;
                        }

                        $shifts = EmployeeWorkingDay::where('employee_id', $employee->id)
                            ->where('working_day_id', $workingDayId)
                        ->with(['workingShift' => function ($query) {
                            $query->where('status', 1);
                        }])
                        ->first();

                        if (! $shifts || ! $shifts->workingShift) {
                            continue;
                        }

                        $presentCount++;

                        // Check if late
                        $late = calculateTimeDifference($shifts->workingShift->start_time, $attendance->time_in);
                        if ($late > 0) {
                            $lateCount++;
                        }
                    }
                }

                $leaveCount = $this->getLeaveCountByDesignation($startDate, $endDate, $branchId, $designation->id);

                $summary[] = [
                    'employee_type' => $designation->designation_name,
                    'total' => $totalEmployees,
                    'present' => $presentCount,
                    'leave' => $leaveCount,
                    'late' => $lateCount
                ];
            }
        }

        return $summary;
    }

    /**
     * Get late arrivals for the date range
     */
    private function getLateArrivals($startDate, $endDate, $branchId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $lateArrivals = [];

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        // Debug: Log the parameters
        \Log::info("Late Arrivals Debug", [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'branchId' => $branchIds
        ]);

        // Get all attendances for the date range with employee and working day info
        $attendances = EmployeeAttendance::with(['employees', 'employees.employeeWorkingDays.workingShift'])
            ->whereHas('employees', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('attendance_type', 1)
            ->whereNotNull('time_in')
            ->get();

        // Debug: Log attendance count
        \Log::info("Attendances found", ['count' => $attendances->count()]);

        foreach ($attendances as $attendance) {
            $employee = $attendance->employees;
            $dayName = $attendance->created_at->format('l');

            // Get working day ID for the day
            $workingDayId = array_search($dayName, ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']) + 1;

            // Debug: Log working day info
            \Log::info("Working Day Debug", [
                'employee_id' => $employee->id,
                'dayName' => $dayName,
                'workingDayId' => $workingDayId
            ]);

            // Find the working day for this employee
            $workingDay = $employee->employeeWorkingDays()
                ->where('working_day_id', $workingDayId)
                ->whereHas('workingShift', function ($query) {
                    $query->where('status', 1);
                })
                ->with('workingShift')
                ->first();

            if (! $workingDay || ! $workingDay->workingShift) {
                \Log::info("No working day found", [
                    'employee_id' => $employee->id,
                    'workingDayId' => $workingDayId
                ]);
                continue;
            }

            // Calculate late minutes
            $late = calculateTimeDifference($workingDay->workingShift->start_time, $attendance->time_in);

            \Log::info("Late calculation", [
                'start_time' => $workingDay->workingShift->start_time,
                'time_in' => $attendance->time_in,
                'late_minutes' => $late
            ]);

            if ($late > 0) {
                // Get current month late count
                $currentMonthLateCount = $this->getCurrentMonthLateCount($employee->id, $attendance->created_at);

                $lateArrivals[] = [
                    'date' => $attendance->created_at->format('d-m-Y'),
                    'id' => $employee->employee_id,
                    'name' => $employee->preferred_name,
                    'time_in' => $attendance->time_in,
                    'late_minutes' => sprintf('%02d:%02d', floor($late / 60), $late % 60),
                    'current_month_late' => $currentMonthLateCount
                ];
            }
        }

        \Log::info("Late arrivals result", ['count' => count($lateArrivals)]);

        return $lateArrivals;
    }

    /**
     * Get late arrivals with pagination
     */
    private function getLateArrivalsPaginated($startDate, $endDate, $branchId, $page = 1, $perPage = 10)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $offset = ($page - 1) * $perPage;

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        // Debug: Log the parameters
        \Log::info("Late Arrivals Paginated Debug", [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'branchId' => $branchIds,
            'page' => $page,
            'perPage' => $perPage
        ]);

        // Get all attendances for the date range with employee and working day info
        $attendances = EmployeeAttendance::with(['employees', 'employees.employeeWorkingDays.workingShift'])
            ->whereHas('employees', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('attendance_type', 1)
            ->whereNotNull('time_in')
            ->get();

        // Debug: Log attendance count
        \Log::info("Paginated Attendances found", ['count' => $attendances->count()]);

        $lateArrivals = [];
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

            if (! $workingDay || ! $workingDay->workingShift) {
                continue;
            }

            $late = calculateTimeDifference($workingDay->workingShift->start_time, $attendance->time_in);

            if ($late > 0) {
                $currentMonthLateCount = $this->getCurrentMonthLateCount($employee->id, $attendance->created_at);

                $lateArrivals[] = [
                    'date' => $attendance->created_at->format('d-m-Y'),
                    'id' => $employee->employee_id,
                    'name' => $employee->preferred_name,
                    'time_in' => $attendance->time_in,
                    'late_minutes' => sprintf('%02d:%02d', floor($late / 60), $late % 60),
                    'current_month_late' => $currentMonthLateCount
                ];
            }
        }

        $total = count($lateArrivals);
        $paginatedData = array_slice($lateArrivals, $offset, $perPage);

        \Log::info("Paginated Late arrivals result", [
            'total' => $total,
            'paginated_count' => count($paginatedData)
        ]);

        return response()->json([
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ]);
    }

    /**
     * Get early departures for the date range
     */
    private function getEarlyDepartures($startDate, $endDate, $branchId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $earlyDepartures = [];

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        // Get all attendances for the date range with employee and working day info
        $attendances = EmployeeAttendance::with(['employees', 'employees.employeeWorkingDays.workingShift'])
            ->whereHas('employees', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
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

            if (! $workingDay || ! $workingDay->workingShift) {
                continue;
            }

            // Calculate early departure (negative extra time)
            $extra = calculateTimeDifference($workingDay->workingShift->end_time, $attendance->time_out);

            if ($extra < 0) {
                $currentMonthEarlyCount = $this->getCurrentMonthEarlyCount($employee->id, $attendance->created_at);

                $earlyDepartures[] = [
                    'id' => $employee->employee_id,
                    'name' => $employee->preferred_name,
                    'designation' => $employee->designation->designation_name ?? 'N/A',
                    'time_out' => $attendance->time_out,
                    'early_minutes' => sprintf('%02d:%02d', floor(abs($extra) / 60), abs($extra) % 60),
                    'current_month_early' => $currentMonthEarlyCount
                ];
            }
        }

        return $earlyDepartures;
    }

    /**
     * Get early departures with pagination
     */
    private function getEarlyDeparturesPaginated($startDate, $endDate, $branchId, $page = 1, $perPage = 10)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $offset = ($page - 1) * $perPage;

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $attendances = EmployeeAttendance::with(['employees', 'employees.employeeWorkingDays.workingShift'])
            ->whereHas('employees', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('attendance_type', 1)
            ->whereNotNull('time_out')
            ->get();

        $earlyDepartures = [];
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

            if (! $workingDay || ! $workingDay->workingShift) {
                continue;
            }

            $extra = calculateTimeDifference($workingDay->workingShift->end_time, $attendance->time_out);

            if ($extra < 0) {
                $currentMonthEarlyCount = $this->getCurrentMonthEarlyCount($employee->id, $attendance->created_at);

                $earlyDepartures[] = [
                    'id' => $employee->employee_id,
                    'name' => $employee->preferred_name,
                    'designation' => $employee->designation->designation_name ?? 'N/A',
                    'time_out' => $attendance->time_out,
                    'early_minutes' => sprintf('%02d:%02d', floor(abs($extra) / 60), abs($extra) % 60),
                    'current_month_early' => $currentMonthEarlyCount
                ];
            }
        }

        $total = count($earlyDepartures);
        $paginatedData = array_slice($earlyDepartures, $offset, $perPage);

        return response()->json([
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ]);
    }

    /**
     * Get student strength and attendance by grade
     */
    private function getStudentStrengthAndAttendance($startDate, $endDate, $branchId, $academicYearId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $studentData = [];

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        // Get all grades in these branches through ClassStudent relationship
        $classStudents = ClassStudent::where('academic_year_id', $academicYearId)
            ->whereHas('branch_class_sections', function ($query) use ($branchIds) {
                $query->whereIn('branch_id', $branchIds);
            })
            ->with(['students', 'branch_class_sections.com_classes'])
            ->get();

        // Group by grade
        $grades = $classStudents->groupBy(function ($classStudent) {
            return $classStudent->branch_class_sections->com_classes->class_name ?? 'Unknown';
        });

        foreach ($grades as $gradeName => $gradeStudents) {
            $totalStrength = $gradeStudents->count();
            $present = 0;
            $absent = 0;

            foreach ($gradeStudents as $classStudent) {
                $student = $classStudent->students;

                if (! $student) {
                    continue;
                }

                // Check if student has attendance in the date range
                $attendance = StudentAttendance::where('student_id', $student->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                ->where('academic_year_id', $academicYearId)
                    ->first();

                if ($attendance && $attendance->attendance_status_id == 1) {
                    $present++;
                } else {
                    $absent++;
                }
            }

            $presentPercentage = $totalStrength > 0 ? round(($present / $totalStrength) * 100, 2) : 0;

            $studentData[] = [
                'grade' => $gradeName,
                'total_strength' => $totalStrength,
                'present' => $present,
                'absent' => $absent,
                'present_percentage' => $presentPercentage
            ];
        }

        return $studentData;
    }

    /**
     * Get absent students (3 or more days)
     */
    private function getAbsentStudents($startDate, $endDate, $branchId, $academicYearId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $threeDaysAgo = $endDate->copy()->subDays(3);

        $absentStudents = [];

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        // Get all students in these branches through ClassStudent relationship
        $students = Student::whereHas('class_students', function ($query) use ($branchIds, $academicYearId) {
            $query->where('academic_year_id', $academicYearId)
                  ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                      $q->whereIn('branch_id', $branchIds);
                  });
        })
        ->with(['class_students.branch_class_sections.com_classes'])
        ->get();

        foreach ($students as $student) {
            // Count absent days in last 3 days
            $absentDays = StudentAttendance::where('student_id', $student->id)
                    ->where('academic_year_id', $academicYearId)
                ->whereDate('created_at', '>=', $threeDaysAgo)
                ->whereDate('created_at', '<=', $endDate)
                ->where(function ($query) {
                    $query->where('attendance_status_id', 2) // 2 = Absent
                          ->orWhereNull('attendance_status_id');
                })
                ->count();

            if ($absentDays >= 3) {
                $firstClassStudent = $student->class_students->first();
                $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

                $absentStudents[] = [
                    'grade' => $grade,
                    'name' => $student->student_name,
                    'contact' => $student->guardian_contact ?? 'N/A',
                    'follow_up_feedback' => 'Pending'
                ];
            }
        }

        return $absentStudents;
    }

    /**
     * Get absent students with pagination
     */
    private function getAbsentStudentsPaginated($startDate, $endDate, $branchId, $academicYearId, $page = 1, $perPage = 10)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $threeDaysAgo = $endDate->copy()->subDays(3);
        $offset = ($page - 1) * $perPage;

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $students = Student::whereHas('class_students', function ($query) use ($branchIds, $academicYearId) {
                $query->where('academic_year_id', $academicYearId)
                  ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                      $q->whereIn('branch_id', $branchIds);
                  });
        })
        ->with(['class_students.branch_class_sections.com_classes'])
            ->get();

        $absentStudents = [];
        foreach ($students as $student) {
            $absentDays = StudentAttendance::where('student_id', $student->id)
                    ->where('academic_year_id', $academicYearId)
                ->whereDate('created_at', '>=', $threeDaysAgo)
                ->whereDate('created_at', '<=', $endDate)
                ->where(function ($query) {
                    $query->where('attendance_status_id', 2) // 2 = Absent
                          ->orWhereNull('attendance_status_id');
                })
                ->count();

            if ($absentDays >= 3) {
                $firstClassStudent = $student->class_students->first();
                $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

                $absentStudents[] = [
                    'grade' => $grade,
                    'name' => $student->student_name,
                    'contact' => $student->guardian_contact ?? 'N/A',
                    'follow_up_feedback' => 'Pending'
                ];
            }
        }

        $total = count($absentStudents);
        $paginatedData = array_slice($absentStudents, $offset, $perPage);

        return response()->json([
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ]);
    }

    /**
     * Get first 3 learners (arrival)
     */
    private function getFirstLearners($startDate, $endDate, $branchId, $academicYearId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $firstLearners = StudentAttendance::whereBetween('created_at', [$startDate, $endDate])
            ->where('academic_year_id', $academicYearId)
            ->where('attendance_status_id', 1)
            ->whereHas('student.class_students', function ($query) use ($branchIds, $academicYearId) {
                $query->where('academic_year_id', $academicYearId)
                      ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                          $q->whereIn('branch_id', $branchIds);
                      });
            })
            ->with(['student.class_students.branch_class_sections.com_classes'])
            ->orderBy('created_at', 'asc')
            ->limit(3)
            ->get();

        $result = [];
        foreach ($firstLearners as $attendance) {
            $student = $attendance->student;
            if (! $student) {
                continue;
            }

            $firstClassStudent = $student->class_students->first();
            $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

            $result[] = [
                'student_id' => $student->student_id ?? 'N/A',
                'student_name' => $student->student_name ?? 'N/A',
                'grade' => $grade,
                'time_in' => $attendance->created_at?->format('H:i:s') ?? 'N/A',
                'staff_on_duty' => 'Samar, Nazneen' // This should be dynamic based on your system
            ];
        }

        return $result;
    }

    /**
     * Get first learners with pagination
     */
    private function getFirstLearnersPaginated($startDate, $endDate, $branchId, $academicYearId, $page = 1, $perPage = 10)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $offset = ($page - 1) * $perPage;

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $firstLearners = StudentAttendance::whereBetween('created_at', [$startDate, $endDate])
            ->where('academic_year_id', $academicYearId)
            ->where('attendance_status_id', 1)
            ->whereHas('student.class_students', function ($query) use ($branchIds, $academicYearId) {
                $query->where('academic_year_id', $academicYearId)
                      ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                          $q->whereIn('branch_id', $branchIds);
                      });
            })
            ->with(['student.class_students.branch_class_sections.com_classes'])
            ->orderBy('created_at', 'asc')
            ->get();

        $result = [];
        foreach ($firstLearners as $attendance) {
            $student = $attendance->student;
            $firstClassStudent = $student->class_students->first();
            $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

            $result[] = [
                'student_id' => $student->student_id ?? 'N/A',
                'student_name' => $student->student_name ?? 'N/A',
                'grade' => $grade,
                'time_in' => $attendance->created_at->format('H:i:s'),
                'staff_on_duty' => 'Samar, Nazneen'
            ];
        }

        $total = count($result);
        $paginatedData = array_slice($result, $offset, $perPage);

        return response()->json([
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ]);
    }

    /**
     * Get last 3 learners (departure)
     */
    private function getLastLearners($startDate, $endDate, $branchId, $academicYearId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $lastLearners = StudentAttendance::whereBetween('created_at', [$startDate, $endDate])
            ->where('academic_year_id', $academicYearId)
            ->where('attendance_status_id', 1)
            ->whereHas('student.class_students', function ($query) use ($branchIds, $academicYearId) {
                $query->where('academic_year_id', $academicYearId)
                      ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                          $q->whereIn('branch_id', $branchIds);
                      });
            })
            ->with(['student.class_students.branch_class_sections.com_classes'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $result = [];
        foreach ($lastLearners as $attendance) {
            $student = $attendance->student;
            if (! $student) {
                continue;
            }

            $firstClassStudent = $student->class_students->first();
            $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

            $result[] = [
                'student_id' => $student->student_id ?? 'N/A',
                'student_name' => $student->student_name ?? 'N/A',
                'grade' => $grade,
                'time_out' => $attendance->created_at?->format('H:i:s') ?? 'N/A',
                'staff_on_duty' => 'Samar, Nazneen'
            ];
        }

        return $result;
    }

    /**
     * Get last learners with pagination
     */
    private function getLastLearnersPaginated($startDate, $endDate, $branchId, $academicYearId, $page = 1, $perPage = 10)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $offset = ($page - 1) * $perPage;

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $lastLearners = StudentAttendance::whereBetween('created_at', [$startDate, $endDate])
            ->where('academic_year_id', $academicYearId)
            ->where('attendance_status_id', 1)
            ->whereHas('student.class_students', function ($query) use ($branchIds, $academicYearId) {
                $query->where('academic_year_id', $academicYearId)
                      ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                          $q->whereIn('branch_id', $branchIds);
                      });
            })
            ->with(['student.class_students.branch_class_sections.com_classes'])
            ->orderBy('created_at', 'desc')
            ->get();

        $result = [];
        foreach ($lastLearners as $attendance) {
            $student = $attendance->student;
            if (! $student) {
                continue;
            }

            $firstClassStudent = $student->class_students->first();
            $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

            $result[] = [
                'student_id' => $student->student_id ?? 'N/A',
                'student_name' => $student->student_name ?? 'N/A',
                'grade' => $grade,
                'time_out' => $attendance->created_at?->format('H:i:s') ?? 'N/A',
                'staff_on_duty' => 'Samar, Nazneen'
            ];
        }

        $total = count($result);
        $paginatedData = array_slice($result, $offset, $perPage);

        return response()->json([
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ]);
    }

    /**
     * Get new admissions
     */
    private function getNewAdmissions($startDate, $endDate, $branchId, $academicYearId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $newAdmissions = Student::whereHas('class_students', function ($query) use ($branchIds, $academicYearId) {
            $query->where('academic_year_id', $academicYearId)
                  ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                      $q->whereIn('branch_id', $branchIds);
                  });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['class_students.branch_class_sections.com_classes'])
                ->get()
        ->groupBy(function ($student) {
            return $student->class_students->first()->branch_class_sections->com_classes->class_name ?? 'N/A';
        });

        $result = [];
        foreach ($newAdmissions as $grade => $students) {
            $result[] = [
                'grade' => $grade,
                'total_admissions' => $students->count(),
                'remarks' => 'New admissions this month'
            ];
        }

        return $result;
    }

    /**
     * Get new admissions with pagination
     */
    private function getNewAdmissionsPaginated($startDate, $endDate, $branchId, $academicYearId, $page = 1, $perPage = 10)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $offset = ($page - 1) * $perPage;

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $newAdmissions = Student::whereHas('class_students', function ($query) use ($branchIds, $academicYearId) {
            $query->where('academic_year_id', $academicYearId)
                  ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                      $q->whereIn('branch_id', $branchIds);
                  });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['class_students.branch_class_sections.com_classes'])
                ->get()
        ->groupBy(function ($student) {
            return $student->class_students->first()->branch_class_sections->com_classes->class_name ?? 'N/A';
        });

        $result = [];
        foreach ($newAdmissions as $grade => $students) {
            $result[] = [
                'grade' => $grade,
                'total_admissions' => $students->count(),
                'remarks' => 'New admissions this month'
            ];
        }

        $total = count($result);
        $paginatedData = array_slice($result, $offset, $perPage);

        return response()->json([
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ]);
    }

    /**
     * Get student withdrawals
     */
    private function getStudentWithdrawals($startDate, $endDate, $branchId, $academicYearId)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $withdrawals = StudentWithdrawal::whereHas('student.class_students', function ($query) use ($branchIds, $academicYearId) {
            $query->where('academic_year_id', $academicYearId)
                  ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                      $q->whereIn('branch_id', $branchIds);
                  });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['student.class_students.branch_class_sections.com_classes', 'reason'])
            ->get();

        $result = [];
        foreach ($withdrawals as $withdrawal) {
            $student = $withdrawal->student;
            if (! $student) {
                continue;
            }

            $firstClassStudent = $student->class_students->first();
            $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

            $result[] = [
                'student_name' => $this->getStudentFullName($student),
                'grade' => $grade,
                'reason' => $this->getWithdrawalReason($withdrawal->reason)
            ];
        }

        return $result;
    }

    /**
     * Get student withdrawals with pagination
     */
    private function getStudentWithdrawalsPaginated($startDate, $endDate, $branchId, $academicYearId, $page = 1, $perPage = 10)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $offset = ($page - 1) * $perPage;

        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        $withdrawals = StudentWithdrawal::whereHas('student.class_students', function ($query) use ($branchIds, $academicYearId) {
            $query->where('academic_year_id', $academicYearId)
                  ->whereHas('branch_class_sections', function ($q) use ($branchIds) {
                      $q->whereIn('branch_id', $branchIds);
                  });
        })
        ->whereBetween('created_at', [$startDate, $endDate])
        ->with(['student.class_students.branch_class_sections.com_classes', 'reason'])
            ->get();

        $result = [];
        foreach ($withdrawals as $withdrawal) {
            $student = $withdrawal->student;
            if (! $student) {
                continue;
            }

            $firstClassStudent = $student->class_students->first();
            $grade = $firstClassStudent?->branch_class_sections?->com_classes?->class_name ?? 'N/A';

            $result[] = [
                'student_name' => $this->getStudentFullName($student),
                'grade' => $grade,
                'reason' => $this->getWithdrawalReason($withdrawal->reason)
            ];
        }

        $total = count($result);
        $paginatedData = array_slice($result, $offset, $perPage);

        return response()->json([
            'data' => $paginatedData,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage),
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $total)
        ]);
    }

    /**
     * Get current month late count for employee
     */
    private function getCurrentMonthLateCount($employeeId, $date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $lateCount = 0;

        $attendances = EmployeeAttendance::where('employee_id', $employeeId)
            ->where('attendance_type', 1)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $endOfMonth)
            ->whereNotNull('time_in')
            ->get();

        foreach ($attendances as $attendance) {
            $dayName = $attendance->created_at->format('l');
            $workingDayId = array_search($dayName, ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']) + 1;

            $workingDay = EmployeeWorkingDay::where('employee_id', $employeeId)
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
     * Get current month early count for employee
     */
    private function getCurrentMonthEarlyCount($employeeId, $date)
    {
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();

        $earlyCount = 0;

        $attendances = EmployeeAttendance::where('employee_id', $employeeId)
            ->where('attendance_type', 1)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $endOfMonth)
            ->whereNotNull('time_out')
            ->get();

        foreach ($attendances as $attendance) {
            $dayName = $attendance->created_at->format('l');
            $workingDayId = array_search($dayName, ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']) + 1;

            $workingDay = EmployeeWorkingDay::where('employee_id', $employeeId)
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

    /**
     * Get leave count by designation
     */
    private function getLeaveCountByDesignation($startDate, $endDate, $branchId, $designationId)
    {
        // Ensure branchId is an array
        $branchIds = is_array($branchId) ? $branchId : [$branchId];

        return LeaveApplication::whereHas('employee', function ($query) use ($branchIds, $designationId) {
            $query->whereIn('branch_id', $branchIds)
                  ->where('designation_id', $designationId);
        })
        ->where('status', 1)
        ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('from_date', [$startDate, $endDate])
                  ->orWhereBetween('to_date', [$startDate, $endDate])
                  ->orWhere(function ($q) use ($startDate, $endDate) {
                      $q->where('from_date', '<=', $startDate)
                        ->where('to_date', '>=', $endDate);
                  });
        })
        ->count();
    }

    /**
     * Generate PDF report
     */
    public function generatePDF(Request $request)
    {
        $selectedMonth = $request->get('report_month', Carbon::today()->format('Y-m'));

        // Handle both single and multiple branch IDs
        $branchId = $request->get('branch_id');
        if (empty($branchId)) {
            $branchId = [get_branch_id()];
        } elseif (! is_array($branchId)) {
            $branchId = [$branchId];
        }

        // Use first branch ID for academic year lookup
        $firstBranchId = is_array($branchId) ? ($branchId[0] ?? get_branch_id()) : $branchId;
        $academicYearId = $request->get('academic_year_id', get_current_acad_year_by_branch_id($firstBranchId)->academic_year_id ?? 1);

        // Calculate date range: from start of month to current date
        $startDate = Carbon::parse($selectedMonth . '-01')->startOfDay();
        $endDate = Carbon::today()->endOfDay();

        $reportData = $this->getAllReportData($startDate, $endDate, $branchId, $academicYearId);

        $pdf = PDF::loadView('reports.attendance_report_pdf', compact('reportData', 'selectedMonth', 'startDate', 'endDate'));

        return $pdf->download('attendance_report_' . $selectedMonth . '.pdf');
    }

    /**
     * Helper to get full student name with null safety
     */
    private function getStudentFullName($student)
    {
        if (! $student) {
            return 'N/A';
        }

        $nameParts = [];
        if (! empty($student->first_name)) {
            $nameParts[] = $student->first_name;
        }
        if (! empty($student->middle_name)) {
            $nameParts[] = $student->middle_name;
        }
        if (! empty($student->last_name)) {
            $nameParts[] = $student->last_name;
        }

        $fullName = implode(' ', $nameParts);
        return empty($fullName) ? 'N/A' : $fullName;
    }

    /**
     * Helper to safely get withdrawal reason
     */
    private function getWithdrawalReason($reason)
    {
        if (is_null($reason)) {
            return 'Not specified';
        }

        // If it's a WithdrawalReason model object
        if (is_object($reason) && method_exists($reason, 'getAttributes')) {
            // It's an Eloquent model, get the withdrawal_reason field
            if (isset($reason->withdrawal_reason)) {
                return $reason->withdrawal_reason;
            }
            if (isset($reason->description)) {
                return $reason->description;
            }
            return 'Not specified';
        }

        // If it's an object, try to get a meaningful string representation
        if (is_object($reason)) {
            if (method_exists($reason, '__toString')) {
                return (string) $reason;
            }
            if (isset($reason->name)) {
                return $reason->name;
            }
            if (isset($reason->description)) {
                return $reason->description;
            }
            if (isset($reason->reason)) {
                return $reason->reason;
            }
            if (isset($reason->withdrawal_reason)) {
                return $reason->withdrawal_reason;
            }
            // Fallback to JSON representation
            return json_encode($reason);
        }

        if (is_array($reason)) {
            // If it's an array, try to get meaningful fields
            if (isset($reason['withdrawal_reason'])) {
                return $reason['withdrawal_reason'];
            }
            if (isset($reason['description'])) {
                return $reason['description'];
            }
            if (isset($reason['name'])) {
                return $reason['name'];
            }
            return json_encode($reason);
        }

        // If it's a string, check if it's JSON
        if (is_string($reason)) {
            // Try to decode JSON string
            $decoded = json_decode($reason, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Successfully decoded JSON, try to get meaningful field
                if (isset($decoded['withdrawal_reason'])) {
                    return $decoded['withdrawal_reason'];
                }
                if (isset($decoded['description'])) {
                    return $decoded['description'];
                }
                if (isset($decoded['name'])) {
                    return $decoded['name'];
                }
                if (isset($decoded['reason'])) {
                    return $decoded['reason'];
                }
            }
            // If not JSON or no meaningful field found, return as string
            return $reason;
        }

        return (string) $reason;
    }
}
