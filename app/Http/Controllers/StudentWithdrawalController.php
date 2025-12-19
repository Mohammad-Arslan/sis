<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\ComClass;
use App\Models\Employee;
use App\Models\Guardian;
use App\Models\Relation;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentInvoice;
use App\Models\StudentWithdrawal;
use App\Models\StudentWithdrawalRequest;
use App\Models\WithdrawalReason;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;
use Session;
use App\Models\StudentFeePackage;

class StudentWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = StudentWithdrawal::with([
                'reason:id,withdrawal_reason',
                'approved_by:id,preferred_name',
                'guardian:id,guardian_name,mobile',
                'created_by:id,name',
                'student' => function ($q) {
                    $q->select('id', 'first_name', 'middle_name', 'last_name', 'roll_no', 'status', 'branch_id')
                        ->with([
                            'branch:id,br_name,branch_code,region_id',
                            'branch.region:id,region_name',
                            'active_class.branch_class_sections.com_classes:id,class_name',
                            'active_class.branch_class_sections.sections:id,section_name',
                        ]);
                }
            ])
                ->select([
                    'id',
                    'student_id',
                    'guardian_id',
                    'clearance_amount',
                    'last_day_at',
                    'withdrawal_wef',
                    'created_at',
                    'withdrawal_reason_id',
                    'application_id',
                    'approved_date',
                    'created_by',
                    'approved_by',
                ])
                ->whereHas('student', function (Builder $query) use ($request) {
                    if ($request->filled('status') && $request->status !== 'all') {
                        $request->status === 'null'
                            ? $query->whereNull('status')
                            : $query->where('status', $request->status);
                    }

                    if ($request->filled('searchName')) {
                        $query->where(function ($q) use ($request) {
                            $q->where('first_name', 'like', '%' . $request->searchName . '%')
                                ->orWhere('middle_name', 'like', '%' . $request->searchName . '%')
                                ->orWhere('last_name', 'like', '%' . $request->searchName . '%');
                        });
                    }

                    if ($request->filled('branch_id')) {
                        $query->where('branch_id', $request->branch_id);
                    } elseif (! isSuperAdmin() && ! isHeadOfficeEmp()) {
                        $query->where('branch_id', get_branch_id());
                    }

                    if ($request->filled('section_id')) {
                        $query->whereHas('std_fee_package', fn($q) => $q->where('section_id', $request->section_id));
                    }

                    if ($request->filled('class_id')) {
                        $query->whereHas('active_class.branch_class_sections', fn($q) => $q->where('class_id', $request->class_id));
                    }

                    // Only include students who have at least one invoice
                    $query->whereHas('student_invoices');
                })
                ->orderBy('created_at', 'desc');
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('application_id', fn($row) => view('students.withdrawal.withdrawal_app_id_link', compact('row')))
                ->addColumn('region', function ($row) {
                    return optional(optional(optional($row->student)->branch)->region)->region_name ?? 'N/A';
                })
                ->addColumn('approved_by', fn($row) => optional($row->getRelationValue('approved_by'))->preferred_name ?? 'N/A')
                ->addColumn('student_name', fn($row) => trim(optional($row->student)->first_name . ' ' . optional($row->student)->middle_name . ' ' . optional($row->student)->last_name))
                ->addColumn('class_section', function ($row) {
                    $class = optional(optional(optional($row->student)->active_class)->branch_class_sections)->com_classes->class_name ?? 'N/A';
                    $section = optional(optional(optional($row->student)->active_class)->branch_class_sections)->sections->section_name ?? 'N/A';
                    return "$class / $section";
                })
                ->addColumn('branch_code', fn($row) => optional(optional($row->student)->branch)->branch_code ?? 'N/A')
                ->addColumn('branch_name', fn($row) => optional(optional($row->student)->branch)->br_name ?? 'N/A')
                ->addColumn('security_amount', function ($row) {
                    $total = 0;
                    $invoices = StudentInvoice::with(['student_invoice_items.fee_charges'])
                        ->where('student_id', $row->student_id)->get();

                    foreach ($invoices as $invoice) {
                        $fees = calculate_total_price_by_invoice($invoice);
                        $total += $fees['invoices_charges']['SD'] ?? 0;
                    }

                    return 'Rs. ' . number_format($total);
                })
                ->addColumn('guardian.guardian_name', fn($row) => optional($row->guardian)->guardian_name ?? 'N/A')
                ->addColumn('guardian.mobile', fn($row) => optional($row->guardian)->mobile ?? 'N/A')
                ->addColumn('approved_date', fn($row) => optional($row->approved_date)->format('d-m-Y') ?? 'N/A')
                ->addColumn('application_date', fn($row) => optional($row->created_at)->format('d-m-Y') ?? 'N/A')
                ->addColumn('withdrawal_wef', fn($row) => optional($row->withdrawal_wef)->format('d-m-Y') ?? 'N/A')
                ->addColumn('last_day_at', fn($row) => optional($row->last_day_at)->format('d-m-Y') ?? 'N/A')
                ->addColumn('last_invoice_paid_at', function ($row) {
                    $last = StudentInvoice::with('fee_period')
                        ->where('student_id', $row->student_id)
                        ->where('is_paid', 1)
                        ->where('bank_payment_status', 'paid')
                        ->latest('created_at')->first();

                    if ($last && $last->fee_period) {
                        $from = $last->fee_period->from_date;
                        $to = $last->fee_period->to_date;
                        return get_month_diff($from, $to) == 1
                            ? get_month_name($from)
                            : get_month_name($from) . ' - ' . get_month_name($to);
                    }

                    return 'N/A';
                })
                ->addColumn('clearance_amount', fn($row) => 'Rs. ' . number_format($row->clearance_amount ?? 0))
                ->addColumn('reason.withdrawal_reason', fn($row) => optional($row->reason)->withdrawal_reason ?? 'N/A')
                ->addColumn('created_by', fn($row) => optional($row->getRelationValue('created_by'))->name ?? 'N/A')
                ->addColumn('status', function ($row) {
                    $status = optional($row->student)->status;
                    return match ($status) {
                        'on_roll' => '<span class="badge bg-success">On Roll</span>',
                        'registered' => '<span class="badge bg-primary">Registered</span>',
                        'left' => '<span class="badge bg-warning">Left</span>',
                        default => '<span class="badge bg-danger">Processing</span>',
                    };
                })
                ->addColumn('action', fn($row) => view('students.withdrawal.withdrawal_form_action', compact('row')))
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // View Load (non-AJAX)
        $branches = Branch::select('id', 'br_name', 'branch_code', 'region_id')->get();
        $sections = Section::select('id', 'section_name')->get();
        $academic_years = AcademicYear::select('id', 'title')->get();

        $classes = (! isSuperAdmin() && ! isHeadOfficeEmp())
            ? BranchClass::where('branch_id', get_branch_id())->with('com_classes:id,class_name')->get()
            : ComClass::select('id', 'class_name')->get();

        return view('students.withdrawal.withdrawal_form_list', compact('classes', 'sections', 'branches', 'academic_years'));
    }

    public function withdrawalRequests(Request $request)
    {
        /*if ($request->ajax()) {

            $withdrawalRequests = StudentWithdrawalRequest::with([
                'reason',
                'guardian',
                'student.active_class.branch_class_sections.com_classes',
                'student.active_class.branch_class_sections.sections',
                'student.branch'
            ])->latest()->get();

            //dd($data);

            return Datatables::of($withdrawalRequests)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('students.withdrawal.withdrawal_requests_list', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);

        }*/

        //if (!$request->ajax()) return view('students.withdrawal.withdrawal_requests_list');
        if ($request->ajax()) {
            $withdrawalRequests = StudentWithdrawalRequest::whereHas('student', function (Builder $query) use ($request) {
                //Filter by student status
                if ($request->status && $request->status != 'all') {
                    if (in_array($request->status, ['on_roll', 'registered', 'left'])) {
                        $query->where('status', $request->status);
                    } else {
                        $query->whereNull('status');
                    }
                }
                //Filter by student name
                if (! empty($request->searchName)) {
                    $query->where('first_name', 'like', $request->searchName . '%')
                        ->orWhere('middle_name', 'like', $request->searchName . '%')
                        ->orWhere('last_name', 'like', $request->searchName . '%');
                }
                //Filter by Branch, Section & Class
                if ($request->branch_id && $request->branch_id > 0) {
                    $query->where('branch_id', $request->branch_id);
                } elseif (! isSuperAdmin() && ! isHeadOfficeEmp() /*!auth()->user()->hasRole('manager-parent-relations')*/) {
                    $query->where('branch_id', get_branch_id());
                }

                if ($request->section_id && $request->section_id > 0) {
                    $query->whereHas('std_fee_package', function ($query) use ($request) {
                        $query->where('section_id', $request->section_id);
                    });
                }

                if ($request->class_id && $request->class_id > 0) {
                    $query->whereHas('active_class.branch_class_sections', function ($query) use ($request) {
                        $query->where('class_id', $request->class_id);
                    });
                }

                // Only include students who have at least one invoice
                $query->whereHas('student_invoices');
            })->with([
                        'reason',
                        'guardian',
                        'student.active_class.branch_class_sections.com_classes',
                        'student.active_class.branch_class_sections.sections',
                        'student.branch'
                    ])->get();

            return DataTables::of($withdrawalRequests)
                ->addIndexColumn()
                ->addColumn('student_name', function ($row) {
                    $studentInfo = Student::where('id', $row['student_id'])->first();
                    return (! empty($studentInfo->first_name)) ? $studentInfo->first_name . ' ' . $studentInfo->middle_name . ' ' . $studentInfo->last_name : '';
                })
                ->addColumn('roll_no', function ($row) {
                    $studentInfo = Student::where('id', $row['student_id'])->first();
                    return (! empty($studentInfo->roll_no)) ? $studentInfo->roll_no : '';
                })
                ->addColumn('class_section', function ($row) {
                    $studentInfo = Student::where('id', $row['student_id'])->with(
                        'active_class.branch_class_sections.com_classes',
                        'active_class.branch_class_sections.sections'
                    )->first();

                    $class_name = isset($studentInfo->active_class->branch_class_sections->com_classes) ? $studentInfo->active_class->branch_class_sections->com_classes->class_name : 'N/A';
                    $section_name = isset($studentInfo->active_class->branch_class_sections->sections) ? $studentInfo->active_class->branch_class_sections->sections->section_name : 'N/A';
                    return $class_name . ' / ' . $section_name;
                })
                ->addColumn('branch', function ($row) {
                    $branchInfo = Branch::where('id', $row['student']['branch_id'])->first();
                    return $branchInfo->branch_code . ' - ' . $branchInfo->br_name;
                })
                ->addColumn('last_day', function ($row) {
                    return date('d-m-Y', strtotime($row['last_day_at_school']));
                })
                ->addColumn('guardian_name', function ($row) {
                    return $row['guardian']['guardian_name'];
                })
                ->addColumn('feedback_message', function ($row) {
                    return $row['feedback_message'];
                })
                ->addColumn('reason', function ($row) {
                    return $row['reason']['withdrawal_reason'];
                })
                ->addColumn('action', function ($row) {
                    return view('students.withdrawal.withdrawal_requests_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $branches = Branch::all();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            $classes = ComClass::all();
            $sections = Section::all();
        }

        return view('students.withdrawal.withdrawal_requests_list', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $reasons = WithdrawalReason::all();
        $academic_years = AcademicYear::all();
        //Get request id if provided in URL
        $requestId = $request->get('request');
        $withdrawalRequest = '';
        if (! empty($requestId)) {
            $withdrawalRequest = StudentWithdrawalRequest::with([
                'reason',
                'guardian.relation',
                'student'
            ])->findOrFail($requestId);
        }
        $branch_id = 0;
        if (! Auth::user()->hasRole('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
            $branch_id = get_branch_id();
        }

        //Show only branch employees if branch person is logged-in, else show all employees
        if ($branch_id != 0) {
            $employees = Employee::where('branch_id', '=', $branch_id)->with(['user'])->get();
        } else {
            $employees = Employee::where([/* Roles */])->with('user')->get();
        }

        $relations = Relation::get();

        return view('students.withdrawal.withdrawal_form')->with([
            'reasons' => $reasons,
            'employees' => $employees,
            'relations' => $relations,
            'withdrawalRequest' => $withdrawalRequest,
            'academic_years' => $academic_years,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation_array = [
            "student_id" => "required|unique:student_withdrawals",
            "withdrawal_reason_id" => "required",
            "application_date" => "required",
            "student_name" => "required",
            //"beneficiary_name" => "required",
            "beneficiary_cnic" => [
                "nullable",
                "regex:/^[0-9]{5}-[0-9]{7}-[0-9]{1}$/"
            ],
            "beneficiary_phone" => [
                "nullable",
                // Accepts 03XXXXXXXXX or +923XXXXXXXXX
                "regex:/^(03[0-9]{9}|\\+923[0-9]{9})$/"
            ],
            "guardian_id" => "required",
            "academic_year_id" => "required",
            //"student_id" => "required"
        ];

        // Custom error messages
        $customMessages = [
            'student_id.required' => 'Student ID is required.',
            'student_id.unique' => 'A withdrawal request has already been submitted for this student.',
            'withdrawal_reason_id.required' => 'Please select a withdrawal reason.',
            'application_date.required' => 'Application date is required.',
            'student_name.required' => 'Student name is required.',
            'guardian_id.required' => 'Please select a guardian.',
            'academic_year_id.required' => 'Please select an academic year.',
            'clearance_amount.required' => 'Clearance amount is required when library clearance is checked.',
            'beneficiary_cnic.regex' => 'Beneficiary CNIC must be in XXXXX-XXXXXXX-X format.',
            'beneficiary_phone.regex' => 'Beneficiary phone must be a valid Pakistani mobile number (03XXXXXXXXX or +923XXXXXXXXX).',
        ];

        if (isset(request()->library_clearance)) {
            $validation_array['clearance_amount'] = 'required';
        }

        $request->validate($validation_array, $customMessages);

        $student = Student::find($request->student_id);

        if ($student->status == 'withdrawn') {
            return redirect()->back()->with('error', 'Student already withdrawn.');
        }

        $checkExisting = StudentWithdrawal::where('student_id', $request->student_id)->first();
        if (isset($checkExisting)) {
            return redirect()->back()->with('error', 'Withdrawal form already submitted for the student.');
        }
        //Get last paid invoice
        $last_paid_invoice = StudentInvoice::where(['student_id' => $request->student_id, 'is_paid' => 1, 'bank_payment_status' => 'paid'])->latest('created_at')->first();

        $input = $request->all();
        $input['created_by'] = auth()->user()->id;
        $input['order_no'] = request()->student_roll_no . 'W';
        $input['library_clearance'] = isset(request()->library_clearance);
        $input['last_invoice_paid_at'] = $last_paid_invoice->paid_date;
        $input['withdrawal_wef'] = Carbon::parse($last_paid_invoice->paid_date)->startOfMonth();
        $input['student_invoice_id'] = $last_paid_invoice->id;
        $input['class_student_id'] = $last_paid_invoice->student->active_class->id;

        //Create withdrawal request
        $withdrawalRecord = StudentWithdrawal::create($input);

        //Make application ID using branch ID
        $applicationId = str_pad($student->branch_id . $withdrawalRecord->id, 10, '0', STR_PAD_LEFT);
        $withdrawalRecord->application_id = $applicationId;
        $withdrawalRecord->save();

        return redirect()->route('student-withdrawal.index')->with('success', 'Withdrawal request added successfully.');
        //return redirect()->back()->with('success', 'Withdrawal submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentWithdrawal  $studentWithdrawal
     * @return \Illuminate\Http\Response
     */
    public function show(StudentWithdrawal $studentWithdrawal)
    {
        //dd($studentWithdrawal);
        $withdrawalInfo = StudentWithdrawal::where('id', $studentWithdrawal->id)->with(['reason', 'student', 'guardian'])->first();
        $studentInfo = Student::where('id', $withdrawalInfo->student_id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch'
        )->first();
        //Get approved by details
        $approvedInfo = Employee::find($withdrawalInfo->approved_by);
        return view('students.withdrawal.withdrawal_details_modal', [
            'withdrawalInfo' => $withdrawalInfo,
            'studentInfo' => $studentInfo,
            'approvedInfo' => $approvedInfo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentWithdrawal  $studentWithdrawal
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentWithdrawal $studentWithdrawal)
    {
        $reasons = WithdrawalReason::all();
        $academic_years = AcademicYear::all();
        // dd($studentWithdrawal->load('student'));

        return view('students.withdrawal.edit_withdrawal_form', [
            'studentWithdrawal' => $studentWithdrawal,
            'reasons' => $reasons,
            'academic_years' => $academic_years,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentWithdrawal  $studentWithdrawal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentWithdrawal $studentWithdrawal)
    {
        $request->validate([
            "student_id" => "required",
            "withdrawal_reason_id" => "required",
            "withdrawal_wef" => "required",
            "application_date" => "required",
            "clearance_amount" => "required",
            "student_name" => "required",
        ]);

        $input = $request->all();
        $input['created_by'] = auth()->user()->id;
        $input['library_clearance'] = isset(request()->library_clearance);

        $studentWithdrawal->update($input);
        return redirect()->back()->with('success', 'Withdrawal updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentWithdrawal  $studentWithdrawal
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentWithdrawal $studentWithdrawal)
    {
        try {
            return $studentWithdrawal->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function auto_withdrawal(Request $request)
    {
        //if (!$request->ajax()) return view('students.withdrawal.auto_withdrawal_list');
        if ($request->ajax()) {
            //$studentWhere = ['status' => 'on_roll'];

            if (get_NWABranchCode() != 0) {
                $studentWhere['branch_id'] = get_NWABranchCode();
            }

            $unpaidStudents = [];
            if (isSuperAdmin() || get_NWABranchCode() != 0) {
                $unpaidStudents = Student::whereNotNull('status')->with([
                    'student_invoices' => function ($query) {
                        $query->where(['is_paid' => 0, 'bank_payment_status' => 'unpaid', 'invoice_frequency' => 'Monthly'])->whereDate('validity_date', '<=', Carbon::now());
                    }
                ])->whereHas('student_invoices', function ($query) {
                    $query->where(['is_paid' => 0, 'bank_payment_status' => 'unpaid', 'invoice_frequency' => 'Monthly'])->whereDate('validity_date', '<=', Carbon::now());
                })->get();
            }

            //dd($unpaidStudents);

            $finalStudents = array();
            foreach ($unpaidStudents as $key => $student) {
                if ($student->student_invoices->count() >= 3) {
                    array_push($finalStudents, $student->id);
                }
            }

            //dd($finalStudents);

            $data = Student::studentListingQuery($request, 0, ['student_invoices'], $finalStudents);
            if (! count($finalStudents)) {
                $data = [];
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    //dd($row);
                    return view('students.student_image_tr', ['row' => $row]);
                })
                ->addColumn('reg_roll_no', function ($row) {
                    return ! empty($row->roll_no) ? $row->roll_no : $row->registration_no;
                })
                ->addColumn('class_section', function ($row) {
                    $class_name = isset($row['active_class']['branch_class_sections']['com_classes']) ? $row['active_class']['branch_class_sections']['com_classes']['class_name'] : 'N/A';
                    $section_name = isset($row['active_class']['branch_class_sections']['sections']) ? $row['active_class']['branch_class_sections']['sections']['section_name'] : 'N/A';
                    return $class_name . ' / ' . $section_name;
                })
                ->addColumn('action', function ($row) {
                    return view('students.withdrawal.auto_withdrawal_action', ['row' => $row]);
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'on_roll') {
                        return '<span class="badge bg-success">On Roll</span>';
                    } else if ($row->status == 'registered') {
                        return '<span class="badge bg-primary">Registered</span>';
                    } else if ($row->status == 'left') {
                        return '<span class="badge bg-warning">Left</span>';
                    } else {
                        return '<span class="badge bg-danger">Processing</span>';
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $branches = Branch::all();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            $classes = ComClass::all();
            $sections = Section::all();
        }

        return view('students.withdrawal.auto_withdrawal_list', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
        ]);
    }

    public function updateAutoWithdrawalStudents(Request $request)
    {
        $request->validate([
            "student_id" => "required",
            "new_status" => "required|in:on_roll,registered,processing,left"
        ]);

        $studentId = $request->student_id;
        $newStatus = $request->new_status;

        // Find the student with unpaid invoices
        $student = Student::with([
            'student_invoices' => function ($query) {
                $query->where([
                    'is_paid' => 0,
                    'bank_payment_status' => 'unpaid',
                    'invoice_frequency' => 'Monthly'
                ])->whereDate('validity_date', '<=', Carbon::now());
            }
        ])
            ->where('id', $studentId)
            ->whereHas('student_invoices', function ($query) {
                $query->where([
                    'is_paid' => 0,
                    'bank_payment_status' => 'unpaid',
                    'invoice_frequency' => 'Monthly'
                ])->whereDate('validity_date', '<=', Carbon::now());
            })
            ->first();

        if ($student) {
            $student->status = $newStatus;
            $student->save();

            // If student status is changed to 'left', deactivate their fee package
            if ($newStatus === 'left') {
                $studentFeePackage = StudentFeePackage::where([
                    'student_id' => $studentId,
                    'is_valid' => 1
                ])->first();

                if ($studentFeePackage) {
                    $studentFeePackage->update([
                        'is_valid' => 0,
                        'active_till' => Carbon::now()
                    ]);
                }
            }

            Session::flash('success', 'Student has been marked as ' . $newStatus . ' successfully.');
            return response()->json(['code' => 200, 'status' => 'success']);
        } else {
            Session::flash('error', 'No unpaid student found or student does not meet the criteria.');
            return response()->json(['code' => 404, 'status' => 'error']);
        }
    }

    public function createWithdrawalForm(Request $request)
    {
        // Optimize database queries for better performance
        $this->optimizeQueries();

        $type = $request->type;
        $withdrawalId = $request->id;
        $showFormInfo = false;

        //If no student if is passed, show blank form
        if (empty($withdrawalId)) {
            $data = [''];
            $withdrawalInfo = null;
            $studentInfo = null;
            $guardianInfo = null;
            $branchInfo = null;
        } else {
            $showFormInfo = true;

            // Use caching to improve performance for repeated requests
            $cacheKey = "withdrawal_form_{$withdrawalId}";
            $cachedData = cache()->get($cacheKey);

            if ($cachedData && $type == 'modal') {
                // Return cached data for modal views (faster)
                return $this->renderForm(
                    $type,
                    $showFormInfo,
                    $cachedData['withdrawalInfo'],
                    $cachedData['studentInfo'],
                    $cachedData['guardianInfo'],
                    $cachedData['branchInfo']
                );
            }

            // Optimized: Single query with only necessary fields and relationships
            $withdrawalInfo = StudentWithdrawal::select([
                'id', 'application_date', 'last_day_at', 'last_invoice_paid_at',
                'library_clearance', 'clearance_amount', 'beneficiary_name',
                'beneficiary_cnic', 'beneficiary_postal_address', 'beneficiary_phone',
                'cheque_number', 'cheque_date', 'security_amount'
            ])
            ->where('id', $withdrawalId)
            ->with([
                'reason:id,withdrawal_reason',
                'student:id,roll_no,first_name,last_name,admission_wef,branch_id,state_id',
                'student.branch:id,branch_code,br_name',
                'student.state:id,state_name',
                'student.active_class:id,student_id,branch_class_section_id',
                'student.active_class.branch_class_sections:id,class_id,section_id',
                'student.active_class.branch_class_sections.com_classes:id,class_name',
                'student.active_class.branch_class_sections.sections:id,section_name',
                'guardian:id,guardian_name',
                'guardian.relation:id,relation_name'
            ])
            ->first();

            if ($withdrawalInfo && $withdrawalInfo->student) {
                $studentInfo = $withdrawalInfo->student;
                $branchInfo = $studentInfo->branch;
                $guardianInfo = $withdrawalInfo->guardian;
            } else {
                $studentInfo = null;
                $branchInfo = null;
                $guardianInfo = null;
            }

            // Cache the data for future modal requests (cache for 5 minutes)
            if ($type == 'modal') {
                cache()->put($cacheKey, [
                    'withdrawalInfo' => $withdrawalInfo,
                    'studentInfo' => $studentInfo,
                    'guardianInfo' => $guardianInfo,
                    'branchInfo' => $branchInfo
                ], 300);
            }
        }

        return $this->renderForm($type, $showFormInfo, $withdrawalInfo, $studentInfo, $guardianInfo, $branchInfo);
    }

    /**
     * Helper method to render the form (modal or PDF)
     */
    private function renderForm($type, $showFormInfo, $withdrawalInfo, $studentInfo, $guardianInfo, $branchInfo)
    {
        // Optimize memory usage by garbage collection before PDF generation
        if ($type != 'modal') {
            gc_collect_cycles();

            // Set memory limit for PDF generation
            ini_set('memory_limit', '512M');
            ini_set('max_execution_time', 120);
        }

        $viewData = [
            'showFormInfo' => $showFormInfo,
            'withdrawalInfo' => $withdrawalInfo,
            'studentInfo' => $studentInfo,
            'guardianInfo' => $guardianInfo,
            'branchInfo' => $branchInfo,
            'type' => $type
        ];

        if ($type == 'modal') {
            return view('students.withdrawal.withdrawal_print_form_modal', $viewData);
        } else {
            // Optimize PDF generation with memory and performance settings
            $pdf = Pdf::loadView('students.withdrawal.withdrawal_print_form_pdf', $viewData);

            // Set PDF options for better performance
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'isPhpEnabled' => false,
                'memoryLimit' => '256M',
                'tempDir' => storage_path('app/temp'),
                'chroot' => public_path(),
                'logOutputFile' => storage_path('logs/pdf.log'),
                'defaultFont' => 'Arial',
                'dpi' => 96,
                'defaultPaperSize' => 'a4',
                'isFontSubsettingEnabled' => true
            ]);

            // Clear view data from memory after PDF generation
            unset($viewData);

            return $pdf->download('StudentWithdrawalForm.pdf');
        }
    }

    /**
     * Optimize database queries for better performance
     */
    private function optimizeQueries()
    {
        // Enable query logging for debugging (remove in production)
        if (config('app.debug')) {
            \DB::enableQueryLog();
        }

        // Set database connection timeout
        \DB::statement('SET SESSION wait_timeout=60');
        \DB::statement('SET SESSION interactive_timeout=60');
    }

    public function createWithdrawalCancellation(Request $request)
    {

        //dd( $request->all() );
        $withdrawalId = $request->id;

        $withdrawalInfo = StudentWithdrawal::where('id', $withdrawalId)->with(['reason', 'student', 'guardian'])->first();
        $studentInfo = Student::where('id', $withdrawalInfo->student_id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch'
        )->first();

        //Get all employees
        $branch_id = 0;
        if (! Auth::user()->hasRole('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
            $branch_id = get_branch_id();
        }

        //Show only branch employees if branch person is logged-in, else show all employees
        if ($branch_id != 0) {
            $employees = Employee::where('branch_id', '=', $branch_id)->with(['user'])->get();
        } else {
            $employees = Employee::where([/* Roles */])->with('user')->get();
        }

        return view('students.withdrawal.withdrawal_cancellation_form_modal', [
            'withdrawalInfo' => $withdrawalInfo,
            'studentInfo' => $studentInfo,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWithdrawalCancellation(Request $request)
    {
        $requestArray = $request->validate([
            "cancellation_by" => "required",
            "cancellation_reason" => "required",
            "cancellation_date" => "required",
            "cancellation_approved_by" => "required",
            "cancellation_approved_remarks" => "required"
        ]);

        //Update withdrawal record with cancellation info
        $studentWithdrawalRecord = StudentWithdrawal::find($request->withdrawal_record_id);
        //Get student id from withdrawal record
        $studentId = $studentWithdrawalRecord->student_id;

        //Update withdrawal record
        $studentWithdrawalRecord->update($requestArray);

        //Remove student withdrawal approval status
        $studentWithdrawalRecord->approved_by = null;
        $studentWithdrawalRecord->save();
        //$studentWithdrawalRecord->delete();

        //Find and mark student status to left
        $studentRecord = Student::find($studentId);
        $studentRecord->status = 'on_roll';
        $studentRecord->save();

        Session::flash('success', 'Withdrawal canceled successfully & student marked as ON ROLL!');
        return redirect()->back();
    }

    public function createWithdrawalApproval(Request $request)
    {

        //dd( $request->all() );
        $withdrawalId = $request->id;

        $withdrawalInfo = StudentWithdrawal::where('id', $withdrawalId)->with(['reason', 'student', 'guardian'])->first();
        $studentInfo = Student::where('id', $withdrawalInfo->student_id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch'
        )->first();

        //Get all employees
        $branch_id = 0;
        if (! Auth::user()->hasRole('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
            $branch_id = get_branch_id();
        }

        //Show only branch employees if branch person is logged-in, else show all employees
        if ($branch_id != 0) {
            $employees = Employee::where('branch_id', '=', $branch_id)->with(['user'])->get();
        } else {
            $employees = Employee::where([/* Roles */])->with('user')->get();
        }

        return view('students.withdrawal.withdrawal_approval_form_modal', [
            'withdrawalInfo' => $withdrawalInfo,
            'studentInfo' => $studentInfo,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWithdrawalApproval(Request $request)
    {
        $requestArray = $request->validate([
            "approved_by" => "required",
            "approved_date" => "required",
            "approval_remarks" => "required"
        ]);

        //Update withdrawal record with cancellation info
        $studentWithdrawalRecord = StudentWithdrawal::find($request->withdrawal_record_id);
        //Get student id from withdrawal record
        $studentId = $studentWithdrawalRecord->student_id;

        //Update withdrawal record
        $studentWithdrawalRecord->update($requestArray);

        //$studentWithdrawalRecord->delete();

        //Find and mark student status to left
        $studentRecord = Student::find($studentId);
        $studentRecord->status = 'left';
        $studentRecord->save();

        // Inactivate student's fee package
        $studentFeePackage = StudentFeePackage::where([
            'student_id' => $studentId,
            'is_valid' => 1
        ])->first();

        if ($studentFeePackage) {
            $studentFeePackage->update([
                'is_valid' => 0,
                'active_till' => Carbon::now()
            ]);
        }

        Session::flash('success', 'Withdrawal approved successfully & student marked as LEFT!');
        return redirect()->back();
    }

    public function createWithdrawalRefund(Request $request)
    {
        //dd( $request->all() );
        $withdrawalId = $request->id;

        $withdrawalInfo = StudentWithdrawal::where('id', $withdrawalId)->with(['reason', 'student', 'guardian'])->first();
        $studentInfo = Student::where('id', $withdrawalInfo->student_id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch'
        )->first();

        //Calculate refundable security amount
        $studentInvoices = StudentInvoice::where(
            'student_id',
            $withdrawalInfo->student_id
        )->with([
                    'student',
                    'student_fee_package.fee_package',
                    'student_fee_package.fee_package.fee_package_type',
                    'student_fee_package.fee_concession.fee_concession_type',
                    'student_fee_package.academic_year',
                    'student_fee_package.com_class',
                    'student_fee_package.section',
                    'student_invoice_items.fee_charges.fee_charges_type',
                    'invoice_type',
                    'payment_source',
                    'promo.promo_type',
                    'fee_period'
                ])->get();

        $securityFees = 0;
        foreach ($studentInvoices as $invoice) {
            $feesCalc = calculate_total_price_by_invoice($invoice);
            $securityFees += $feesCalc['invoices_charges']['SD'];
        }

        //Get all employees
        $branch_id = 0;
        if (! Auth::user()->hasRole('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
            $branch_id = get_branch_id();
        }

        //Show only branch employees if branch person is logged-in, else show all employees
        if ($branch_id != 0) {
            $employees = Employee::where('branch_id', '=', $branch_id)->with(['user'])->get();
        } else {
            $employees = Employee::where([/* Roles */])->with('user')->get();
        }

        return view('students.withdrawal.withdrawal_refund_form_modal', [
            'withdrawalInfo' => $withdrawalInfo,
            'studentInfo' => $studentInfo,
            'employees' => $employees,
            'securityFees' => $securityFees
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeWithdrawalRefund(Request $request)
    {
        $request->validate([
            "cheque_number" => "required",
            "beneficiary_name" => "required",
            "cheque_date" => "required"
        ]);

        //Update withdrawal record with cancellation info
        $studentWithdrawalRecord = StudentWithdrawal::find($request->withdrawal_record_id);
        //$studentWithdrawalRecord->application_status = 'Complete';
        //Update withdrawal record
        $studentWithdrawalRecord->update($request->all());

        Session::flash('success', 'Withdrawal refund status successfully updated!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\ $request
     * @return \Illuminate\Http\Response
     */
    public function withdrawalRequestsDelete(Request $request)
    {
        try {
            $studentWithdrawalRequestRecord = StudentWithdrawalRequest::findOrFail($request->id);
            return $studentWithdrawalRequestRecord->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
