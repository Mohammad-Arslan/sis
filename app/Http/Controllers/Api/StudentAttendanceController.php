<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceStatus;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\StudentAttendance;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentAttendanceController extends Controller
{
    public function getStudentAttendance(Request $request)
    {
        $branch_class_section_id = $request->branch_class_section_id;
        $academic_year_id = $request->academic_year_id;
        $student_id = $request->student_id;
        $month = $request->month;
        $data['date'] = $month;
        $data['PRESENT_DATES'] = [];
        $data['ABSENT_DATES'] = [];
        $data['LEAVE_DATES'] = [];
        $data['TARDY_DATES'] = [];
        $data['EXEMPTED_DATES'] = [];


        $data['PRESENT'] = StudentAttendance::where([
            'academic_year_id' => $academic_year_id,
            'branch_class_section_id' => $branch_class_section_id,
            'student_id' => $student_id,
        ])
            ->whereHas('attendance_status', function ($q) {
                $q->where('name', 'Present');
            })
            ->whereMonth('attendance_date', $month)
            ->get();

        if (count($data['PRESENT']) > 0) {
            foreach ($data['PRESENT'] as $row) {
                $date = Carbon::parse($row->attendance_date)->format('Y-m-d');
                $row['alter_date'] = $date;
                array_push($data['PRESENT_DATES'], $date);
            }
        }

        $data['ABSENT'] = StudentAttendance::where([
            'academic_year_id' => $academic_year_id,
            'branch_class_section_id' => $branch_class_section_id,
            'student_id' => $student_id,
        ])
            ->whereHas('attendance_status', function ($q) {
                $q->where('name', 'Absent');
            })
            ->whereMonth('attendance_date', $month)
            ->get();

        if (count($data['ABSENT']) > 0) {
            foreach ($data['ABSENT'] as $row) {
                $date = Carbon::parse($row->attendance_date)->format('Y-m-d');
                $row['alter_date'] = $date;
                array_push($data['ABSENT_DATES'], $date);
            }
        }

        $data['LEAVE'] = StudentAttendance::where([
            'academic_year_id' => $academic_year_id,
            'branch_class_section_id' => $branch_class_section_id,
            'student_id' => $student_id,
        ])
            ->whereHas('attendance_status', function ($q) {
                $q->where('name', 'Leave');
            })
            ->whereMonth('attendance_date', $month)
            ->get();

        if (count($data['LEAVE']) > 0) {
            foreach ($data['LEAVE'] as $row) {
                $date = Carbon::parse($row->attendance_date)->format('Y-m-d');
                $row['alter_date'] = $date;
                array_push($data['LEAVE_DATES'], $date);
            }
        }

        $data['TARDY'] = StudentAttendance::where([
            'academic_year_id' => $academic_year_id,
            'branch_class_section_id' => $branch_class_section_id,
            'student_id' => $student_id,
        ])
            ->whereHas('attendance_status', function ($q) {
                $q->where('name', 'Tardy');
            })
            ->whereMonth('attendance_date', $month)
            ->get();

        if (count($data['TARDY']) > 0) {
            foreach ($data['TARDY'] as $row) {
                $date = Carbon::parse($row->attendance_date)->format('Y-m-d');
                $row['alter_date'] = $date;
                array_push($data['TARDY_DATES'], $date);
            }
        }

        $data['EXEMPTED'] = StudentAttendance::where([
            'academic_year_id' => $academic_year_id,
            'branch_class_section_id' => $branch_class_section_id,
            'student_id' => $student_id,
        ])
            ->whereHas('attendance_status', function ($q) {
                $q->where('name', 'Exempted');
            })
            ->whereMonth('attendance_date', $month)
            ->get();

        if (count($data['EXEMPTED']) > 0) {
            foreach ($data['EXEMPTED'] as $row) {
                $date = Carbon::parse($row->attendance_date)->format('Y-m-d');
                $row['alter_date'] = $date;
                array_push($data['EXEMPTED_DATES'], $date);
            }
        }
        return response($data, 200);
    }
}
