<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;
use App\Models\Employee;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax())
        {
            EmployeeAttendance::create($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeAttendance  $employeeAttendance
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeAttendance $employeeAttendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeAttendance  $employeeAttendance
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeAttendance $employeeAttendance, Request $request)
    {
        $Attendance = EmployeeAttendance::find($request->id);
        $employeeAttendance['id'] = $Attendance['id'];
        $employeeAttendance['employee_id'] = $Attendance['employee_id'];
        if($request->type == 'in')
        {
            $employeeAttendance['time_in'] = $Attendance['time_in'];
        }
        else
        {
            $employeeAttendance['time_out'] = $Attendance['time_out'];
        }
        $employeeAttendance['academic_year_id'] = $Attendance['academic_year_id'];
        $employeeAttendance['status'] = $Attendance['status'];
        $employeeAttendance['attendance_type'] = $Attendance['attendance_type'];
        $employeeAttendance['created_at'] = $Attendance['created_at'];
        $employeeAttendance['rec_type'] = $request->type;
        $employees = Employee::where('branch_id','=',getBranch(Auth::user()->id))->whereNull('left_date')->with('user','department','designation')->get();

        if($request->month && $request->year)
        {
            $month = $request->year.'-'.$request->month;
            $start = Carbon::parse($month)->startOfMonth()->format('d-m-Y');
            $end = Carbon::parse($month)->endOfMonth()->format('d-m-Y');
            $attendance_month = Carbon::create()->day(1)->month($request->month);
            $attendance_month = Carbon::parse($attendance_month)->format('M');
            $calendar_date = $attendance_month .' / '. $request->year;
            //dd($attendance_month);
        }else{
            $month = $employeeAttendance['created_at'];
            $start = Carbon::parse($month)->startOfMonth()->format('d-m-Y');
            $end = Carbon::parse($month)->endOfMonth()->format('d-m-Y');
            //$attendance_month = date('m');
            $attendance_year = Carbon::parse($start)->format('Y');
            $attendance_month = Carbon::parse($start)->format('M');
            $calendar_date = $attendance_month .' / '. $attendance_year;
        }

        $total_number_of_days = $this->getDatesFromRange($start, $end);
        $academic_years = AcademicYear::all();

        //dd($total_number_of_days);
        return view('employees.attendance.index',compact('employees','academic_years','total_number_of_days','calendar_date','employeeAttendance'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeAttendance  $employeeAttendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmployeeAttendance $employeeAttendance)
    {
        if($request->ajax())
        {
            $attendance = EmployeeAttendance::find($request->id);
            $attendance->time_out = $request->time_out;
            $attendance->update();
        }
    }

    function getDatesFromRange($start, $end, $format = 'Y-m-d') {

        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        // Use loop to store date into array
        $i = 0;
        foreach($period as $date) {
            $array[$i]['day'] = Carbon::createFromFormat('Y-m-d', $date->format($format))->format('d-D');
            $array[$i]['dated'] = Carbon::createFromFormat('Y-m-d', $date->format($format));
            $i++;
        }

        // Return the array elements
        return $array;
    }

    public function attendance_sheet(Request $request)
    {
        $employees = Employee::where('branch_id','=',getBranch(Auth::user()->id))->whereNull('left_date')->with('user','department','designation')->get();
        $month = null;
        if($request->month && $request->year)
        {
            $month = $request->year.'-'.$request->month;
            $start = Carbon::parse($month)->startOfMonth()->format('d-m-Y');
            $end = Carbon::parse($month)->endOfMonth()->format('d-m-Y');
            $attendance_month = Carbon::create()->day(1)->month($request->month);
            $attendance_month = Carbon::parse($attendance_month)->format('M');
            $calendar_date = $attendance_month .' / '. $request->year;
            //dd($attendance_month);
        }else{
            $start = new Carbon('first day of this month');
            $start = Carbon::parse($start)->format('d-m-Y');
            $end   = new Carbon('last day of this month');
            $end   = Carbon::parse($end)->format('d-m-Y');
            $attendance_month = Carbon::create()->day(1)->month($start);
            $attendance_year = Carbon::parse($start)->format('Y');
            $attendance_month = Carbon::parse($start)->format('M');
            $calendar_date = $attendance_month .' / '. $attendance_year;
        }
        $total_number_of_days = $this->getDatesFromRange($start, $end);
        $academic_years = AcademicYear::all();

        //dd($total_number_of_days);
        return view('employees.attendance.index',compact('employees','academic_years','calendar_date','total_number_of_days'));
    }

    public function attendance_mark(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'type' => 'required',
            'date_time' => 'required',
            'academic_year_id' => 'required',
        ]);
        //dd($request->all());
        $employee = null;
        //$now = Carbon::now();
        $now = Carbon::parse($request->date_time)->format('Y-m-d');

        if($request->type == 'in')
        {
            $employee = EmployeeAttendance::where('employee_id', $request->employee_id)->where('academic_year_id', $request->academic_year_id)->where('attendance_type', 1)->whereNotNull('time_in')->whereDate('created_at', $now)->get();
            if(isset($employee[0]))
            {
                return redirect()->route('attendance-sheet')->with('error', 'Attendance time in already marked. Duplicate marking not allowed!');
            }
            else
            {
                $date_time = explode(" ",$request->date_time);
                $input['academic_year_id'] = $request->academic_year_id;
                $input['employee_id'] = $request->employee_id;
                $input['time_in'] = $date_time[1];
                $input['created_at'] = $request->date_time;
                $input['attendance_type'] = 1;
                EmployeeAttendance::create($input);
            }
        }
        else
        {
            $employee = EmployeeAttendance::where('employee_id', $request->employee_id)->where('academic_year_id', $request->academic_year_id)->where('attendance_type', 1)->whereNotNull('time_out')->whereDate('created_at', $now)->get();
            if(isset($employee[0]))
            {
                return redirect()->route('attendance-sheet')->with('error', 'Attendance time out is already marked. Duplicate marking not allowed!');
            }
            else
            {
                //$now = Carbon::now();
                //$now = Carbon::parse($request->date_time)->format('Y-m-d');
                $date_time = explode(" ",$request->date_time);
                EmployeeAttendance::where('employee_id', $request->employee_id)->where('academic_year_id', $request->academic_year_id)->where('attendance_type', 1)->whereDate('created_at',$now)->update(['time_out' => $date_time[1]]);
            }
        }
        return redirect()->route('attendance-sheet')->with('success', 'Attendance has been marked.');
    }

    public function update_marked_attendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'type' => 'required',
            'date_time' => 'required',
            'academic_year_id' => 'required',
        ]);
        //dd($request->all());
        $employee = null;
        $now = Carbon::parse($request->date_time)->format('Y-m-d');

        if($request->type == 'in')
        {
            $date_time = explode(" ",$request->date_time);
            $input['academic_year_id'] = $request->academic_year_id;
            $input['employee_id'] = $request->employee_id;
            $input['time_in'] = $date_time[1];
            $input['created_at'] = $request->date_time;
            $input['attendance_type'] = 1;
            EmployeeAttendance::where('employee_id', $request->employee_id)->where('id', $request->id)->update($input);

        }
        else
        {
            $date_time = explode(" ",$request->date_time);
            $input['academic_year_id'] = $request->academic_year_id;
            $input['employee_id'] = $request->employee_id;
            $input['time_out'] = $date_time[1];
            EmployeeAttendance::where('employee_id', $request->employee_id)->where('id', $request->id)->update($input);

        }
        $month = Carbon::parse($request->date_time)->startOfMonth()->format('m');
        $year = Carbon::parse($request->date_time)->endOfMonth()->format('Y');
        return redirect()->route('attendance-sheet',['month'=>$month,'year'=>$year])->with('success', 'Attendance has been marked.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeAttendance  $employeeAttendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeAttendance $employeeAttendance)
    {
        //
    }
}
