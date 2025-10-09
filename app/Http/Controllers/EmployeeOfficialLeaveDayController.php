<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Country;
use App\Models\Employee;
use App\Models\Religion;
use App\Models\Department;
use App\Models\WorkingDay;
use App\Models\Designation;
use App\Models\Nationality;
use App\Models\AcademicYear;
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use App\Models\OfficialLeaveDay;
use Yajra\Datatables\Datatables;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeWorkingDay;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\EmployeeOfficialLeaveDay;
use Illuminate\Support\Facades\Validator;

class EmployeeOfficialLeaveDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = EmployeeOfficialLeaveDay::where('employee_id', $request->employee_id)->with([
               'employee',
               'working_day',
               'official_leave'
            ])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('leave_date', function ($row) {
                    $leave_date = NULL;
                    if(isset($row->leave_date))
                    {
                        $leave_date = date('d-M-Y',strtotime($row->leave_date));
                    }
                    return $leave_date;
                })
                ->addColumn('shift_timing', function ($row) {
                    $ShiftTiming = $row->official_leave->start_time . ' - ' . $row->official_leave->end_time;
                    return $ShiftTiming;
                })
                ->addColumn('status', function ($row) {
                    if($row->official_leave->status == '1'){
                        $LeaveStatus =  "Eid al-Fitar";
                    }else if($row->official_leave->status == '2'){
                        $LeaveStatus =  "Eid al-Adha";
                    }else if($row->official_leave->status == '3'){
                        $LeaveStatus =  "Pakistan Day";
                    }else if($row->official_leave->status == '4'){
                        $LeaveStatus =  "Independence Day";
                    }else if($row->official_leave->status == '5'){
                        $LeaveStatus =  "Quaid-e-Azam Day";
                    }else if($row->official_leave->status == '6'){
                        $LeaveStatus =  "Labour Day";
                    }else if($row->official_leave->status == '7'){
                        $LeaveStatus =  "Muharram";
                    }
                    return $LeaveStatus;
                })
                ->addColumn('action', function ($row) {
                    return view('employees.employee_official_leaves_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $working_days = WorkingDay::all();
        $official_leaves = OfficialLeaveDay::all();

        return view('employees.employee_official_leaves',[
            'working_days' => $working_days,
            'official_leaves' => $official_leaves
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'working_day_id' => 'required',
            'official_leave_id' => 'required',
            'leave_date' => 'required',
        ]);


        $AssigendLeaveDays = EmployeeOfficialLeaveDay::where([['employee_id','=' ,$request->employee_id],['working_day_id','=',$request->working_day_id],['official_leave_id','=',$request->official_leave_id],['leave_date','=',$request->leave_date]])->first(['employee_id','working_day_id','official_leave_id','leave_date']);

        $error= NULL;
        $attendance = [];
        if(isset($AssigendLeaveDays) && !is_null($AssigendLeaveDays))
        {
            $error=1;
        }else{
            $academic_year = AcademicYear::where('active',1)->first('id');
            EmployeeOfficialLeaveDay::create($request->all());
            $attendance['employee_id'] = $request->employee_id;
            $attendance['time_in'] = '00:00:00';
            $attendance['time_out'] = '23:59:59';
            $attendance['academic_year_id'] = $academic_year->id;
            $attendance['attendance_type'] = '0';
            $attendance['created_at'] = $request->leave_date;
            EmployeeAttendance::create($attendance);
        }


        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $religions = Religion::all();
        $nationalities = Nationality::all();
        $comapnies = Company::all();
        $departments = Department::all();
        $designations = Designation::all();
        //$categories = Category::all();
        $branches = Branch::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();
        $official_leaves = OfficialLeaveDay::all();
        $employee = Employee::where('id', $request->employee_id)->with([
            'branch',
            'countries',
            'cities',
            'department',
            'designation',
            'nationality',
            'company'
        ])->get();
        //dd($employee[0]);
        $data = [
            'id' => $request->employee_id,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'branches' => $branches,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts,
            'official_leaves' => $official_leaves,
            'employee' => $employee[0]
        ];
        if($error)
        {
            //return redirect(route('edit-employee', $data['id']) . '?tab=working_shifts')->with('success', 'Dependent added successfully.');
            return redirect(route('edit-employee', $data['id']) . '?tab=official_leaves')->with('error', 'Duplicate entry not allowed, selected leave is already assigned to the employee.');
        }
        else{
            return redirect(route('edit-employee', $data['id']) . '?tab=official_leaves')->with('success', 'Employee official leave record has been created successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeOfficialLeaveDay  $employeeOfficialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeOfficialLeaveDay $employeeOfficialLeaveDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeOfficialLeaveDay  $employeeOfficialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,EmployeeOfficialLeaveDay $employeeOfficialLeaveDay)
    {
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $religions = Religion::all();
        $nationalities = Nationality::all();
        $comapnies = Company::all();
        $departments = Department::all();
        $designations = Designation::all();
        //$categories = Category::all();
        $branches = Branch::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();
        $official_leaves = OfficialLeaveDay::all();
        $employeeWorkingDay = EmployeeWorkingDay::find($request->id);
        $employeeOfficialLeaveDay = EmployeeOfficialLeaveDay::find($request->id);
        $employee = Employee::where('id', $request->employee_id)->with([
            'branch',
            'countries',
            'cities',
            'department',
            'designation',
            'nationality',
            'company'
        ])->get();
        $data = [
            'id' => $request->employee_id,
            'leave_record_id' => $request->id,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'branches' => $branches,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts,
            'official_leaves' => $official_leaves,
            'employee' => $employee[0],
            'employeeWorkingDay' => $employeeWorkingDay,
            'employeeOfficialLeaveDay' => $employeeOfficialLeaveDay
        ];
       // dd($data);
        return redirect(route('edit-employee', $data['id']) . '?leave_record_id='.$request->id.'&tab=official_leaves');
        //return view('employees.employee_working_shifts', ['employeeWorkingDay' => $employeeWorkingDay]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeOfficialLeaveDay  $employeeOfficialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeOfficialLeaveDay $employeeOfficialLeaveDay)
    {
        $request->validate([
            'employee_id' => 'required',
            'working_day_id' => 'required',
            'official_leave_id' => 'required',
            'leave_date' => 'required',
        ]);
        $AssigendLeaveDays = EmployeeOfficialLeaveDay::where([['employee_id','=' ,$employeeOfficialLeaveDay->employee_id],['working_day_id','=',$employeeOfficialLeaveDay->working_day_id],['official_leave_id','=',$employeeOfficialLeaveDay->official_leave_id],['id','!=',$employeeOfficialLeaveDay->id]])->get(['employee_id','working_day_id','official_leave_id']);

        $error= NULL;
        if(isset($AssigendLeaveDays[0]) && !is_null($AssigendLeaveDays[0]))
        {
            $error=1;
        }else{
            $employeeOfficialLeaveDay->update($request->all());
        }
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $religions = Religion::all();
        $nationalities = Nationality::all();
        $comapnies = Company::all();
        $departments = Department::all();
        $designations = Designation::all();
        //$categories = Category::all();
        $branches = Branch::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();
        $employeeWorkingDay = EmployeeWorkingDay::find($request->id);
        $employeeOfficialLeaveDay = EmployeeOfficialLeaveDay::find($request->id);
        $employee = Employee::where('id', $request->employee_id)->with([
            'branch',
            'countries',
            'cities',
            'department',
            'designation',
            'nationality',
            'company'
        ])->get();
        $data = [
            'id' => $request->employee_id,
            'leave_record_id' => $request->id,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'branches' => $branches,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts,
            'employee' => $employee[0],
            'employeeWorkingDay' => $employeeWorkingDay,
            'employeeOfficialLeaveDay' => $employeeOfficialLeaveDay
        ];
        if($error)
        {
            return redirect(route('edit-employee', $data['id']) . '?leave_record_id='.$request->id.'&tab=official_leaves')->with('error', 'Duplicate entry not allowed, selected leave is already assigned to the employee.');
        }else{
            return redirect(route('edit-employee', $data['id']) . '?tab=official_leaves')->with('success', 'Employee leave record has been updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeOfficialLeaveDay  $employeeOfficialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeOfficialLeaveDay $employeeOfficialLeaveDay)
    {
        try {
            return $employeeOfficialLeaveDay->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
