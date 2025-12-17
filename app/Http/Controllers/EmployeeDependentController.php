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
use Yajra\Datatables\Datatables;
use App\Models\EmployeeDependent;
use Illuminate\Database\QueryException;

class EmployeeDependentController extends Controller
{
    
    public function index(Request $request)
    {
        //dump($request->all());
         if ($request->ajax()) {

            //dd($request->all());
             $data = EmployeeDependent::where('employee_id',$request->employee_id)->get();
             return Datatables::of($data)
                 ->addIndexColumn()
                 ->addColumn('action', function ($row) {
                     return view('employees.employee_dependent_actions', ['row' => $row]);
                 })
                 ->rawColumns(['action'])
                 ->make(true);
         }

        $data['employee_id'] = $request->employee_id;
        return view('employees.employee_dependents',$data);
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
        //dd($request->all());
        $validator = request()->validate([
            'employee_id' => 'required',
            'dependent_name' => 'required',
            'dependent_relationship' => 'required',
            'dependent_relationship' => [
                'bail',
                function ($attribute, $value, $fail) {
                    if (request()->filled('dependent_relationship')  && request()->filled($attribute) && ($value == 'Father' || $value == 'Mother')) {
                        $exist = NULL;
                        $exist = EmployeeDependent::where('employee_id',request()->employee_id)->where('dependent_relationship',$value)->first();
                        if(!is_null($exist))
                        {
                            return $fail('Duplicate entries are not allowed for: '. $value);
                        }

                    }
                }
            ],
            'dependent_dob' => 'required',
            'dependent_cnic' => [
                'required',
                'regex:/^\d{5}-\d{7}-\d{1}$/',
                'unique:employee_dependents,dependent_cnic',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        // Remove dashes for validation
                        $cnic = str_replace('-', '', $value);
                        
                        // Check if it's exactly 13 digits
                        if (!preg_match('/^\d{13}$/', $cnic)) {
                            return $fail('CNIC must be in format: 12345-1234567-1');
                        }
                        
                        // Validate first 5 digits (province code)
                        $provinceCode = substr($cnic, 0, 5);
                        if ($provinceCode < 10001 || $provinceCode > 99999) {
                            return $fail('Invalid province code in CNIC');
                        }
                        
                        // Validate middle 7 digits
                        $middleDigits = substr($cnic, 5, 7);
                        if ($middleDigits < 1000000 || $middleDigits > 9999999) {
                            return $fail('Invalid middle digits in CNIC');
                        }
                        
                        // Validate last digit (check digit)
                        $lastDigit = substr($cnic, 12, 1);
                        if ($lastDigit < 0 || $lastDigit > 9) {
                            return $fail('Invalid check digit in CNIC');
                        }
                    }
                }
            ],
        ], [
            'dependent_cnic.required' => 'CNIC is required.',
            'dependent_cnic.regex' => 'CNIC must be in format: 12345-1234567-1',
            'dependent_cnic.unique' => 'This CNIC is already registered with another dependent.',
        ]);

        $input = $request->all();
        // Set smart card to null by default
        $input['dependent_smart_card'] = null;
        
        //dd($input);
        $dependent = EmployeeDependent::create($input);

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

        return redirect(route('edit-employee', $data['id']) . '?tab=working_shifts')->with('success', 'Dependent added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeDependent  $employeeDependent
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeDependent $employeeDependent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeDependent  $employeeDependent
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, EmployeeDependent $employeeDependent)
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
        $employeeDependent = EmployeeDependent::find($request->id);
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
            'employeeDependent' => $employeeDependent
        ];
        return redirect(route('edit-employee', $data['id']) . '?dependent_record_id='.$request->id.'&tab=dependent_info');
        //return view('employees.employee_dependents', ['employeeDependent' => $employeeDependent]);
    }

    public function update(Request $request, EmployeeDependent $employeeDependent)
    {
        $validator = request()->validate([
            'employee_id' => 'required',
            'dependent_name' => 'required',
            'dependent_relationship' => 'required',
            'dependent_relationship' => [
                'bail',
                function ($attribute, $value, $fail) {
                    if (request()->filled('dependent_relationship')  && request()->filled($attribute) && ($value == 'Father' || $value == 'Mother')) {
                        $exist = NULL;
                        $exist = EmployeeDependent::where('id','!=',request()->id)->where('employee_id',request()->employee_id)->where('dependent_relationship',$value)->first();
                        if(!is_null($exist))
                        {
                            return $fail('Duplicate entries are not allowed for: '. $value);
                        }

                    }
                }
            ],
            'dependent_dob' => 'required',
            'dependent_cnic' => [
                'required',
                'regex:/^\d{5}-\d{7}-\d{1}$/',
                'unique:employee_dependents,dependent_cnic,' . $request->id,
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        // Remove dashes for validation
                        $cnic = str_replace('-', '', $value);
                        
                        // Check if it's exactly 13 digits
                        if (!preg_match('/^\d{13}$/', $cnic)) {
                            return $fail('CNIC must be in format: 12345-1234567-1');
                        }
                        
                        // Validate first 5 digits (province code)
                        $provinceCode = substr($cnic, 0, 5);
                        if ($provinceCode < 10001 || $provinceCode > 99999) {
                            return $fail('Invalid province code in CNIC');
                        }
                        
                        // Validate middle 7 digits
                        $middleDigits = substr($cnic, 5, 7);
                        if ($middleDigits < 1000000 || $middleDigits > 9999999) {
                            return $fail('Invalid middle digits in CNIC');
                        }
                        
                        // Validate last digit (check digit)
                        $lastDigit = substr($cnic, 12, 1);
                        if ($lastDigit < 0 || $lastDigit > 9) {
                            return $fail('Invalid check digit in CNIC');
                        }
                    }
                }
            ],
        ], [
            'dependent_cnic.required' => 'CNIC is required.',
            'dependent_cnic.regex' => 'CNIC must be in format: 12345-1234567-1',
            'dependent_cnic.unique' => 'This CNIC is already registered with another dependent.',
        ]);
        
        $input = $request->all();
        // Set smart card to null by default
        $input['dependent_smart_card'] = null;
        
        $employeeDependent->update($input);
        
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
        $employeeDependent = EmployeeDependent::find($request->id);
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
            'employeeDependent' => $employeeDependent
        ];
       // return redirect(route('edit-employee', $data['id']) . '?record_id='.$request->id.'&tab=dependent_info')->with('success', 'Record has been updated successfully.');
        return redirect(route('edit-employee', $data['id']) . '?tab=working_shifts')->with('success', 'Dependent Information has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeDependent  $employeeDependent
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeeDependent $employeeDependent)
    {
        try {
            return $employeeDependent->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
