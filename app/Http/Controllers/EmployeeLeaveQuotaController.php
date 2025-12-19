<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeLeaveQuota;
use Illuminate\Support\Facades\Auth;

class EmployeeLeaveQuotaController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function getEmployeeLeaveQouta($id)
    {
        $leaveQuotas = EmployeeLeaveQuota::with('leaveType')->where('designation_id', $id)->where('employee_id', auth()->user()->employee->id)->get();
        return view('employees.leave_applications.leave_quotas', compact('leaveQuotas'));
    }
}
