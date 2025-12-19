<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Region;
use App\Models\EmployeeAttendance;
use App\Models\LeaveApplication;
use PDF;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Country;
use App\Models\Category;
use App\Models\Employee;
use App\Models\Guardian;
use App\Models\Religion;
use App\Models\Department;
use App\Models\WorkingDay;
use App\Models\Designation;
use App\Models\Nationality;
use Illuminate\Support\Str;
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use App\Models\DesignationType;
use App\Models\NetworkAssociate;
use App\Models\OfficialLeaveDay;
use Yajra\Datatables\Datatables;
use App\Models\EmployeeDependent;
use App\Models\StudentConcession;
use App\Models\EmployeeLeaveQuota;
use App\Models\EmployeeWorkingDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DesignationLeaveQuota;
use App\Models\NetworkAssociateBranch;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\EmployeeOfficialLeaveDay;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportEmployee;
use App\Exports\EmployeeTemplateExport;
use App\Exports\ExportEmployee;
use App\Jobs\ProcessEmployeeExport;
use App\Jobs\ProcessEmployeeImport;
use App\Models\ImportProgress;
use App\Models\ImportErrorLog;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $branch_id = 0;
                if (! Auth::user()->hasRole('super_admin|human_resource|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
                    $branch_id = get_branch_id();
                }

                // Base query with optimized relationships - only load what's needed
                $query = Employee::select([
                    'employees.id',
                    'employees.employee_id',
                    'employees.company_id',
                    'employees.branch_id',
                    'employees.department_id',
                    'employees.designation_id',
                    'employees.city_id',
                    'employees.created_at',
                    'employees.user_id'
                ])
                ->whereNotNull('employees.user_id') // Ensure only employees with valid user_id
                ->with([
                    'branch:id,br_name,branch_code',
                    'department:id,department_name',
                    'designation:id,designation_name',
                    'cities:id,city_name',
                    'company:id,company_name',
                    'user:id,first_name,last_name,gender'
                ]);

                // Apply branch filter early to reduce data set
                if ($branch_id != 0) {
                    $query->where('employees.branch_id', $branch_id);
                }

                // Apply filters with proper indexing hints
                if ($request->gender && $request->gender != null) {
                    $query->whereHas('user', function ($q) use ($request) {
                        $q->where('gender', $request->gender);
                    });
                }

                if ($request->company_id && $request->company_id > 0) {
                    $query->where('employees.company_id', $request->company_id);
                }

                if ($request->branch_id && $request->branch_id > 0) {
                    $query->where('employees.branch_id', $request->branch_id);
                }

                if ($request->department_id && $request->department_id > 0) {
                    $query->where('employees.department_id', $request->department_id);
                }

                if ($request->designation_id && $request->designation_id > 0) {
                    $query->where('employees.designation_id', $request->designation_id);
                }

                // Optimized search logic with better performance
                if ($request->searchName && $request->searchName != null) {
                    $searchTerm = trim($request->searchName);

                    if (Auth::user()->hasRole('super_admin|human_resource|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
                        $query->where(function ($q) use ($searchTerm) {
                            $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                                $userQuery->where(function ($uq) use ($searchTerm) {
                                    $uq->where('first_name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('employee_id', 'like', '%' . $searchTerm . '%');
                                });
                            })
                            ->orWhereHas('branch', function ($branchQuery) use ($searchTerm) {
                                $branchQuery->where('branch_code', 'like', '%' . $searchTerm . '%');
                            })
                            ->orWhereHas('cities', function ($cityQuery) use ($searchTerm) {
                                $cityQuery->where('city_name', 'like', '%' . $searchTerm . '%');
                            });
                        });
                    } else {
                        // For non-admin users, only search in their branch
                        $query->where(function ($q) use ($searchTerm) {
                            $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                                $userQuery->where(function ($uq) use ($searchTerm) {
                                    $uq->where('first_name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('employee_id', 'like', '%' . $searchTerm . '%');
                                });
                            });
                        });
                    }
                }

                // Handle DataTables ordering
                if ($request->order && count($request->order) > 0) {
                    $orderColumn = $request->order[0]['column'];
                    $orderDir = $request->order[0]['dir'];

                    $columns = ['id', 'employee_id', 'created_at'];
                    if (isset($columns[$orderColumn])) {
                        $query->orderBy('employees.' . $columns[$orderColumn], $orderDir);
                    } else {
                        $query->orderBy('employees.id', 'desc');
                    }
                } else {
                    $query->orderBy('employees.id', 'desc');
                }

                // Use DataTables with proper pagination handling
                $result = Datatables::of($query)
                    ->addIndexColumn()
                    ->addColumn('full_name', function ($row) {
                        $firstName = $row->user ? $row->user->first_name : '';
                        $lastName = $row->user ? $row->user->last_name : '';
                        return trim($firstName . ' ' . $lastName);
                    })
                    ->addColumn('company_name', function ($row) {
                        return $row->company->company_name ?? '';
                    })
                    ->addColumn('branch_code', function ($row) {
                        return $row->branch->branch_code ?? '';
                    })
                    ->addColumn('branch_name', function ($row) {
                        return $row->branch->br_name ?? '';
                    })
                    ->addColumn('department_name', function ($row) {
                        return $row->department->department_name ?? '';
                    })
                    ->addColumn('designation_name', function ($row) {
                        return $row->designation->designation_name ?? '';
                    })
                    ->addColumn('city_name', function ($row) {
                        return $row->cities->city_name ?? '';
                    })
                    ->addColumn('action', function ($row) {
                        // Ensure we have valid data before rendering actions
                        if (! $row->id || ! $row->user_id) {
                            return '<span class="text-muted">Invalid Record</span>';
                        }
                        return view('employees.actions', ['row' => $row]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);

                return $result;
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'An error occurred while loading employee data. Please try again.',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
        }

        // Cache frequently used data for better performance with longer cache duration
        $companies = cache()->remember('companies_list', 600, function () {
            return Company::select('id', 'company_name')->orderBy('company_name')->get();
        });

        if (Auth::user()->hasRole('network_associate')) {
            $departments = cache()->remember('nwa_departments', 600, function () {
                return Employee::nwa_department_filter();
            });
            $designations = cache()->remember('nwa_designations', 600, function () {
                return Employee::nwa_designation_filter();
            });
        } else {
            $departments = cache()->remember('departments_list', 600, function () {
                return Department::select('id', 'department_name')->orderBy('department_name')->get();
            });
            $designations = cache()->remember('designations_list', 600, function () {
                return Designation::select('id', 'designation_name')->orderBy('designation_name')->get();
            });
        }

        $regions = cache()->remember('regions_list', 600, function () {
            return Region::select('id', 'region_name')->orderBy('region_name')->get();
        });

        $branches = cache()->remember('branches_list', 600, function () {
            return Branch::select('id', 'br_name', 'branch_code')->orderBy('br_name')->get();
        });

        return view('employees.list_employees', [
            'companies'    => $companies,
            'departments'  => $departments,
            'designations' => $designations,
            'branches'     => $branches,
            'regions'      => $regions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $regions = Region::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();
        $official_leaves = OfficialLeaveDay::all();
        $data = [
            'basic_info' => 'active',
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'regions' => $regions,
            'branches' => $branches,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts,
            'official_leaves' => $official_leaves
        ];
        //dd($branches);
        return view('employees.employee_basic', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'prefix' => 'required|in:Mr,Mrs,Ms',
            'emp_image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'first_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
            'preferred_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
            'father_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
            'spouse_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
            'nationality_id' => 'required|exists:nationalities,id',
            'gender' => 'required|in:Male,Female',
            'religion_id' => 'required|exists:religions,id',
            'email' => 'required|email|unique:users,email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
            'password' => 'required|min:8|max:255',
            'date_of_birth' => 'required|before:today|after:1900-01-01',
            'CNIC' => 'required|unique:users,CNIC|regex:/^\d{5}-\d{7}-\d{1}$/',
            'cnic_expiry' => 'required|after:today',
            'pin_code' => 'nullable|unique:employees,pin_code|regex:/^\d+$/',
            'card_no' => 'nullable|unique:employees,card_no|regex:/^\d+$/',
            'marital_status' => 'nullable|in:Single,Married',
            'date_of_marriage' => 'nullable|after:date_of_birth',
            'no_of_children' => 'nullable|integer|min:0|max:20',
            'children_in_ucs' => 'nullable|integer|min:0|max:20|lte:no_of_children',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_id' => 'required|exists:cities,id',
        ], [
            'prefix.required' => 'Prefix is required.',
            'prefix.in' => 'Prefix must be Mr, Mrs, or Ms.',
            'first_name.required' => 'First name is required.',
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'preferred_name.regex' => 'Preferred name can only contain letters and spaces.',
            'father_name.regex' => 'Father name can only contain letters and spaces.',
            'spouse_name.regex' => 'Spouse name can only contain letters and spaces.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'email.regex' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth cannot be in the future.',
            'date_of_birth.after' => 'Date of birth must be after 1900.',
            'CNIC.required' => 'CNIC is required.',
            'CNIC.unique' => 'This CNIC is already taken.',
            'CNIC.regex' => 'CNIC must follow the format: 12345-1234567-1',
            'cnic_expiry.required' => 'CNIC expiry date is required.',
            'cnic_expiry.after' => 'CNIC expiry date must be in the future.',
            'pin_code.regex' => 'PIN code must contain only digits.',
            'card_no.regex' => 'Card number must contain only digits.',
            'date_of_marriage.after' => 'Date of marriage cannot be before date of birth.',
            'no_of_children.min' => 'Number of children cannot be negative.',
            'no_of_children.max' => 'Number of children cannot exceed 20.',
            'children_in_ucs.min' => 'Number of children in UCS cannot be negative.',
            'children_in_ucs.max' => 'Number of children in UCS cannot exceed 20.',
            'children_in_ucs.lte' => 'Children in UCS cannot exceed total number of children.',
            'marital_status.in' => 'Marital status must be Single or Married.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be Male or Female.',
            'religion_id.required' => 'Religion is required.',
            'religion_id.exists' => 'Religion is invalid.',
            'nationality_id.required' => 'Nationality is required.',
            'nationality_id.exists' => 'Nationality is invalid.',
            'country_id.required' => 'Country is required.',
            'country_id.exists' => 'Country is invalid.',
            'state_id.required' => 'State is required.',
            'state_id.exists' => 'State is invalid.',
            'city_id.required' => 'City is required.',
            'city_id.exists' => 'City is invalid.',
        ]);

        // Store dates exactly as received from frontend
        $input = $request->all();

        // Clear marriage-related fields if marital status is not Married
        if ($input['marital_status'] !== 'Married') {
            $input['date_of_marriage'] = null;
            $input['no_of_children'] = null;
            $input['children_in_ucs'] = null;
        }

        if ($request->hasfile('emp_image')) {
            /*$path = public_path() . '/uploads/employees/';
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }*/
            // $destination_path = public_path('/uploads/employees');
            $emp_img_filename = Str::random(32) . '.' . $request->emp_image->getClientOriginalExtension();
            // $request->emp_image->move($destination_path, $emp_img_filename);
            $input['emp_image'] = $emp_img_filename;
            $filepath = 'images/' . $emp_img_filename;
            $s3path = Storage::disk('s3')->put($filepath, file_get_contents($request->emp_image));
            $s3path = Storage::disk('s3')->url($s3path);
        } else {
            $input['emp_image'] = 'user-dummy-img.jpg';
        }

        $input['password'] = bcrypt($request->password);
        $input['name'] = $request->first_name . ' ' . $request->last_name;

        DB::beginTransaction();
        $user = User::create($input);
        $input['user_id'] = $user->id;
        if (! auth()->user()->hasRole('super_admin')) {
            $input['branch_id'] = get_branch_id();
            $input['employee_id'] = Employee::max('employee_id');
            if (is_null($input['employee_id'])) {
                $input['employee_id'] = 1001;
            } else {
                $input['employee_id'] = $input['employee_id'] + 1 ;
            }
        }
        $employee = Employee::create($input);
        DB::commit();

        return redirect(route('edit-employee', $employee->id) . '?tab=service_info')->with('success', 'Employee Basic info successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $employee = Employee::where('id', $employee->id)->with([
            'branch',
            'region',
            'countries',
            'cities',
            'department',
            'designation',
            'nationality',
            'designation_type',
            'company',
            'user'
        ])->get();
        //dd($employee->toArray());
        return view('employees.employee_profile_modal', ['employee' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {

        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $religions = Religion::all();
        $nationalities = Nationality::all();
        $comapnies = Company::all();
        $departments = Department::all();
        //$categories = Category::all();
        $regions = Region::all();
        $branches = Branch::all();
        $designations = Designation::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();
        $official_leaves = OfficialLeaveDay::all();
        $data = [
            //'id' => $employee->id,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'regions' => $regions,
            'branches' => $branches,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts,
            'official_leaves' => $official_leaves,
            'employee' => $employee
        ];


        //dd($data);
        return view('employees.employee_basic', $data);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        // request()->validate([
        //     'prefix' => 'required',
        //     'first_name' => 'required',
        //     'father_name' => 'required',
        //     'nationality_id' => 'required',
        //     'gender'=> 'required',
        //     'religion_id'=> 'required',
        //     'CNIC' => 'required',
        //     'country_id' => 'required',
        //     'state_id' => 'required',
        //     'city_id' => 'required',
        // ]);
        //dd($request->toArray());
        $input = $request->all();

        // Comprehensive validation for service info updates
        if ($request->form_info == 'service') {
            $request->validate([
                'hiring_date' => 'nullable|before_or_equal:today',
                'confirm_date' => 'nullable|date_format:Y-m-d|before_or_equal:today|after_or_equal:hiring_date',
                'job_status' => 'nullable|in:Regular,Adhoc,Contractual,Probation',
                'regular_date' => 'nullable|before_or_equal:today|after_or_equal:hiring_date',
                'left_date' => 'nullable|before_or_equal:today|after_or_equal:hiring_date',
                'from_date' => 'nullable|before_or_equal:to_date',
                'to_date' => 'nullable|after_or_equal:from_date',
                'probation_end_date' => 'nullable|after_or_equal:hiring_date',
                'probation_extended' => 'nullable|string|max:100',
                'death_case' => 'nullable|in:Y',
                'death_date' => 'nullable|date_format:Y-m-d|before_or_equal:today|required_if:death_case,Y',
                'eobi_number' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
                'ni_number' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
                'mobile_number' => 'nullable|string|max:15|regex:/^[0-9\+\-\(\)\s]+$/',
                'passport_number' => 'nullable|string|max:20|regex:/^[A-Z0-9]+$/',
                'expiry_date' => 'nullable|date_format:Y-m-d|after:today|required_with:passport_number',
                'crb' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
                'issue_date' => 'nullable|date_format:Y-m-d|before_or_equal:today|required_with:crb',
                'ss_no' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
            ], [
                'hiring_date.date_format' => 'Hiring date must be in YYYY-MM-DD format.',
                'hiring_date.before_or_equal' => 'Hiring date cannot be in the future.',
                'confirm_date.date_format' => 'Confirmation date must be in YYYY-MM-DD format.',
                'confirm_date.before_or_equal' => 'Confirmation date cannot be in the future.',
                'confirm_date.after_or_equal' => 'Confirmation date cannot be before hiring date.',
                'job_status.in' => 'Job status must be Regular, Adhoc, Contractual, or Probation.',
                'regular_date.date_format' => 'Regular date must be in YYYY-MM-DD format.',
                'regular_date.before_or_equal' => 'Regular date cannot be in the future.',
                'regular_date.after_or_equal' => 'Regular date cannot be before hiring date.',
                'left_date.date_format' => 'Left date must be in YYYY-MM-DD format.',
                'left_date.before_or_equal' => 'Left date cannot be in the future.',
                'left_date.after_or_equal' => 'Left date cannot be before hiring date.',
                'from_date.date_format' => 'From date must be in YYYY-MM-DD format.',
                'from_date.before_or_equal' => 'From date cannot be after to date.',
                'to_date.date_format' => 'To date must be in YYYY-MM-DD format.',
                'to_date.after_or_equal' => 'To date cannot be before from date.',
                'probation_end_date.date_format' => 'Probation end date must be in YYYY-MM-DD format.',
                'probation_end_date.after_or_equal' => 'Probation end date cannot be before hiring date.',
                'probation_extended.max' => 'Probation extended cannot exceed 100 characters.',
                'death_case.in' => 'Death case must be Y or empty.',
                'death_date.date_format' => 'Death date must be in YYYY-MM-DD format.',
                'death_date.before_or_equal' => 'Death date cannot be in the future.',
                'death_date.required_if' => 'Death date is required when death case is selected.',
                'eobi_number.max' => 'EOBI number cannot exceed 20 characters.',
                'eobi_number.regex' => 'EOBI number can only contain uppercase letters, numbers, and hyphens.',
                'ni_number.max' => 'N.I. number cannot exceed 20 characters.',
                'ni_number.regex' => 'N.I. number can only contain uppercase letters, numbers, and hyphens.',
                'mobile_number.max' => 'Mobile number cannot exceed 15 characters.',
                'mobile_number.regex' => 'Mobile number can only contain numbers, spaces, hyphens, and parentheses.',
                'passport_number.max' => 'Passport number cannot exceed 20 characters.',
                'passport_number.regex' => 'Passport number can only contain uppercase letters and numbers.',
                'expiry_date.date_format' => 'Passport expiry date must be in YYYY-MM-DD format.',
                'expiry_date.after' => 'Passport expiry date must be in the future.',
                'expiry_date.required_with' => 'Passport expiry date is required when passport number is provided.',
                'crb.regex' => 'CRB can only contain uppercase letters, numbers, and hyphens.',
                'crb.max' => 'CRB cannot exceed 20 characters.',
                'issue_date.date_format' => 'Issue date must be in YYYY-MM-DD format.',
                'issue_date.before_or_equal' => 'Issue date cannot be in the future.',
                'issue_date.required_with' => 'Issue date is required when CRB is provided.',
                'ss_no.max' => 'Social Security number cannot exceed 20 characters.',
                'ss_no.regex' => 'Social Security number can only contain uppercase letters, numbers, and hyphens.',
            ]);
        }

        if ($request->form_info == 'company') {
            $designation_type_id = Designation::where('id', $input['designation_id'])->get('type_id');
            $input['designation_type_id'] = $designation_type_id[0]['type_id'];
            $input['employee_id'] = Employee::max('employee_id');
            if (is_null($input['employee_id'])) {
                $input['employee_id'] = 1001;
            } else {
                $input['employee_id'] = $input['employee_id'] + 1 ;
            }

            //assigning leave qoutas to newly added employee.
            $emp_leave_qoutas = DesignationLeaveQuota::where('designation_id', $input['designation_id'])->get(['designation_id','leave_type_id','no_of_allowed_leaves']);
            //dd($emp_leave_qoutas->toArray());
            foreach ($emp_leave_qoutas as $emp_leave_qouta) {
                $leave_inputs[] = ['employee_id' => $employee->id,'designation_id' => $emp_leave_qouta->designation_id,'leave_type_id' => $emp_leave_qouta->leave_type_id, 'no_of_allowed_leaves' => $emp_leave_qouta->no_of_allowed_leaves];
            }

            collect($leave_inputs)->each(function ($input_qoutas) {
                EmployeeLeaveQuota::create($input_qoutas);
            });
        }

        $employee->update($input);

        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $religions = Religion::all();
        $nationalities = Nationality::all();
        $comapnies = Company::all();
        $departments = Department::all();
        $designations = Designation::all();
        //$categories = Category::all();
        $regions = Region::all();
        $branches = Branch::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();
        $official_leaves = OfficialLeaveDay::all();

        $data = [
            'id' => $employee->id,
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'regions' => $regions,
            'branches' => $branches,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts,
            'official_leaves' => $official_leaves,
            'employee' => $employee
        ];
        //dd($data);
        if ($request->form_info == 'service') {
            return redirect(route('employees.edit', $data['id']) . '?tab=company_info')->with('success', 'Employee service info has been saved successfully.');
        }
        if ($request->form_info == 'company') {
            return redirect(route('employees.edit', $data['id']) . '?tab=dependent_info')->with('success', 'Employee company info has been saved successfully.');
        }
    }


    public function editEmployee(Request $request, Employee $employee, $id)
    {
        $employeeDependent = $employeeWorkingDay = $employeeOfficialLeaveDay = null;
        if ($request->has('dependent_record_id')) {
            $record_id = $request->input('dependent_record_id');
            $employeeDependent = EmployeeDependent::find($record_id);
        }
        if ($request->has('record_id')) {
            $record_id = $request->input('record_id');
            $employeeWorkingDay = EmployeeWorkingDay::find($record_id);
        }
        if ($request->has('leave_record_id')) {
            $record_id = $request->input('leave_record_id');
            $employeeOfficialLeaveDay = EmployeeOfficialLeaveDay::find($record_id);
        }
        $countries = Country::all();
        $states = State::all();
        $cities = City::all();
        $regions = Region::all();
        $religions = Religion::all();
        $nationalities = Nationality::all();
        $comapnies = Company::all();
        $departments = new Department();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $departments = $departments->where('for_school', 1);
        }
        $departments = $departments->get();

        $designations = new Designation();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $designations = $designations->where('for_school', 1);
        }
        $designations = $designations->get();

        //$categories = Category::all();
        if (! auth()->user()->hasRole('super_admin') && ! isHeadOfficeEmp()) {
            $branches = Branch::where('id', get_branch_id())->get();
        } else {
            $branches = Branch::all();
        }

        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::all();
        $official_leaves = OfficialLeaveDay::all();
        $employee = Employee::where('id', $id)->with([
            'branch',
            'region',
            'countries',
            'cities',
            'department',
            'designation',
            'nationality',
            'company',
            'reporting_manager',
            'user'
        ])->get();

         //dd($employee->toArray());
        $data = [
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'regions' => $regions,
            'branches' => $branches,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts,
            'official_leaves' => $official_leaves,
            'employee' => $employee,
            'employeeDependent' => $employeeDependent,
            'employeeWorkingDay' => $employeeWorkingDay,
            'employeeOfficialLeaveDay' => $employeeOfficialLeaveDay
        ];
        //dd($data);
        return view('employees.edit_employee_basic', $data);
    }



    public function updateEmployee(Request $request, Employee $employee)
    {
        $employee_record = Employee::find($request->id);
        $user = $employee_record['user'];
        $input = []; // Ensure $input is always defined

        // Comprehensive validation for basic info updates
        if ($request->form_info == 'basic') {
            $request->validate([
                'prefix' => 'required|in:Mr,Mrs,Ms',
                'emp_image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'first_name' => 'required|max:100|regex:/^[a-zA-Z\s]+$/',
                'last_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
                'preferred_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
                'father_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
                'spouse_name' => 'nullable|max:100|regex:/^[a-zA-Z\s]+$/',
                'nationality_id' => 'required|exists:nationalities,id',
                'gender' => 'required|in:Male,Female',
                'religion_id' => 'required|exists:religions,id',
                'email' => 'required|email|unique:users,email,' . $employee_record->user_id . '|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'date_of_birth' => 'required|before:today|after:1900-01-01',
                'CNIC' => 'required|unique:users,CNIC,' . $employee_record->user_id . '|regex:/^\d{5}-\d{7}-\d{1}$/',
                'cnic_expiry' => 'required|after:today',
                'pin_code' => 'nullable|unique:employees,pin_code,' . $request->id . '|regex:/^\d+$/',
                'card_no' => 'nullable|unique:employees,card_no,' . $request->id . '|regex:/^\d+$/',
                'marital_status' => 'nullable|in:Single,Married',
                'date_of_marriage' => 'nullable|after:date_of_birth',
                'no_of_children' => 'nullable|integer|min:0|max:20',
                'children_in_ucs' => 'nullable|integer|min:0|max:20|lte:no_of_children',
                'country_id' => 'nullable|exists:countries,id',
                'state_id' => 'nullable|exists:states,id',
                'city_id' => 'nullable|exists:cities,id',
            ], [
                'prefix.required' => 'Prefix is required.',
                'prefix.in' => 'Prefix must be Mr, Mrs, or Ms.',
                'first_name.required' => 'First name is required.',
                'first_name.regex' => 'First name can only contain letters and spaces.',
                'last_name.regex' => 'Last name can only contain letters and spaces.',
                'preferred_name.regex' => 'Preferred name can only contain letters and spaces.',
                'father_name.regex' => 'Father name can only contain letters and spaces.',
                'spouse_name.regex' => 'Spouse name can only contain letters and spaces.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already taken.',
                'email.regex' => 'Please enter a valid email address.',
                'date_of_birth.required' => 'Date of birth is required.',
                'date_of_birth.before' => 'Date of birth cannot be in the future.',
                'date_of_birth.after' => 'Date of birth must be after 1900.',
                'CNIC.required' => 'CNIC is required.',
                'CNIC.unique' => 'This CNIC is already taken.',
                'CNIC.regex' => 'CNIC must follow the format: 12345-1234567-1',
                'cnic_expiry.required' => 'CNIC expiry date is required.',
                'cnic_expiry.after' => 'CNIC expiry date must be in the future.',
                'pin_code.regex' => 'PIN code must contain only digits.',
                'card_no.regex' => 'Card number must contain only digits.',
                'date_of_marriage.after' => 'Date of marriage cannot be before date of birth.',
                'no_of_children.min' => 'Number of children cannot be negative.',
                'no_of_children.max' => 'Number of children cannot exceed 20.',
                'children_in_ucs.min' => 'Number of children in UCS cannot be negative.',
                'children_in_ucs.max' => 'Number of children in UCS cannot exceed 20.',
                'children_in_ucs.lte' => 'Children in UCS cannot exceed total number of children.',
            ]);

            // Store dates exactly as received from frontend
            $input = $request->all();

            // Clear marriage-related fields if marital status is not Married
            if (isset($input['marital_status']) && $input['marital_status'] !== 'Married') {
                $input['date_of_marriage'] = null;
                $input['no_of_children'] = null;
                $input['children_in_ucs'] = null;
            }

            // Handle employee image upload
            if ($request->hasFile('emp_image')) {
                $image = $request->file('emp_image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/employees'), $imageName);
                $input['emp_image'] = $imageName;
            }

            // Update employee record
            $employee_record->update($input);

            // Update user record
            $user_record = User::find($employee_record->user_id);
            $input['name'] = $input['first_name'] . ' ' . $input['last_name'];
            // dd($input);
            $user_record->update($input);
        }

        if ($request->form_info == 'company') {
            // Make sure $input is defined
            if (empty($input)) {
                $input = $request->all();
            }

            $designation = Designation::where('id', $input['designation_id'])->first();
            $input['designation_type_id'] = $designation['type_id'];
            $user->syncRoles([$designation['role_id']]);

            if (is_null($employee_record->employee_id)) {
                $input['employee_id'] = Employee::max('employee_id');
                if (is_null($input['employee_id'])) {
                    $input['employee_id'] = 1001;
                } else {
                    $input['employee_id'] = $input['employee_id'] + 1 ;
                }
            }
            //assigning leave qoutas to employee if not assigned before.
            $employee_leave_quota = null;
            $employee_leave_quota = EmployeeLeaveQuota::where('employee_id', $request->id)->first();
            if (is_null($employee_leave_quota)) {
                $emp_leave_qoutas = DesignationLeaveQuota::where('designation_id', $input['designation_id'])->get(['designation_id','leave_type_id','no_of_allowed_leaves']);
                $leave_inputs = [];
                foreach ($emp_leave_qoutas as $emp_leave_qouta) {
                    $leave_inputs[] = ['employee_id' => $request->id,'designation_id' => $emp_leave_qouta->designation_id,'leave_type_id' => $emp_leave_qouta->leave_type_id, 'no_of_allowed_leaves' => $emp_leave_qouta->no_of_allowed_leaves];
                }

                collect($leave_inputs)->each(function ($input_qoutas) {
                    EmployeeLeaveQuota::create($input_qoutas);
                });
            } else {
                $previous_designation = EmployeeLeaveQuota::where('employee_id', $request->id)->first('designation_id');
                if ($previous_designation->designation_id != $input['designation_id']) {
                    //dd('different designation');
                    $previous_leave_quota = EmployeeLeaveQuota::where('employee_id', $request->id)->orderBy('leave_type_id')->get(['leave_type_id','no_of_balanced_leaves']);
                    //dd($previous_leave_quota->toArray());
                    $designation_leave_quotas = DesignationLeaveQuota::where('designation_id', $input['designation_id'])->orderBy('leave_type_id')->get(['designation_id','leave_type_id','no_of_allowed_leaves']);
                    EmployeeLeaveQuota::where('employee_id', $request->id)->delete();
                    $leave_inputs = [];
                    foreach ($designation_leave_quotas as $emp_new_quota) {
                        $leave_inputs[] = ['employee_id' => $request->id,'designation_id' => $emp_new_quota->designation_id,'leave_type_id' => $emp_new_quota->leave_type_id, 'no_of_allowed_leaves' => $emp_new_quota->no_of_allowed_leaves];
                    }
                    collect($leave_inputs)->each(function ($input_quotas) {
                        EmployeeLeaveQuota::create($input_quotas);
                    });

                    foreach ($previous_leave_quota as $previous_quota) {
                        $current_leave_quota = [];
                        $balanced_inputs = [];
                        $current_leave_quota = EmployeeLeaveQuota::where('employee_id', $request->id)->where('leave_type_id', $previous_quota->leave_type_id)->first();
                        //dd($current_leave_quota->toArray());
                        if (isset($current_leave_quota)) {
                            $balanced_inputs['no_of_balanced_leaves'] = $previous_quota->no_of_balanced_leaves;
                            $current_leave_quota->update($balanced_inputs);
                        }
                    }
                } else {
                    //do nothing if designation is not changed.
                }
            }
        }

        // Comprehensive validation for service info updates
        if ($request->form_info == 'service') {
            $request->validate([
                'hiring_date' => 'nullable|before_or_equal:today',
                'confirm_date' => 'nullable|after_or_equal:hiring_date',
                'job_status' => 'nullable|in:Regular,Adhoc,Contractual,Probation',
                'regular_date' => 'nullable|before_or_equal:today|after_or_equal:hiring_date',
                'left_date' => 'nullable|before_or_equal:today|after_or_equal:hiring_date',
                'from_date' => 'nullable|before_or_equal:to_date',
                'to_date' => 'nullable|after_or_equal:from_date',
                'probation_end_date' => 'nullable|after_or_equal:hiring_date',
                'probation_extended' => 'nullable|string|max:100',
                'death_case' => 'nullable|in:Y',
                'death_date' => 'nullable|before_or_equal:today|required_if:death_case,Y',
                'eobi_number' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
                'ni_number' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
                'mobile_number' => 'nullable|string|max:15|regex:/^[0-9\+\-\(\)\s]+$/',
                'passport_number' => 'nullable|string|max:20|regex:/^[A-Z0-9]+$/',
                'expiry_date' => 'nullable|after:today|required_with:passport_number',
                'crb' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
                'issue_date' => 'nullable|before_or_equal:today|required_with:crb',
                'ss_no' => 'nullable|string|max:20|regex:/^[A-Z0-9\-]+$/',
            ], [
                'hiring_date.before_or_equal' => 'Hiring date cannot be in the future.',
                'confirm_date.after_or_equal' => 'Confirmation date cannot be before hiring date.',
                'job_status.in' => 'Job status must be one of: Regular, Adhoc, Contractual, or Probation.',
                'regular_date.before_or_equal' => 'Regular date cannot be in the future.',
                'regular_date.after_or_equal' => 'Regular date cannot be before hiring date.',
                'left_date.before_or_equal' => 'Left date cannot be in the future.',
                'left_date.after_or_equal' => 'Left date cannot be before hiring date.',
                'from_date.before_or_equal' => 'From date cannot be after To date.',
                'to_date.after_or_equal' => 'To date cannot be before From date.',
                'probation_end_date.after_or_equal' => 'Probation end date cannot be before hiring date.',
                'probation_extended.max' => 'Probation extended cannot exceed 100 characters.',
                'death_case.in' => 'Death case must be Y or left empty.',
                'death_date.before_or_equal' => 'Death date cannot be in the future.',
                'death_date.required_if' => 'Death date is required when death case is Y.',
                'eobi_number.max' => 'EOBI number cannot exceed 20 characters.',
                'eobi_number.regex' => 'EOBI number can only contain uppercase letters, numbers, and hyphens.',
                'ni_number.max' => 'NI number cannot exceed 20 characters.',
                'ni_number.regex' => 'NI number can only contain uppercase letters, numbers, and hyphens.',
                'mobile_number.max' => 'Mobile number cannot exceed 15 characters.',
                'mobile_number.regex' => 'Mobile number can only contain numbers, spaces, hyphens, plus sign, and parentheses.',
                'passport_number.max' => 'Passport number cannot exceed 20 characters.',
                'passport_number.regex' => 'Passport number can only contain uppercase letters and numbers.',
                'expiry_date.after' => 'Passport expiry date must be in the future.',
                'expiry_date.required_with' => 'Passport expiry date is required when passport number is provided.',
                'crb.regex' => 'CRB can only contain uppercase letters, numbers, and hyphens.',
                'crb.max' => 'CRB cannot exceed 20 characters.',
                'issue_date.before_or_equal' => 'Issue date cannot be in the future.',
                'issue_date.required_with' => 'Issue date is required when CRB is provided.',
                'ss_no.max' => 'Social Security number cannot exceed 20 characters.',
                'ss_no.regex' => 'Social Security number can only contain uppercase letters, numbers, and hyphens.',
            ]);
            // Make sure $input is defined for service update
            if (empty($input)) {
                $input = $request->all();
            }
        }

        // if($input['job_status'] == 'Left')
        // {
        //     $students  = Guardian::where(['is_parent' => 'yes','employee_no' => $employee_record->employee_id])->get();
        //     if(isset($students[0]))
        //     {
        //         foreach($students as $student)
        //         {
        //             StudentConcession::where('student_id', $student->student_id)->update(['is_valid' => 0]);
        //         }
        //     }

        // }

        // Make sure $input is always defined before update
        if (empty($input)) {
            $input = $request->all();
        }
        $employee_record->update($input);

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
        $regions = Region::all();
        $employee = Employee::where('id', $request->id)->with([
            'branch',
            'region',
            'region',
            'countries',
            'cities',
            'department',
            'designation',
            'nationality',
            'company',
            'user'
        ])->get();

        $data = [
            'countries' => $countries,
            'states' => $states,
            'cities' => $cities,
            'religions' => $religions,
            'nationalities' => $nationalities,
            'comapnies' => $comapnies,
            'departments' => $departments,
            'designations' => $designations,
            //'categories' => $categories,
            'regions' => $regions,
            'branches' => $branches,
            'employee' => $employee,
            'id' => $request->id
        ];

        if ($request->form_info == 'basic') {
            return redirect(route('edit-employee', $data['id']) . '?tab=service_info')->with('success', 'Employee basic info has been updated successfully.');
        }
        if ($request->form_info == 'service') {
            return redirect(route('edit-employee', $data['id']) . '?tab=company_info')->with('success', 'Employee service info has been updated successfully.');
        }
        if ($request->form_info == 'company') {
            return redirect(route('edit-employee', $data['id']) . '?tab=dependent_info')->with('success', 'Employee company info has been updated successfully.');
        }
    }

    public function getBranchAdministrativeStaff(Request $request, $id)
    {
        if ($request->ajax()) {
            $branch = Branch::find($id);
            $data = Employee::where('branch_id', $branch->id)
                ->whereNotNull('user_id') // Ensure only employees with valid user_id
                ->with([
                    'branch',
                    'countries',
                    'cities',
                    'department',
                    'designation',
                    'designation_type',
                    'nationality',
                    'company',
                    'user'
                ]);

            if ($request->type_id && $request->type_id > 0) {
                $data->where('designation_type_id', $request->type_id);
            }

            /*if($request->searchName && $request->searchName != null ){

                $data->orWhereHas('user', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('last_name', 'like', '%' . $request->searchName . '%');
                    $query->orWhere('gender', 'like', '%' . $request->searchName . '%');
                });

                $data->orWhereHas('company', function ($query) use ($request) {
                    $query->where('company_name', 'like', '%' . $request->searchName . '%');
                });

                $data->orWhereHas('branch', function ($query) use ($request) {
                    $query->where('br_name', 'like', '%' . $request->searchName . '%');
                });

                $data->orWhereHas('department', function ($query) use ($request) {
                    $query->where('department_name', 'like', '%' . $request->searchName . '%');
                });

                $data->orWhereHas('designation', function ($query) use ($request) {
                    $query->where('designation_name', 'like', '%' . $request->searchName . '%');
                });

                $data->orWhereHas('cities', function ($query) use ($request) {
                    $query->where('city_name', 'like', '%' . $request->searchName . '%');
                });

            }*/

            $data = $data->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $firstName = $row->user ? $row->user->first_name : '';
                    $lastName = $row->user ? $row->user->last_name : '';
                    return trim($firstName . ' ' . $lastName);
                })
                ->addColumn('designation_name', function ($row) {
                    return isset($row['designation']) ? $row['designation']['designation_name'] : '';
                })
                ->addColumn('designation_type_name', function ($row) {
                    return isset($row['designation_type']) ? $row['designation_type']['type_name'] : '';
                })
                ->addColumn('action', function ($row) {
                    // Ensure we have valid data before rendering actions
                    if (! $row->id || ! $row->user_id) {
                        return '<span class="text-muted">Invalid Record</span>';
                    }
                    return view('employees.emp-actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('employees.branch_administrative_staff');
    }

    public function getReportingManager(Request $request)
    {
        if ($request->ajax()) {
            $html = '<option value="">Please select reporting manager</option>';

            // Get all employees in the selected branch (excluding the current employee)
            $employees = Employee::where('branch_id', $request->branch_id)
                ->where('id', '!=', $request->employee_id)
                ->whereNull('left_date')
                ->with('user')
                ->get();

            foreach ($employees as $employee) {
                $html .= '<option value="' . $employee->user_id . '">' . ($employee->user->name ?? 'Unknown User') . '</option>';
            }

            return $html;
        }
    }

    public function getEmployeeAttendance()
    {
        $employee = Employee::where('user_id', Auth::id())->get();
        return view('employees.getmyattendance', ['employee' => $employee]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        try {
            return $employee->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function getEmployeeUsingEmpId(Request $request)
    {
        try {
            if (isset($request->emp_id)) {
                $employee = Employee::with(['user'])->where('employee_id', $request->emp_id)->first();
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Sent Successfully','data' => $employee]);
            } else {
                return response()->json(['code' => 422, 'status' => 'success', 'message' => 'Data Not Found','data' => new \stdClass()]);
            }
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function generateEmployeeSalarySlip($id = null)
    {
        if ($id != null) {
            $employee = Employee::find($id);
        } else {
            $employee = auth()->user()->employee;
        }

        if (! empty($employee)) {
            // Get last month's data
            $lastMonth = date("n", strtotime("last month"));
            $lastYear = date("Y", strtotime("last month"));
            $lastMonthName = date("F", strtotime("last month"));

            // Fetch the latest processed payroll for this employee
            $payroll = Payroll::with([
                'details'
            ])->where('employee_id', $employee->id)
              ->where('month', $lastMonth)
              ->where('year', $lastYear)
              ->orderBy('created_at', 'desc')
              ->first();

            // Get attendance summary for the month
            $attendanceSummary = $this->getAttendanceSummary($employee->id, $lastMonth, $lastYear);

            // Prepare data for the salary slip
            $salarySlipData = [
                'employee' => $employee,
                'payroll' => $payroll,
                'attendance_summary' => $attendanceSummary,
                'month' => $lastMonthName,
                'year' => $lastYear,
                'period' => $lastMonthName . '-' . $lastYear
            ];

            $pdf = PDF::loadView('employees.partials.salary-slip', $salarySlipData);
            $fileName = $employee->employee_id . '_' . $employee->preferred_name . '_salary_slip_' . $lastMonthName . '_' . $lastYear . '.pdf';
            return $pdf->stream($fileName);
        }
        return abort(404);
    }

    /**
     * Generate salary slip for specific month/year
     */
    public function generateEmployeeSalarySlipForPeriod($id = null, $month = null, $year = null)
    {
        if ($id != null) {
            $employee = Employee::find($id);
        } else {
            $employee = auth()->user()->employee;
        }

        // Use provided month/year or default to last month
        $targetMonth = $month ?: date("n", strtotime("last month"));
        $targetYear = $year ?: date("Y", strtotime("last month"));
        $targetMonthName = $month ? date("F", mktime(0, 0, 0, $month, 1, $year)) : date("F", strtotime("last month"));

        if (! empty($employee)) {
            // Fetch the processed payroll for this employee and period
            $payroll = Payroll::with([
                'details'
            ])->where('employee_id', $employee->id)
              ->where('month', $targetMonth)
              ->where('year', $targetYear)
              ->orderBy('created_at', 'desc')
              ->first();

            // Get attendance summary for the month
            $attendanceSummary = $this->getAttendanceSummary($employee->id, $targetMonth, $targetYear);

            // Prepare data for the salary slip
            $salarySlipData = [
                'employee' => $employee,
                'payroll' => $payroll,
                'attendance_summary' => $attendanceSummary,
                'month' => $targetMonthName,
                'year' => $targetYear,
                'period' => $targetMonthName . '-' . $targetYear
            ];

            $pdf = PDF::loadView('employees.partials.salary-slip', $salarySlipData);
            $fileName = $employee->employee_id . '_' . $employee->preferred_name . '_salary_slip_' . $targetMonthName . '_' . $targetYear . '.pdf';
            return $pdf->stream($fileName);
        }
        return abort(404);
    }

    /**
     * Get attendance summary for salary slip (similar to PayrollController)
     */
    private function getAttendanceSummary($employeeId, $month, $year)
    {
        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        // Get attendance records for the employee in the specified period
        $attendances = \App\Models\EmployeeAttendance::where('employee_id', $employeeId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        // Calculate working days for the month (typically 30 days)
        $totalWorkingDays = 30; // Standard working days per month
        $presents = $attendances->where('status', 'present')->count();
        $absents = $attendances->where('status', 'absent')->count();
        $lateMinutes = $attendances->sum('late_minutes') ?? 0;
        $extraHours = $attendances->sum('extra_hours') ?? 0;

        // Get approved leaves
        $approvedLeaves = \App\Models\LeaveApplication::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('from_date', [$start, $end])
                  ->orWhereBetween('to_date', [$start, $end]);
            })
            ->get();

        return [
            'total_working_days' => $totalWorkingDays,
            'presents' => $presents,
            'absents' => $absents,
            'late_minutes' => $lateMinutes,
            'extra_hours' => $extraHours,
            'approved_leaves' => $approvedLeaves
        ];
    }

    /**
     * Show employee import form
     */
    public function showImportForm()
    {
        return view('employees.import_form');
    }

    /**
     * Download employee import template
     */
    public function downloadTemplate()
    {
        return Excel::download(new EmployeeTemplateExport(), 'employee_import_template.xlsx');
    }

        /**
     * Import employees from Excel/CSV file
     */
    public function importEmployees(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:102400', // 100MB max for large imports
        ]);

        try {
            // Generate unique import ID
            $importId = uniqid('emp_import_', true);

            // Store file temporarily (use local disk explicitly)
            $file = $request->file('file');
            $fileName = $importId . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('imports/employees', $fileName, 'local');

            // Create import progress record
            $importProgress = ImportProgress::create([
                'import_id' => $importId,
                'import_type' => 'employee',
                'user_id' => auth()->id(),
                'file_name' => $file->getClientOriginalName(),
                'status' => 'pending',
                'current_message' => 'Import queued for processing...',
            ]);

            // Dispatch job to queue
            ProcessEmployeeImport::dispatch(
                $filePath,
                $importId,
                auth()->id()
            );

            Log::info('Employee import job dispatched', [
                'import_id' => $importId,
                'file_path' => $filePath,
                'user_id' => Auth::id(),
            ]);

            // Return JSON response with import ID for WebSocket subscription
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Import started successfully! Processing in background...',
                    'import_id' => $importId
                ]);
            }

            return redirect()->back()->with([
                'success' => 'Import started successfully! Processing in background...',
                'import_id' => $importId
            ]);
        } catch (\Exception $e) {
            Log::error('Employee import dispatch failed: ' . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import failed: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    /**
     * Get import statistics
     */
    public function getImportStats(Request $request)
    {
        $importId = $request->get('import_id');

        if (! $importId) {
            return response()->json(['error' => 'Import ID required'], 400);
        }

        $importProgress = ImportProgress::where('import_id', $importId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $importProgress) {
            return response()->json(['error' => 'Import not found'], 404);
        }

        return response()->json([
            'import_id' => $importProgress->import_id,
            'status' => $importProgress->status,
            'total_rows' => $importProgress->total_rows,
            'processed_rows' => $importProgress->processed_rows,
            'imported_count' => $importProgress->imported_count,
            'skipped_count' => $importProgress->skipped_count,
            'error_count' => $importProgress->error_count,
            'current_row' => $importProgress->current_row,
            'current_message' => $importProgress->current_message,
            'errors' => $importProgress->errors,
            'progress_percentage' => $importProgress->progress_percentage,
            'started_at' => $importProgress->started_at,
            'completed_at' => $importProgress->completed_at,
        ]);
    }

    /**
     * Export all employees with real-time progress
     */
    public function exportEmployees(Request $request)
    {
        // No validation needed since we're exporting all data without filters

        try {
            // Generate unique export ID
            $exportId = uniqid('emp_export_', true);

            // Create export progress record
            $exportProgress = ImportProgress::create([
                'import_id' => $exportId,
                'import_type' => 'employee_export',
                'user_id' => auth()->id(),
                'file_name' => 'employee_export_' . date('Y-m-d_H-i-s') . '.xlsx',
                'status' => 'pending',
                'current_message' => 'Export queued for processing...',
            ]);

            // No filters - export all employees
            $filters = [];

            // Dispatch job to queue
            ProcessEmployeeExport::dispatch(
                $exportId,
                auth()->id(),
                $filters
            );

            Log::info('Employee export job dispatched', [
                'export_id' => $exportId,
                'filters' => $filters,
                'user_id' => Auth::id(),
            ]);

            // Return JSON response with export ID for WebSocket subscription
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Export started successfully! Processing in background...',
                    'export_id' => $exportId
                ]);
            }

            return redirect()->back()->with([
                'success' => 'Export started successfully! Processing in background...',
                'export_id' => $exportId
            ]);
        } catch (\Exception $e) {
            Log::error('Employee export dispatch failed: ' . $e->getMessage(), [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Export failed: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Get export statistics
     */
    public function getExportStats(Request $request)
    {
        $exportId = $request->get('export_id');

        if (! $exportId) {
            return response()->json(['error' => 'Export ID required'], 400);
        }

        $exportProgress = ImportProgress::where('import_id', $exportId)
            ->where('import_type', 'employee_export')
            ->where('user_id', auth()->id())
            ->first();

        if (! $exportProgress) {
            return response()->json(['error' => 'Export not found'], 404);
        }

        return response()->json([
            'export_id' => $exportProgress->import_id,
            'status' => $exportProgress->status,
            'total_rows' => $exportProgress->total_rows,
            'processed_rows' => $exportProgress->processed_rows,
            'imported_count' => $exportProgress->imported_count,
            'skipped_count' => $exportProgress->skipped_count,
            'error_count' => $exportProgress->error_count,
            'current_row' => $exportProgress->current_row,
            'current_message' => $exportProgress->current_message,
            'errors' => $exportProgress->errors,
            'progress_percentage' => $exportProgress->progress_percentage,
            'started_at' => $exportProgress->started_at,
            'completed_at' => $exportProgress->completed_at,
        ]);
    }

    /**
     * Download completed export file
     */
    public function downloadExport(Request $request)
    {
        $exportId = $request->get('export_id');

        if (! $exportId) {
            return response()->json(['error' => 'Export ID required'], 400);
        }

        $exportProgress = ImportProgress::where('import_id', $exportId)
            ->where('import_type', 'employee_export')
            ->where('user_id', auth()->id())
            ->first();

        if (! $exportProgress) {
            return response()->json(['error' => 'Export not found'], 404);
        }

        if ($exportProgress->status !== 'completed') {
            return response()->json(['error' => 'Export not completed yet'], 400);
        }

        // File path (local disk uses storage/app as root)
        $filePath = 'exports/employees/employee_export_' . $exportId . '.xlsx';

        if (! Storage::disk('local')->exists($filePath)) {
            return response()->json(['error' => 'Export file not found. Please try exporting again.'], 404);
        }

        return Storage::disk('local')->download($filePath, $exportProgress->file_name);
    }

    /**
     * Get error logs for a specific import
     */
    public function getImportErrorLogs(Request $request)
    {
        $importId = $request->get('import_id');

        if (! $importId) {
            return response()->json(['error' => 'Import ID required'], 400);
        }

        // Get import progress to verify ownership
        $importProgress = ImportProgress::where('import_id', $importId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $importProgress) {
            return response()->json(['error' => 'Import not found'], 404);
        }

        // Get error logs with pagination
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 50);
        $errorType = $request->get('error_type');

        $query = ImportErrorLog::forImport($importId)
            ->orderBy('occurred_at', 'desc');

        if ($errorType) {
            $query->byErrorType($errorType);
        }

        $errorLogs = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $errorLogs->items(),
            'pagination' => [
                'current_page' => $errorLogs->currentPage(),
                'last_page' => $errorLogs->lastPage(),
                'per_page' => $errorLogs->perPage(),
                'total' => $errorLogs->total(),
                'from' => $errorLogs->firstItem(),
                'to' => $errorLogs->lastItem(),
            ],
            'error_summary' => $this->getErrorSummary($importId)
        ]);
    }

    /**
     * Get error summary for an import
     */
    public function getErrorSummary($importId)
    {
        $summary = ImportErrorLog::forImport($importId)
            ->selectRaw('error_type, COUNT(*) as count')
            ->groupBy('error_type')
            ->get()
            ->keyBy('error_type');

        return [
            'validation_error' => $summary->get('validation_error')->count ?? 0,
            'import_error' => $summary->get('import_error')->count ?? 0,
            'lookup_error' => $summary->get('lookup_error')->count ?? 0,
            'missing_fields' => $summary->get('missing_fields')->count ?? 0,
            'database_error' => $summary->get('database_error')->count ?? 0,
            'total' => $summary->sum('count')
        ];
    }

    /**
     * Clear error logs for a specific import
     */
    public function clearImportErrorLogs(Request $request)
    {
        $importId = $request->get('import_id');

        if (! $importId) {
            return response()->json(['error' => 'Import ID required'], 400);
        }

        // Get import progress to verify ownership
        $importProgress = ImportProgress::where('import_id', $importId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $importProgress) {
            return response()->json(['error' => 'Import not found'], 404);
        }

        $deletedCount = ImportErrorLog::truncateForImport($importId);

        return response()->json([
            'success' => true,
            'message' => "Cleared {$deletedCount} error log entries",
            'deleted_count' => $deletedCount
        ]);
    }

    /**
     * Get recent import history for the user
     */
    public function getImportHistory(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $imports = ImportProgress::where('user_id', auth()->id())
            ->where('import_type', 'employee')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Add error counts to each import
        $imports->getCollection()->transform(function ($import) {
            $errorCount = ImportErrorLog::forImport($import->import_id)->count();
            $import->error_count = $errorCount;
            return $import;
        });

        return response()->json([
            'success' => true,
            'data' => $imports->items(),
            'pagination' => [
                'current_page' => $imports->currentPage(),
                'last_page' => $imports->lastPage(),
                'per_page' => $imports->perPage(),
                'total' => $imports->total(),
            ]
        ]);
    }
}
