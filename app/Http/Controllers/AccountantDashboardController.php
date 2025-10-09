<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\EmployeeAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EmployeeLeaveQuota;
use Illuminate\Support\Facades\Auth;

class AccountantDashboardController extends Controller
{
    public function index()
    {
        $employeeId = Auth::user()->employee->id;
        //$service_length = now()->diffInMonths(Carbon::parse(Auth::user()->employee->hiring_date));

        $leaveQuotas = EmployeeLeaveQuota::with('leaveType')->where('employee_id',auth()->user()->employee->id)->get();
        $today = date('Y-m-d');

        $today_start = $today.' 00:00:00';
        $today_end = $today.' 23:59:59';
        $mark_time_in = EmployeeAttendance::whereBetween('created_at',[$today_start,$today_end])->where('employee_id',auth()->user()->employee->id)->get(['id','time_in','time_out']);
        if(isset($mark_time_in[0]->time_in) && is_null($mark_time_in[0]->time_out))
        {
            $time_in = $mark_time_in[0]->time_in;
            $id = $mark_time_in[0]->id;
        }
        else{
            $time_in = NULL;
            $id = NULL;
        }
        return view('employees.accountant.dashboard', [
            'user_id' => $employeeId,
            'leaveQuotas' => $leaveQuotas,
            'time_in' => $time_in,
            'id' => $id
            //'service_length' => $service_length
        ]);
    }
}
