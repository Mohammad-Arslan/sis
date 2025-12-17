<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\AcademicYear;

class BiometricAttendanceController extends Controller
{
    public function store(Request $request)
    {
        $data = json_decode($request->getContent());
        if (!$data || !is_array($data)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or empty JSON provided'], 400);
        }

        $processedCount = 0;
        $studentCount = 0;
        $employeeCount = 0;
        $unmatchedCount = 0;

        foreach ($data as $log) {
            $validator = Validator::make((array) $log, [
                'LTime' => 'required|date',
                'Pin' => 'nullable|string',
                'CardNo' => 'nullable|string',
                'DoorId' => 'nullable|string',
                'EventType' => 'nullable|string',
                'IsInState' => 'nullable|string',
                'IpAdress' => 'nullable|string',
                'Port' => 'nullable|string',
                'MachineName' => 'nullable|string',
                'CreatedDate' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                Log::warning("Invalid biometric log entry", [
                    'errors' => $validator->errors(),
                    'log' => $log
                ]);
                continue; // Skip invalid log
            }

            // Prepare log data with correct field names
            $logData = [
                'LTime' => $log->LTime,
                'Pin' => $log->Pin ?? null,
                'CardNo' => $log->CardNo ?? null,
                'DooID' => $log->DoorId ?? null, // Note: DooID as per your specification
                'EventType' => $log->EventType ?? null,
                'InOutState' => $log->IsInState ?? null, // Note: InOutState as per your specification
                'IPAdress' => $log->IpAdress ?? null, // Note: IPAdress as per your specification
                'Port' => $log->Port ?? null,
                'MachineName' => $log->MachineName ?? null,
                'CreatedDate' => now()->format('Y-m-d h:i:sa'),
                'addeddate' => now()->format('Y-m-d'),
            ];

            // Try to match as student first
            $student = Student::where('pin_code', $log->Pin)
                ->orWhere('card_no', $log->CardNo)
                ->first();

            if ($student) {
                // Process as student
                $studentLogData = $logData;
                $studentLogData['student_id'] = $student->id;
                $studentLogData['employee_id'] = null;
                
                // Save to PullLog table
                DB::table('PullLog')->insert($studentLogData);

                // Mark student attendance
                StudentAttendance::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'attendance_date' => now()->toDateString(),
                        'branch_class_section_id' => $student->active_class->branch_class_sections->id,
                        'subject_id' => null,
                    ],
                    [
                        'academic_year_id' => $student->active_class->academic_year_id,
                        'employee_id' => null,
                        'attendance_status_id' => 1,
                        'online_attendance' => false,
                    ]
                );
                
                $studentCount++;
                $processedCount++;
                continue;
            }

            // Try to match as employee
            $employee = Employee::where('pin_code', $log->Pin)
                ->orWhere('card_no', $log->CardNo)
                ->first();

            if ($employee) {
                // Process as employee
                $employeeLogData = $logData;
                $employeeLogData['employee_id'] = $employee->id;
                $employeeLogData['student_id'] = null;
                
                // Save to employee_pull_logs table
                DB::table('employee_pull_logs')->insert($employeeLogData);

                // Mark employee attendance
                $currentDate = now()->toDateString();
                $currentTime = now()->format('H:i:s');
                
                // Check if employee already has attendance for today
                $existingAttendance = EmployeeAttendance::where('employee_id', $employee->id)
                    ->whereDate('created_at', $currentDate)
                    ->first();

                if ($existingAttendance) {
                    // Update time_out if employee is checking out
                    if ($log->EventType === 'OUT' || $log->IsInState === '0') {
                        $existingAttendance->update([
                            'time_out' => $currentTime,
                            'attendance_type' => 0, // 0 for check-out
                        ]);
                    }
                } else {
                    // Create new attendance record for check-in
                    EmployeeAttendance::create([
                        'employee_id' => $employee->id,
                        'time_in' => $currentTime,
                        'time_out' => null,
                        'attendance_type' => 1, // 1 for check-in
                        'academic_year_id' => AcademicYear::where('active', 1)->first()?->id ?? 1,
                        'status' => 'present',
                    ]);
                }
                
                $employeeCount++;
                $processedCount++;
                continue;
            }

            // No match found - log as unmatched
            Log::warning('Biometric log does not match any student or employee', [
                'pin' => $log->Pin,
                'card_no' => $log->CardNo,
                'time' => $log->LTime
            ]);
            
            // Since both employees and students use the same digit pattern for PIN/card,
            // we'll log unmatched entries to both tables for manual review
            DB::table('unmatched_attendance_logs')->insert([
                'pin_code' => $log->Pin,
                'card_no' => $log->CardNo,
                'log_time' => $log->LTime,
                'raw_data' => json_encode($log),
                'created_at' => now(),
            ]);
            
            DB::table('unmatched_employee_attendance_logs')->insert([
                'pin_code' => $log->Pin,
                'card_no' => $log->CardNo,
                'log_time' => $log->LTime,
                'raw_data' => json_encode($log),
                'created_at' => now(),
            ]);
            
            $unmatchedCount++;
        }

        return response()->json([
            'status' => 'success', 
            'message' => 'Logs processed successfully.',
            'summary' => [
                'total_processed' => $processedCount,
                'students' => $studentCount,
                'employees' => $employeeCount,
                'unmatched' => $unmatchedCount
            ]
        ]);
    }

}
