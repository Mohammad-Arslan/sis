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
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\EmployeeWorkingDay;
use Illuminate\Database\QueryException;

class EmployeeWorkingDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  $data = EmployeeWorkingDay::where('employee_id', $request->employee_id)->with([
        //     'employee',
        //     'working_day',
        //     'working_shift'
        //  ])->toSQL();
        //dd($request->toArray());
        if ($request->ajax()) {
            //dd($request->employee_id);
            $data = EmployeeWorkingDay::where('employee_id', $request->employee_id)->with([
               'employee',
               'working_day',
               'working_shift'
            ])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('shift_timing', function ($row) {
                    $ShiftTiming = $row->working_shift->start_time . ' - ' . $row->working_shift->end_time;
                    return $ShiftTiming;
                })
                ->addColumn('status', function ($row) {
                    if($row->working_shift->status == '0'){
                        $Status =  "Off Day";
                    }else{
                        $Status =  "Working Shift";
                    }
                    return $Status;
                })
                ->addColumn('action', function ($row) {
                    return view('employees.employee_shifts_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        //dd($data->toArray());
        }
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();

        return view('employees.employee_working_shifts',[
            'working_days' => $working_days,
            'working_shifts' => $working_shifts
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
            'working_shift_id' => 'required',
        ]);


        $AssigendWorkingDays = EmployeeWorkingDay::where([['employee_id','=' ,$request->employee_id],['working_day_id','=',$request->working_day_id]])->get(['employee_id','working_day_id']);

        $error= NULL;
        if(isset($AssigendWorkingDays[0]) && !is_null($AssigendWorkingDays[0]))
        {
            $error=1;
        }else{
            EmployeeWorkingDay::create($request->all());
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
            'employee' => $employee[0]
        ];
        if($error)
        {
            //return redirect(route('edit-employee', $data['id']) . '?tab=working_shifts')->with('success', 'Dependent added successfully.');
            return redirect(route('edit-employee', $data['id']) . '?tab=working_shifts')->with('error', 'Duplicate entry not allowed, selected schedule is already assigned to the employee.');
        }
        else{
            return redirect(route('edit-employee', $data['id']) . '?tab=working_shifts')->with('success', 'Employee working shift has been created successfully.');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeWorkingDay  $employeeWorkingDay
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeWorkingDay $employeeWorkingDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeWorkingDay  $employeeWorkingDay
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, EmployeeWorkingDay $employeeWorkingDay)
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
        $employeeWorkingDay = EmployeeWorkingDay::find($request->id);
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
            'record_id' => $request->id,
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
            'employeeWorkingDay' => $employeeWorkingDay
        ];
        //dd($data);
        return redirect(route('edit-employee', $data['id']) . '?record_id='.$request->id.'&tab=working_shifts');
        //return view('employees.employee_working_shifts', ['employeeWorkingDay' => $employeeWorkingDay]);
    }

    public function update(Request $request, EmployeeWorkingDay $employeeShift)
    {
        //dd($request->id);
        $request->validate([
            'employee_id' => 'required',
            'working_day_id' => 'required',
            'working_shift_id' => 'required',
        ]);
        $AssigendWorkingDays = EmployeeWorkingDay::where([['employee_id','=' ,$request->employee_id],['working_day_id','=',$request->working_day_id],['id','!=',$employeeShift->id]])->get(['id','employee_id','working_day_id','working_shift_id']);
        $error= NULL;
        if(isset($AssigendWorkingDays[0]) && !is_null($AssigendWorkingDays[0]))
        {
            $error=1;
        }else{
            $employeeShift->update($request->all());
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
            'record_id' => $request->id,
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
            'employeeWorkingDay' => $employeeWorkingDay
        ];
        if($error)
        {
            return redirect(route('edit-employee', $data['id']).'&tab=working_shifts')->with('error', 'Duplicate entry not allowed, selected schedule is already assigned to the employee.');
        }else{
            return redirect(route('edit-employee', $data['id']) . '?tab=official_leaves')->with('success', 'Employee working shift has been updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeWorkingDay  $employeeWorkingDay
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeWorkingDay $employeeShift)
    {
        try {
            return $employeeShift->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
