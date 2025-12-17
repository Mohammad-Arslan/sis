<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\ClassTeacher;
use App\Models\EmployeeAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EmployeeLeaveQuota;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $employeeId = Auth::user()->employee->id;
        //$service_length = now()->diffInMonths(Carbon::parse(Auth::user()->employee->hiring_date));

        $teacherClasses = ClassTeacher::where('employee_id', $employeeId)->with([
            'academic_year',
            'branch_class_section.branches',
            'branch_class_section.sections',
            'branch_class_section.com_classes',
            'employee',
            'teacher_type',
            'subject'
        ])->get();

        $attendanceClasses = ClassTeacher::with('teacher_type')
            ->where(function($q){
                $q->whereHas('teacher_type',function($q1){
                    $q1->where('abbreviation','class');
                });
                $q->orWhereHas('branch_class_section.com_classes.attendance_type',function ($q2){
                    $q2->where('abbreviation','subject');
                });
            })->where('employee_id', $employeeId)->get();

        $leaveQuotas = EmployeeLeaveQuota::with('leaveType')->where('employee_id',auth()->user()->employee->id)->get();
        $today = date('Y-m-d');

        $today_start = $today.' 00:00:00';
        $today_end = $today.' 23:59:59';
        $mark_time_in = EmployeeAttendance::whereBetween('created_at',[$today_start,$today_end])->where('employee_id',auth()->user()->employee->id)->get(['id','time_in','time_out','created_at']);
        if(isset($mark_time_in[0]->time_in) && is_null($mark_time_in[0]->time_out))
        {
            $time_in = $mark_time_in[0]->time_in;
            $time_out = NULL;
            $id = $mark_time_in[0]->id;
        }
        else if(isset($mark_time_in[0]->time_in) && isset($mark_time_in[0]->time_out))
        {
            $time_in = $mark_time_in[0]->time_in;
            $time_out = $mark_time_in[0]->time_out;
            $id = $mark_time_in[0]->id;
        }
        else{
            $time_in = NULL;
            $time_out = NULL;
            $id = NULL;
        }
        return view('employees.teachers.dashboard', [
            'user_id' => $employeeId,
            'teacher_classes' => $teacherClasses,
            'attendanceClasses' => $attendanceClasses,
            'leaveQuotas' => $leaveQuotas,
            'time_in' => $time_in,
            'time_out' => $time_out,
            'id' => $id
            //'service_length' => $service_length
        ]);
    }
}
