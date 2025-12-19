<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Traits\SerializeDateTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Employee extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SerializeDateTrait;

    protected $fillable = [
        'user_id',
        'employee_id',
        'pin_code',
        'card_no',
        'prefix',
        'emp_image',
        'preferred_name',
        'father_name',
        'spouse_name',
        'nationality_id',
        'religion_id',
        'cnic_expiry',
        'marital_status',
        'date_of_marriage',
        'no_of_children',
        'children_in_ucs',
        'country_id',
        'state_id',
        'city_id',
        'job_status',
        'hiring_date',
        'confirm_date',
        'regular_date',
        'left_date',
        'from_date',
        'to_date',
        'probation_end_date',
        'probation_extended',
        'death_case',
        'death_date',
        'eobi_number',
        'ni_number',
        'mobile_number',
        'passport_number',
        'crb',
        'issue_date',
        'ss_no',
        'expiry_date',
        'previous_id',
        'company_id',
        'region_id',
        'branch_id',
        'department_id',
        'designation_id',
        'designation_type_id',
        'insurance_plan',
        'grade',
        'date_of_birth',

        'address',
        'reporting_to'
    ];

    protected $dates = [
        'date_of_birth',
        'cnic_expiry',
        'date_of_marriage',
        'hiring_date',
        'confirm_date',
        'regular_date',
        'left_date',
        'from_date',
        'to_date',
        'probation_end_date',
        'death_date',
        'issue_date',
        'expiry_date'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function countries()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function cities()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public static function nwa_department_filter()
    {
        $depart = [];
        $branch_id = get_set_NWABranchId();
        $departments = Employee::where('branch_id', '=', $branch_id)->groupBy('department_id')->pluck('department_id')->toArray();
        $depart = Department::whereIn('id', $departments)->get();
        //dd($depart);
        return $depart;
    }

    public static function nwa_designation_filter()
    {
        $desig = [];
        $branch_id = get_set_NWABranchId();
        $designations = Employee::where('branch_id', '=', $branch_id)->groupBy('designation_id')->pluck('designation_id')->toArray();
        $desig = Designation::whereIn('id', $designations)->get();
        //dd($depart);
        return $desig;
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class, 'nationality_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
    public function states()
    {
        return $this->belongsTo(State::class, 'state_id', 'id');
    }

    public function designation_type()
    {
        return $this->belongsTo(DesignationType::class, 'designation_type_id', 'id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reporting_manager()
    {
        return $this->belongsTo(User::class, 'reporting_to', 'id');
    }

    public function attendance()
    {
        return $this->hasMany(EmployeeAttendance::class, 'employee_id', 'id');
    }

    public function leaveApplications()
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function employeeWorkingDays()
    {
        return $this->hasMany(EmployeeWorkingDay::class, 'employee_id', 'id');
    }

    public function class_teachers()
    {
        return $this->hasMany(ClassTeacher::class, 'employee_id', 'id');
    }

    /**
     * @return array
     * @description This function is used to get employee attendance calendar all events
     */
    public function employeeAttendanceEvents()
    {
        /* getting logged in user employee record id */
        if (Auth::user()->hasRole('network_associate')) {
            $current_user = NetworkAssociate::where('user_id', Auth::id())->first('id');
        } else {
            $current_user = Employee::where('user_id', Auth::id())->first('id');
        }

        // If no current user record found, return empty events array
        if (! $current_user) {
            return [];
        }

        //dd($current_user->id);
        $attendanceEvents = $attendanceCheckInEvents = $attendanceCheckOutEvents = $attendanceOffDayEvents = $attendanceLeaveEvents = [];
        $att_type = null;
        /** formatting employee attendance events*/
        if (count($this->attendance) > 0) {
            //attendance loop started if records found.
            foreach ($this->attendance as $attendance) {
                /*get attendance day name like 'Monday, Tuesday'*/
                $checkInDayName = date('l', strtotime($attendance->created_at));
                $att_type = $leave_applied = $leave_status = $color = $leave_chk = $is_leave_applied = null;
                $i = 1;
                /*join employee table with employee_working_days, and working_shifts table to get employee shifts according to working days*/
                //dd($attendance);
                if ($current_user && isset($current_user->id) && $current_user->id == $this->id) {
                    $id = $current_user->id;
                } else {
                    $id = $this->id;
                }
                if ($attendance->attendance_type == 0) {
                    $employee = self::select('employees.id', 'eld.working_day_id', 'eld.official_leave_id', 'wd.abbreviation', 'wd.name', 'ld.start_time', 'ld.end_time', 'ld.status')
                        ->join('employee_official_leave_days as eld', 'employees.id', '=', 'eld.employee_id')
                        ->join('working_days as wd', 'eld.working_day_id', '=', 'wd.id')
                        ->join('official_leave_days as ld', 'eld.official_leave_id', '=', 'ld.id')
                        ->where('employees.id', $id)
                        ->where('wd.name', $checkInDayName)
                        ->first();
                    //dd($employee);
                    $att_type = 0;
                }
                if ($attendance->attendance_type == 1) {
                    $employee = self::select('employees.id', 'ewd.working_day_id', 'ewd.working_shift_id', 'wd.abbreviation', 'wd.name', 'ws.start_time', 'ws.end_time', 'ws.status')
                        ->join('employee_working_days as ewd', 'employees.id', '=', 'ewd.employee_id')
                        ->join('working_days as wd', 'ewd.working_day_id', '=', 'wd.id')
                        ->join('working_shifts as ws', 'ewd.working_shift_id', '=', 'ws.id')
                        ->where('employees.id', $id)
                        ->where('wd.name', $checkInDayName)
                        ->first();
                    //dd($employee);
                    $att_type = 1;
                }

                if ($att_type == 1) {
                    if (isset($employee->status) == 1) {
                        /*find checkin time difference*/
                        $checkInTimeDiff = calculateTimeDifference($employee->start_time, $attendance->time_in);
                        /*find checkout time difference*/
                        $checkOutTimeDiff = calculateTimeDifference($employee->end_time, $attendance->time_out);

                        $attendanceCheckInEvents = [
                            'title' => 'In ( ' . date('h:i', strtotime($attendance->time_in)) . ' )',
                            'start' => $attendance->created_at->toDateString()
                        ];

                        /*if checkin time difference is greater than 10, it consider late arrival*/
                        if ($checkInTimeDiff > 10) {
                            $attendanceCheckInEvents['color'] = 'red';
                        }
                        $attendanceEvents[] = $attendanceCheckInEvents;
                        if (! is_null($attendance->time_out)) {
                            $attendanceCheckOutEvents = [
                                'title' => 'Out ( ' . date('h:i', strtotime($attendance->time_out)) . ' )',
                                'start' => $attendance->created_at->toDateString()
                            ];
                            /*if checkout time difference is less than 10, it consider early leave*/
                            if ($checkOutTimeDiff < -10) {
                                $attendanceCheckOutEvents['color'] = 'red';
                            }
                            $attendanceEvents[] = $attendanceCheckOutEvents;
                        }

                        //if user is open his/her own attendance.
                        //time in checks started.
                        if ($checkInTimeDiff > 10 && $current_user && $current_user->id == $this->id) {
                            $leave_applied = LeaveApplication::where('employee_id', '=', $current_user->id)->where('application_type_id', 4)->where('from_date', '=', $attendance->created_at->toDateString())->with('leaveApplicationType')->first();
                            if (! is_null($leave_applied)) {
                                $is_leave_applied = 1;
                            }
                            if (isset($is_leave_applied)) {
                                //dd($leave_applied->toArray());
                                if ($leave_applied->status == '0') {
                                    $leave_status = 'Pending';
                                    $color = 'orange';
                                } elseif ($leave_applied->status == '1') {
                                    $leave_status = 'Approved';
                                    $color = 'green';
                                }
                                $attendanceEvents[] = [
                                    'title' => $leave_applied->leaveApplicationType->name . ' ( ' . $leave_status . ' )',
                                    'start' => $attendance->created_at->toDateString(),
                                    'color' => $color
                                ];
                            } else {
                                //if ($current_user->id == $this->id) {
                                $attendanceEvents[] = [
                                    'title' => 'Raise Late Arrival',
                                    'start' => $attendance->created_at->toDateString(),
                                    'url' => route('leave.application', $employee->id) . '?application_type=Late Arrival&date=' . $attendance->created_at->toDateString() . '&time_in=' . $attendance->time_in,
                                    'color' => 'orange'
                                ];
                                //}
                            }
                        } elseif ($checkInTimeDiff > 10 && $current_user && isset($current_user->id) && $current_user->id != $this->id) {
                            $leave_applied = LeaveApplication::where('employee_id', '=', $this->id)->where('application_type_id', 4)->where('from_date', '=', $attendance->created_at->toDateString())->with('leaveApplicationType')->first();
                            if (! is_null($leave_applied)) {
                                $is_leave_applied = 1;
                            }
                            if ($is_leave_applied) {
                                if ($leave_applied->status == '0') {
                                    $leave_status = 'Pending';
                                    $color = 'orange';
                                } elseif ($leave_applied->status == '1') {
                                    $leave_status = 'Approved';
                                    $color = 'green';
                                }
                                $attendanceEvents[] = [
                                    'title' => $leave_applied->leaveApplicationType->name . ' ( ' . $leave_status . ' )',
                                    'start' => $attendance->created_at->toDateString(),
                                    //'url' => route('leave.application', $employee->id) . '?application_type=Late Arrival&date=' . $attendance->created_at->toDateString() . '&time_in=' . $attendance->time_in,
                                    'color' => $color
                                ];
                            }
                        } else {
                            //timeout checks.
                            if (! is_null($attendance->time_out)) {
                                if ($checkOutTimeDiff < -10 && $current_user && isset($current_user->id) && $current_user->id == $this->id) {
                                    $leave_applied = LeaveApplication::where('employee_id', '=', $current_user->id)->where('application_type_id', 5)->where('from_date', '=', $attendance->created_at->toDateString())->with('leaveApplicationType')->first();
                                    if (! is_null($leave_applied)) {
                                        $is_leave_applied = 1;
                                    }
                                    if ($is_leave_applied) {
                                        if ($leave_applied->status == '0') {
                                            $leave_status = 'Pending';
                                            $color = 'orange';
                                        } elseif ($leave_applied->status == '1') {
                                            $leave_status = 'Approved';
                                            $color = 'green';
                                        }
                                        $attendanceEvents[] = [
                                            'title' => $leave_applied->leaveApplicationType->name . ' ( ' . $leave_status . ' )',
                                            'start' => $attendance->created_at->toDateString(),
                                            //'url' => route('leave.application', $employee->id) . '?application_type=Late Arrival&date=' . $attendance->created_at->toDateString() . '&time_in=' . $attendance->time_in,
                                            'color' => $color
                                        ];
                                    } else {
                                        //if ($current_user->id == $this->id) {
                                        $attendanceEvents[] = [
                                            'title' => 'Raise Early Leave',
                                            'start' => $attendance->created_at->toDateString(),
                                            'url' => route('leave.application', $employee->id) . '?application_type=Early Leaving&date=' . $attendance->created_at->toDateString() . '&time_out=' . $attendance->time_out,
                                            'color' => 'orange'
                                        ];
                                        //}
                                    }
                                } elseif ($checkOutTimeDiff < -10 && $current_user && isset($current_user->id) && $current_user->id != $this->id) {
                                    $leave_applied = LeaveApplication::where('employee_id', '=', $this->id)->where('application_type_id', 5)->where('from_date', '=', $attendance->created_at->toDateString())->with('leaveApplicationType')->first();
                                    if (! is_null($leave_applied)) {
                                        $is_leave_applied = 1;
                                    }
                                    if ($is_leave_applied) {
                                        if ($leave_applied->status == '0') {
                                            $leave_status = 'Pending';
                                            $color = 'orange';
                                        } elseif ($leave_applied->status == '1') {
                                            $leave_status = 'Approved';
                                            $color = 'green';
                                        }
                                        $attendanceEvents[] = [
                                            'title' => $leave_applied->leaveApplicationType->name . ' ( ' . $leave_status . ' )',
                                            'start' => $attendance->created_at->toDateString(),
                                            //'url' => route('leave.application', $employee->id) . '?application_type=Late Arrival&date=' . $attendance->created_at->toDateString() . '&time_in=' . $attendance->time_in,
                                            'color' => $color
                                        ];
                                    }
                                } else {
                                    //do nothing.
                                }
                            }
                        }
                    } elseif (isset($employee?->status) == 0) {
                        $attendanceOffDayEvents = [
                            'title' => 'Off Day',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'blue';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } else {
                        //do nothing.
                    }
                } else {
                    if ($employee?->status == 1) {
                        $attendanceOffDayEvents = [
                            'title' => 'Eid al-Fitar',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'green';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } elseif ($employee?->status == 2) {
                        $attendanceOffDayEvents = [
                            'title' => 'Eid al-Adha',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'green';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } elseif ($employee?->status == 3) {
                        $attendanceOffDayEvents = [
                            'title' => 'Pakistan Day',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'green';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } elseif ($employee?->status == 4) {
                        $attendanceOffDayEvents = [
                            'title' => 'Independence Day',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'green';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } elseif ($employee?->status == 5) {
                        $attendanceOffDayEvents = [
                            'title' => 'Quaid-e-Azam Day',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'green';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } elseif ($employee?->status == 6) {
                        $attendanceOffDayEvents = [
                            'title' => 'Labour Day',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'green';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } elseif ($employee?->status == 7) {
                        $attendanceOffDayEvents = [
                            'title' => 'Muharram',
                            'start' => $attendance->created_at->toDateString()
                        ];
                        $attendanceOffDayEvents['color'] = 'green';
                        $attendanceEvents[] = $attendanceOffDayEvents;
                    } else {
                        //nothing todo.
                    } /*  end schedule shifts status check. */
                }
            }// end foreach for attendance.
        }

        $id = null;

        if ($current_user && $this) {
            $id = $current_user->id == $this->id ? $current_user->id : $this->id;
        } elseif ($this) {
            $id = $this->id;
        } elseif ($current_user) {
            $id = $current_user->id;
        }
        //check user applied leaves.

        $leave_applied = LeaveApplication::where('employee_id', '=', $id)->whereNotIn('application_type_id', [4, 5])->with(['leaveApplicationType', 'leaveType'])->get();
        //dd($leave_applied->toArray());
        if ($leave_applied) {
            foreach ($leave_applied as $leaves) {
                if ($leaves['status'] == '0') {
                    $leave_status = 'Pending';
                    $color = 'orange';
                } else {
                    $leave_status = 'Approved';
                    $color = 'green';
                }
                if (isset($leaves->leaveApplicationType->name) && $leaves->leaveApplicationType->name == 'Attendance not Marked') {
                    $start = $leaves['attendance_not_marked_date']->toDateString();
                    $end = '';
                }
                if (isset($leaves->leaveApplicationType->name) && ($leaves->leaveApplicationType->name == 'Leave' || $leaves->leaveApplicationType->name == 'Out Station')) { //$leaves->leaveApplicationType->name == 'Late Arrival' || $leaves->leaveApplicationType->name == 'Early Leaving'
                    $start = $leaves['from_date']->toDateString();
                    if (isset($leaves['end_date'])) {
                        $end = $leaves['end_date']->toDateString();
                    } else {
                        $end = '';
                    }
                }
                if (isset($leaves->leaveType->name)) {
                    $attendanceEvents[] = [
                        'title' => $leaves->leaveType->name . ' ( ' . $leave_status . ' )',
                        'start' => $start,
                        'end' => $end,
                        'color' => $color
                    ];
                } else if (isset($leaves->leaveApplicationType->name)) {
                    $attendanceEvents[] = [
                        'title' => $leaves->leaveApplicationType->name . ' ( ' . $leave_status . ' )',
                        'start' => $start,
                        'end' => $end,
                        'color' => $color
                    ];
                }
            }
        }
        /*else
        {
            //dd($current_user->id);
            if($current_user && isset($current_user->id) && $current_user->id == $this->id){
            $attendanceEvents[] = [
                'title' => 'Absent (Raise Leave)',
                'start' => now()->toDateString(),
                'url' => route('leave.application', $this->id) . '?application_type=Leave&date=' . now()->toDateString(),
                'color' => 'red'
            ];
            }
        }*/

        return $attendanceEvents;
    }

    public function guardians()
    {
        return $this->belongsTo(Guardian::class, 'employee_id', 'employee_no');
    }

    public function getFullNameAttribute()
    {
        return $this->prefix . ' ' . $this->preferred_name;
    }

    // New salary-related relationships
    public function currentSalaryStructure()
    {
        return $this->hasOne(EmployeeSalaryStructure::class)->where('is_active', true);
    }

    public function salaryHistory()
    {
        return $this->hasMany(EmployeeSalaryStructure::class)->orderBy('effective_from', 'desc');
    }

    public function taxPreferences()
    {
        return $this->hasOne(EmployeeTaxPreference::class);
    }

    public function deductionPreferences()
    {
        return $this->hasMany(EmployeeDeductionPreference::class)->where('is_active', true);
    }

    public function getApplicableTaxSlab($grossSalary)
    {
        $preference = $this->taxPreferences;
        if ($preference && $preference->tax_slab_id) {
            return IncomeTaxSlab::find($preference->tax_slab_id);
        }

        return IncomeTaxSlab::where('status', 1)
            ->where('min_salary', '<=', $grossSalary)
            ->where('max_salary', '>=', $grossSalary)
            ->first();
    }
}
