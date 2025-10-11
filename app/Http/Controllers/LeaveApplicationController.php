<?php

namespace App\Http\Controllers;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ApplicationType;
use App\Models\LeaveApplication;
use App\Models\EmployeeLeaveQuota;
use App\Models\DesignationLeaveQuota;
use App\Models\EmployeeAttendance;

class LeaveApplicationController extends Controller
{
    public function index($id)
    {
        if ($id != null && Employee::where('id', $id)->exists()) {
            //$employee = Employee::find($id);
            //dd($employee->reporting_to);
            return view('employees.leave_applications.index', ['employee_id' => $id]);
        }
        return redirect()->to('/');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $inputs = [
            'application_type_id' => $request->application_type_id,
            'employee_id' => $request->employee_id,
            'application_date' => isset($request->application_date) ? strToTimeDateFormat($request->application_date) : null,
            'from_date' => isset($request->from_date) ? strToTimeDateFormat($request->from_date) : null,
            'to_date' => isset($request->to_date) ? strToTimeDateFormat($request->to_date) : null,
            'num_of_days' => $request->num_of_days,
            'category' => $request->category,
            'with_pay' => $request->with_pay,
            'leave_type_id' => $request->leave_type_id,
            'reason' => $request->reason,
            'forward_to' => $request->forward_to,
            'adjustment_date' => isset($request->adjustment_date) ? strToTimeDateFormat($request->adjustment_date) : null,
            'off_day_work_date' => isset($request->off_day_work_date) ? strToTimeDateFormat($request->off_day_work_date) : null,
            'arrival_time' => $request->arrival_time,
            'departure_time' => $request->departure_time,
            'attendance_not_marked_date' => isset($request->attendance_not_marked_date) ? strToTimeDateFormat($request->attendance_not_marked_date) : null
        ];
        //dd($inputs);
        //EmployeeAttendance
        $application_type = ApplicationType::where('id', $inputs['application_type_id'])->get('name');
        //dd($application_type[0]->name);
        //$leave_type = LeaveType::where('id',$inputs['leave_type_id'])->get('name');

        //insert leave application request.
        LeaveApplication::create($inputs);

        if (isset($inputs['leave_type_id'])) {
            $leave_acquired = NULL;

            $EmployeeLeaveQouta = EmployeeLeaveQuota::where('employee_id', $inputs['employee_id'])->where('leave_type_id', $inputs['leave_type_id'])->first();
            //($EmployeeLeaveQouta->no_of_balanced_leaves);
            if ($inputs['category'] == "Full Day") {
                $leave_acquired['no_of_balanced_leaves'] = $EmployeeLeaveQouta->no_of_balanced_leaves + $inputs['num_of_days'];
            }
            if ($inputs['category'] == "Half Day") {
                $leave_acquired['no_of_balanced_leaves'] = $EmployeeLeaveQouta->no_of_balanced_leaves + 0.5;
            }
            if ($inputs['category'] == "Short Leave") {
                $leave_acquired['no_of_balanced_leaves'] = $EmployeeLeaveQouta->no_of_balanced_leaves + 0.25;
            }

            //update acquired employee leaves.
            $EmployeeLeaveQouta->update($leave_acquired);
        }
        return redirect()->route('leave.application', $request->employee_id)->with('success', 'Application Submitted Successfully');
    }

    /**
     * @description function to get requested leave applications forms
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getFormsView(Request $request)
    {
        if ($request->ajax()) {
            $applicationType = ApplicationType::find($request->application_type_id);
            if (!empty($applicationType)) {
                $applicationTypeID = $applicationType->id;
                if ($applicationType->name == 'Leave') {
                    return view('employees.leave_applications.forms.leave', compact('applicationTypeID'));
                } else if ($applicationType->name == 'Out Station') {
                    return view('employees.leave_applications.forms.out_station', compact('applicationTypeID'));
                } else if ($applicationType->name == 'Toil') {
                    return view('employees.leave_applications.forms.toil', compact('applicationTypeID'));
                } else if ($applicationType->name == 'Late Arrival') {
                    return view('employees.leave_applications.forms.late_arrival', compact('applicationTypeID'));
                } else if ($applicationType->name == 'Early Leaving') {
                    return view('employees.leave_applications.forms.early_leaving', compact('applicationTypeID'));
                } else if ($applicationType->name == 'Attendance not Marked') {
                    return view('employees.leave_applications.forms.attendance_not_marked', compact('applicationTypeID'));
                }
            }
        }
    }

    /**
     * @description function to show employee leave applications listing view
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function leaveAppliedListShow($id)
    {
        if ($id != null) {
            $employee = Employee::find($id);
            if (!empty($employee)) {
                return view('employees.leave_applications.applied_applications_listing', compact('employee'));
            }
        }
        return redirect()->to('/');
    }

    /**
     * @description function to get leave application details
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function details(Request $request)
    {
        if ($request->ajax()) {
            $leaveApplication = LeaveApplication::where('id', $request->application_id)->first();
            return view('employees.leave_applications.applied_application_modal', compact('leaveApplication'));
        }
    }

    /**
     * @description function to update the status of leave application
     * @param Request $request
     */
    public function statusUpdate(Request $request)
    {
        if ($request->ajax()) {
            LeaveApplication::where('id', $request->application_id)->update(['status' => $request->status]);
        }
    }

    public function statusApprove(Request $request)
    {
        if ($request->ajax()) {
            //$leave = LeaveApplication::find($request->application_id);
            $application_detail = LeaveApplication::with(['employee', 'leaveType', 'leaveApplicationType'])->where('id', $request->application_id)->get()->toArray();
            $attendance = [];
            //dd($application_detail);
            if ($application_detail[0]['leave_application_type']['name'] == 'Leave') {
                //this condition is working fine.
                $from_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 00:00:00')->format('Y-m-d H:i:s');
                $to_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 23:59:59')->format('Y-m-d H:i:s');
                $begin = new DateTime($application_detail[0]['from_date']);
                $end   = new DateTime($application_detail[0]['to_date']);
                if ($application_detail[0]['num_of_days'] > 1) {
                    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                        $attendance_date = $i;
                        $attendance['employee_id'] = $application_detail[0]['employee']['id'];
                        $attendance['attendance_type'] = 1;
                        $attendance['created_at'] = $attendance_date->format("Y-m-d");
                        EmployeeAttendance::create($attendance);
                        $attendance = [];
                    }
                } else {
                    $attendance_date = $from_date;
                    $attendance['employee_id'] = $application_detail[0]['employee']['id'];
                    $attendance['attendance_type'] = 1;
                    $attendance['created_at'] = $attendance_date;
                    EmployeeAttendance::create($attendance);
                }
            } else if ($application_detail[0]['leave_application_type']['name'] == 'Out Station') {
                //this condition is working fine.
                $from_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 00:00:00')->format('Y-m-d H:i:s');
                $to_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 23:59:59')->format('Y-m-d H:i:s');
                $begin = new DateTime($application_detail[0]['from_date']);
                $end   = new DateTime($application_detail[0]['to_date']);
                if ($application_detail[0]['num_of_days'] > 1) {
                    for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                        $attendance_date = $i;
                        $attendance['employee_id'] = $application_detail[0]['employee']['id'];
                        $attendance['attendance_type'] = 1;
                        $attendance['created_at'] = $attendance_date->format("Y-m-d");
                        EmployeeAttendance::create($attendance);
                        $attendance = [];
                    }
                } else {
                    $attendance_date = $from_date;
                    $attendance['employee_id'] = $application_detail[0]['employee']['id'];
                    $attendance['attendance_type'] = 1;
                    $attendance['created_at'] = $attendance_date;
                    EmployeeAttendance::create($attendance);
                }
            } else if ($application_detail[0]['leave_application_type']['name'] == 'Toil') {
                //this condition is working fine.
                $adjustment_date_start = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['adjustment_date'] . ' 00:00:00')->format('Y-m-d H:i:s');
                $adjustment_date_end = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['adjustment_date'] . ' 23:59:59')->format('Y-m-d H:i:s');
                $adjustment_attendance = EmployeeAttendance::whereBetween('created_at', [$adjustment_date_start, $adjustment_date_end])->where('employee_id', $application_detail[0]['employee']['id'])->first();
                $off_day_work_date_start = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['off_day_work_date'] . ' 00:00:00')->format('Y-m-d H:i:s');
                $off_day_work_date_end = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['off_day_work_date'] . ' 23:59:59')->format('Y-m-d H:i:s');
                $off_day_attendance = EmployeeAttendance::whereBetween('created_at', [$off_day_work_date_start, $off_day_work_date_end])->where('employee_id', $application_detail[0]['employee']['id'])->first();

                if ($application_detail[0]['category'] == 'Half Day Toil') {
                    $time_out = Carbon::createFromFormat('H:i:s', $adjustment_attendance->time_out)->addHours(4);
                    $adjustment_attendance->time_out = date('H:i:s', strtotime($time_out));
                    $off_day_attendance->time_out = NULL;
                } else {
                    $time_out = Carbon::createFromFormat('H:i:s', $adjustment_attendance->time_out)->addHours(8);
                    $adjustment_attendance->time_out = date('H:i:s', strtotime($time_out));
                    $off_day_attendance->time_out = '00:00:00';
                    $off_day_attendance->time_in = '23:59:59';
                    $off_day_attendance->attendance_type = '0';
                }
                $adjustment_attendance->update();
                $off_day_attendance->update();
            } else if ($application_detail[0]['leave_application_type']['name'] == 'Late Arrival') {
                //this condition is working fine.
                $from_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 00:00:00')->format('Y-m-d H:i:s');
                $to_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 23:59:59')->format('Y-m-d H:i:s');
                $attendance = EmployeeAttendance::whereBetween('created_at', [$from_date, $to_date])->where('employee_id', $application_detail[0]['employee']['id'])->update(['time_in' => $application_detail[0]['arrival_time']]);
                // $begin = new DateTime( $application_detail[0]['from_date'] );
                // $end   = new DateTime( $application_detail[0]['to_date'] );
                // $interval = DateInterval::createFromDateString('1 day');
                // $period = new DatePeriod($begin, $interval, $end);
                //for($i = $begin; $i <= $end; $i->modify('+1 day')){...}
                //$attendance['time_in'] = $application_detail[0]['arrival_time'];
                //$attendance->update();
            } else if ($application_detail[0]['leave_application_type']['name'] == 'Early Leaving') {
                //this condition is working fine.
                $from_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 00:00:00')->format('Y-m-d H:i:s');
                $to_date = Carbon::createFromFormat('d-m-Y H:i:s', $application_detail[0]['from_date'] . ' 23:59:59')->format('Y-m-d H:i:s');
                $attendance = EmployeeAttendance::whereBetween('created_at', [$from_date, $to_date])->where('employee_id', $application_detail[0]['employee']['id'])->first();
                $begin = new DateTime($application_detail[0]['from_date']);
                $end   = new DateTime($application_detail[0]['to_date']);
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                //for($i = $begin; $i <= $end; $i->modify('+1 day')){...}
                $attendance['time_out'] = $application_detail[0]['departure_time'];
                $attendance->update();
            } else if ($application_detail[0]['leave_application_type']['name'] == 'Attendance not Marked') { // this condition is working fine.
                $attendance_date = new DateTime($application_detail[0]['attendance_not_marked_date']);
                $attendance['employee_id'] = $application_detail[0]['employee']['id'];
                $attendance['time_in'] = $application_detail[0]['arrival_time'];
                $attendance['time_out'] = $application_detail[0]['departure_time'];
                $attendance['attendance_type'] = 1;
                $attendance['created_at'] = $attendance_date->format("Y-m-d");
                EmployeeAttendance::create($attendance);
                $attendance[] = NULL;
            }
            //approve leave.
            LeaveApplication::where('id', $request->application_id)->update(['status' => $request->status]);
        }
    }

    /**
     * @description function to check if leave application already exists
     * and in pending state or not
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function alreadyApplied(Request $request)
    {
        if ($request->ajax()) {
            //dd($request->all());
            $applicationExists = 0;
            $employeeID = $request->employee_id;
            $applicationTypeID = $request->application_type_id;
            $applicationTypeName = ApplicationType::where('id', $request->application_type_id)->get('name')->toArray();

            $leaveQuotas = EmployeeLeaveQuota::where('employee_id', $request->employee_id)->where('leave_type_id', $request->leave_type_id)->first();
            if ($applicationTypeName[0]['name'] != "Leave" && !isset($leaveQuotas)) {

                //dd($applicationTypeName[0]['name']);
                if (isset($request->num_of_days) && $request->num_of_days >= 1 && $applicationTypeName[0]['name'] != 'Attendance not Marked') {
                    if (isset($request->from_date)) {
                        $from_date = date('Y-m-d', strtotime($request->from_date));
                    }
                    if (isset($request->to_date)) {
                        $to_date = date('Y-m-d', strtotime($request->to_date));
                    }
                    if (LeaveApplication::where([
                        'employee_id' => $employeeID,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                        'status' => 0
                    ])->exists()) {
                        $applicationExists = 1;
                    }
                } else {
                    if (LeaveApplication::where([
                        'employee_id' => $employeeID,
                        'application_type_id' => $applicationTypeID,
                        'attendance_not_marked_date' => $request->attendance_not_marked_date,
                        'status' => 0
                    ])->exists()) {
                        $applicationExists = 1;
                    }
                }
            } elseif ($applicationTypeName[0]['name'] == "Leave" && isset($leaveQuotas) && $leaveQuotas->no_of_allowed_leaves > $leaveQuotas->no_of_balanced_leaves) {
                if (isset($request->num_of_days) && $request->num_of_days >= 1 && $applicationTypeName[0]['name'] != 'Attendance not Marked') {
                    if (isset($request->from_date)) {
                        $from_date = date('Y-m-d', strtotime($request->from_date));
                    }
                    if (isset($request->to_date)) {
                        $to_date = date('Y-m-d', strtotime($request->to_date));
                    }
                    if (LeaveApplication::where([
                        'employee_id' => $employeeID,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                        'status' => 0
                    ])->exists()) {
                        $applicationExists = 1;
                    }
                } else {
                    if (LeaveApplication::where([
                        'employee_id' => $employeeID,
                        'application_type_id' => $applicationTypeID,
                        'attendance_not_marked_date' => $request->attendance_not_marked_date,
                        'status' => 0
                    ])->exists()) {
                        $applicationExists = 1;
                    }
                }
            } else {
                $applicationExists = 2;
            }


            return Response()->json($applicationExists);
        }
    }

    public function getLeaveRequests($id)
    {
        if ($id != '' && $id != 0) {
            $leaveRequests = LeaveApplication::with(['employee', 'leaveType', 'leaveApplicationType'])->where('forward_to', $id)->get();
            //dd($leaveRequests->toArray());
        }
        return view('employees.leave_applications.show_leave_requests_for_approval', compact('leaveRequests'));
    }
}
