<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\State;
use App\Models\Branch;
use App\Models\Section;
use App\Models\Student;
use App\Models\ComClass;
use App\Models\Employee;
use App\Models\FeePackage;
use App\Models\BranchClass;
use App\Models\AcademicYear;
use App\Models\ClassStudent;
use Illuminate\Http\Request;
use App\Models\StudentInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\GradeBookHistory;
use App\Models\NetworkAssociate;
use App\Models\StudentFeePackage;
use App\Models\BranchClassSection;
use App\Models\StudentTransferCase;
use App\Models\StudentBehaviourSkill;
use App\Models\StudentTransferReason;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;

class StudentTransferCaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = StudentTransferCase::whereHas('student', function (Builder $query) use ($request) {
                //Filter by student status
                if ($request->status && $request->status != 'all') {
                    if (in_array($request->status, ['on_roll', 'registered', 'left']))
                        $query->where('status', $request->status);
                    else
                        $query->whereNull('status');
                }
                //Filter by student name
                if (!empty($request->searchName)) {
                    $query->where('first_name', 'like', $request->searchName . '%')
                        ->orWhere('middle_name', 'like', $request->searchName . '%')
                        ->orWhere('last_name', 'like', $request->searchName . '%');
                }
                //Filter by Branch, Section & Class
                if ($request->branch_id && $request->branch_id > 0) {
                    $query->where('to_branch', $request->branch_id);
                }
                // elseif (!isHeadOfficeEmp() && !isSuperAdmin()/*!auth()->user()->hasRole('manager-parent-relations')*/) {
                //     $query->where('to_branch', get_branch_id());
                // }
                elseif (!isHeadOfficeEmp() && !isSuperAdmin()) {

                    $query->where('to_branch', get_branch_id())->orWhere('from_branch', get_branch_id());
                }

                if ($request->academic_year_id && $request->academic_year_id > 0) {
                    $query->where('academic_year_id', $request->academic_year_id);
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
            })->with([
                        'reason',
                        'student',
                        'created_by',
                        'approved_by',
                        'from_branch_model',
                        'to_branch_model',
                        'academic_year'
                    ])->get();
            //dd($data->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('academic_year', function ($row) {
                    return $row->academic_year->title;
                })
                ->addColumn('application_id', function ($row) use ($request) {
                    return view('students.transfer_case.transfer_app_id_link', ['row' => $row]);
                })
                ->addColumn('student_name', function ($row) {
                    $studentInfo = Student::where('id', $row['student']['id'])->first();
                    if (!isHeadOfficeEmp() && !isSuperAdmin()) {

                        return $studentInfo->first_name . ' ' . $studentInfo->middle_name . ' ' . $studentInfo->last_name;
                    } else {
                        $row = $studentInfo;
                        return view('students.student_image_tr', ['row' => $row]);
                    }
                })
                ->addColumn('from_branch_code', function ($row) {
                    $branchInfo = Branch::where('id', $row['from_branch'])->first();
                    return $branchInfo->branch_code;
                })
                ->addColumn('from_branch_name', function ($row) {
                    $branchInfo = Branch::where('id', $row['from_branch'])->first();
                    return $branchInfo->br_name . ' (' . $branchInfo->branch_code . ')';
                })
                ->addColumn('to_branch_code', function ($row) {
                    $branchInfo = Branch::where('id', $row['to_branch'])->first();
                    return $branchInfo->branch_code;
                })
                ->addColumn('to_branch_name', function ($row) {
                    $branchInfo = Branch::where('id', $row['to_branch'])->first();
                    return $branchInfo->br_name . ' (' . $branchInfo->branch_code . ')';
                })
                ->addColumn('action', function ($row) {
                    return view('students.transfer_case.transfer_form_action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $branches = Branch::all();
        $academic_years = AcademicYear::all();
        if (!isSuperAdmin() && !isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            $classes = ComClass::all();
            $sections = Section::all();
        }

        return view('students.transfer_case.transfer_case_list', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
            'academic_years' => $academic_years
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = Branch::all();
        $states = State::all();
        $academic_years = AcademicYear::all();
        $transfer_reasons = StudentTransferReason::all();
        return view('students.transfer_case.create_student_transfer_case', ['branches' => $branches, 'transfer_reasons' => $transfer_reasons, 'states' => $states, 'academic_years' => $academic_years]);
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
        $validation_array = [
            "student_id" => "required",
            "transfer_reason_id" => "required",
            "request_date" => "required",
            'branch_to' => 'required'
        ];

        $request->validate($validation_array);
        $previousRecord = StudentTransferCase::where(['student_id' => $request->student_id, 'status' => 'PENDING'])->first();
        if ($previousRecord) {
            return redirect()->back()->with('error', 'Student has transfer case pending');
        }
        $student = Student::find($request->student_id);
        if ($student->status == 'left') {
            return redirect()->back()->with('error', 'Student left the school');
        }
        $last_paid_invoice = StudentInvoice::where(['student_id' => $request->student_id, 'is_paid' => 0, 'bank_payment_status' => 'unpaid'])->latest('created_at')->first();
        if (isset($last_paid_invoice))
            return redirect()->back()->with('error', 'Fee is not paid');
        //Get last paid invoice

        $input = $request->all();
        $input['created_by'] = auth()->user()->id;
        $input['to_branch'] = $request->branch_to;
        $input['status'] = 'PENDING';
        //Create withdrawal request
        $transferCase = StudentTransferCase::create($input);

        //Make application ID using branch ID
        $applicationId = str_pad($student->branch_id . $transferCase->id, 10, '0', STR_PAD_LEFT);
        $transferCase->application_id = $applicationId;
        $transferCase->save();

        return redirect()->back()->with('success', 'Transfer Case submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentTransferCase  $studentTransferCase
     * @return \Illuminate\Http\Response
     */
    public function show(StudentTransferCase $studentTransferCase)
    {
        $transferInfo = StudentTransferCase::where('id', $studentTransferCase->id)->with(['reason', 'student'])->first();
        $studentInfo = Student::where('id', $transferInfo->student_id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch'
        )->first();
        //Get approved by details
        $approvedInfo = Employee::where('user_id', $transferInfo->approved_by)->with('user')->first();
        if (!$approvedInfo) {
            $approvedInfo = NetworkAssociate::where('user_id', $transferInfo->approved_by)->with('user')->first();
        }
        // dd($approvedInfo);
        return view('students.transfer_case.transfer_details_modal', [
            'transferInfo' => $transferInfo,
            'studentInfo' => $studentInfo,
            'approvedInfo' => $approvedInfo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentTransferCase  $studentTransferCase
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentTransferCase $studentTransferCase)
    {
        // dd($studentTransferCase->toArray());
        $student = $studentTransferCase->student;
        $academic_years = AcademicYear::all();
        $branches = Branch::all();
        $states = State::all();
        $transfer_reasons = StudentTransferReason::all();
        return view('students.transfer_case.create_student_transfer_case', ['student' => $student, 'branches' => $branches, 'transfer_reasons' => $transfer_reasons, 'states' => $states, 'academic_years' => $academic_years, 'studentTransferCase' => $studentTransferCase]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentTransferCase  $studentTransferCase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentTransferCase $studentTransferCase)
    {
        $validation_array = [
            "student_id" => "required",
            "transfer_reason_id" => "required",
            "request_date" => "required",
            'branch_to' => 'required'
        ];

        $request->validate($validation_array);
        $input = $request->all();
        if ($studentTransferCase->status == "PENDING") {
            $input['to_branch'] = $request->branch_to;
            $studentTransferCase->update($input);
            return redirect()->back()->with('success', 'Transfer updated successfully.');
        }
        return redirect()->back()->with('error', 'Transfer cannot be updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentTransferCase  $studentTransferCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentTransferCase $studentTransferCase)
    {
        try {
            $studentTransferCase->delete();
            return response()->json([
                'code' => 200,
                'message' => 'Transfer case deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to delete transfer case.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function createTransferCancellation(Request $request)
    {
        $transferId = $request->id;

        $transferInfo = StudentTransferCase::where('id', $transferId)->with(['reason', 'student'])->first();
        $studentInfo = Student::where('id', $transferInfo->student_id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch'
        )->first();

        //Get all employees
        $branch_id = 0;
        if (!\Auth::user()->hasRole('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
            $branch_id = get_branch_id();
        }

        //Show only branch employees if branch person is logged-in, else show all employees
        if ($branch_id != 0) {
            $employees = Employee::where('branch_id', '=', $branch_id)->with(['user'])->get();
        } else {
            $employees = Employee::where([/* Roles */])->with('user')->get();
        }

        return view('students.transfer_case.transfer_cancellation', [
            'transferCase' => $transferInfo,
            'studentInfo' => $studentInfo,
            'employees' => $employees,
        ]);
    }

    public function storeTransferCancellation(Request $request)
    {
        $requestArray = $request->validate([
            "cancelled_by" => "required",
            "cancel_reason" => "required",
            "cancellation_date" => "required",
        ]);

        $studentTransferRecord = StudentTransferCase::find($request->transfer_record_id);

        //Update withdrawal record
        $studentTransferRecord->update(['status' => 'CANCELLED']);
        $studentTransferRecord->update($requestArray);
        Session::flash('success', 'Transfer case canceled successfully');
        return redirect()->back();
    }

    public function storeTransferApproval(Request $request)
    {
        // dd($request->all());
        $requestArray = $request->validate([
            "class_id" => "required",
            "section_id" => "required",
            "transfer_record_id" => "required",
            "approved_by" => "required",
            "approved_date" => "required",
            "approval_remarks" => "required"
        ]);

        $studentTransferRecord = StudentTransferCase::find($request->transfer_record_id);
        if (!$studentTransferRecord) {
            return redirect()->back()->with('error', 'Transfer record not found.');
        }

        $studentId = $studentTransferRecord->student_id;
        $studentTransferRecord->update($requestArray);
        $studentTransferRecord->update(['status' => 'APPROVED']);

        $academic_info = ClassStudent::where([
            'student_id' => $studentId,
            'is_valid' => 1
        ])->with([
            'students',
            'academic_years',
            'branch_class_sections.com_classes',
            'branch_class_sections.sections'
        ])->first();

        if (!$academic_info) {
            return redirect()->back()->with('error', 'Student\'s academic info not found.');
        }

        $branch_class_section = BranchClassSection::find($request->section_id);
        if (!$branch_class_section) {
            return redirect()->back()->with('error', 'Branch class section not found.');
        }

        // Null safety checks for nested relationships
        if (
            !$academic_info->academic_years ||
            !$academic_info->branch_class_sections ||
            !$academic_info->branch_class_sections->com_classes ||
            !$academic_info->branch_class_sections->sections
        ) {
            return redirect()->back()->with('error', 'Student academic info is incomplete. Please check class, section, or academic year assignments.');
        }

        // Try to create gradebook history, but don't fail the entire transfer if it fails
        $gradebookCreated = $this->createGradeBookHistory($academic_info, $studentId);
        if (!$gradebookCreated) {
            \Log::warning("Student transfer: GradeBookHistory creation failed for student {$studentId}, but transfer will continue");
            // Don't return here - continue with the transfer process
        }

        $studentRecord = Student::find($studentId);
        if (!$studentRecord) {
            return redirect()->back()->with('error', 'Student record not found.');
        }
        $studentRecord->branch_id = $studentTransferRecord->to_branch;
        $studentRecord->from_branch = $studentTransferRecord->from_branch;
        $studentRecord->transfer_status = '1';
        $studentRecord->save();

        $academic_info->branch_class_section_id = $branch_class_section->id;
        $academic_info->save();

        $fee_package = FeePackage::where(['branch_id' => $studentTransferRecord->to_branch])
            ->whereHas('fee_package_type', function ($query) {
                $query->where('name', 'Monthly');
            })->first();

        if (!$fee_package) {
            return redirect()->back()->with('error', 'Fee package not found for the selected branch.');
        }

        $input = [
            "fee_package_id" => $fee_package->id,
            "fee_concession_id" => $request->fee_concession_id,
            "academic_year_id" => $academic_info->academic_years->id,
            "com_class_id" => $academic_info->branch_class_sections->com_classes->id,
            "section_id" => $academic_info->branch_class_sections->sections->id,
            "student_id" => $studentId
        ];

        StudentFeePackage::where(['student_id' => $studentId, 'is_valid' => 1])->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
        StudentFeePackage::create($input);

        // Show appropriate success message based on whether gradebook was created
        if ($gradebookCreated) {
            Session::flash('success', 'Transfer case approved successfully. Student gradebook history has been created.');
        } else {
            Session::flash('success', 'Transfer case approved successfully. Note: Student gradebook history could not be created - please check with administrators.');
        }
        
        return redirect()->back();
    }

    public function createTransferApproval(Request $request)
    {
        //dd( $request->all() );
        $transferId = $request->id;
        $transferCase = StudentTransferCase::where('id', $transferId)->with(['reason', 'student', 'to_branch_model'])->first();
        $studentInfo = Student::where('id', $transferCase->student_id)->with(
            'active_class.branch_class_sections.com_classes',
            'active_class.branch_class_sections.sections',
            'branch'
        )->first();

        $class = $studentInfo->active_class->branch_class_sections->com_classes;
        $branch_class_sections = BranchClassSection::where(['branch_id' => $transferCase->to_branch, 'class_id' => $class->id])->with('sections')->get();

        //Get all employees
        $branch_id = 0;
        if (!\Auth::user()->hasRole('super_admin|finance-manager|manager-parent-relations|senior-finance-manager|manager-parent-relations|senior-manager-business-development|manager-business-development|deputy-director|ceo|manager-quality-assurance|manager-legal-affairs-litigation|senior-marketing-manager|marketing-manager')) {
            $branch_id = get_branch_id();
        }

        //Show only branch employees if branch person is logged-in, else show all employees
        if ($branch_id != 0) {
            $employees = Employee::where('branch_id', '=', $branch_id)->with(['user'])->get();
        } else {
            $employees = Employee::where([/* Roles */])->with('user')->get();
        }

        return view('students.transfer_case.transfer_approval_modal', [
            'transferCase' => $transferCase,
            'studentInfo' => $studentInfo,
            'employees' => $employees,
            'class' => $class,
            'branch_class_sections' => $branch_class_sections
        ]);
    }

    public function printTransferCase(Request $request)
    {
        $type = $request->type;
        $withdrawalId = $request->id;
        $showFormInfo = false;

        //If no student if is passed, show blank form
        if (empty($withdrawalId)) {
            $data = [''];
        } else {
            $showFormInfo = true;
            $withdrawalInfo = StudentTransferCase::where('id', $withdrawalId)->with(['reason', 'student', 'to_branch_model.region', 'from_branch_model'])->first();
            $studentInfo = Student::where('id', $withdrawalInfo->student_id)->with(
                'active_class.branch_class_sections.com_classes',
                'active_class.branch_class_sections.sections',
                'branch',
                'state',
                'guardian'
            )->first();

            // dd($withdrawalInfo->toArray());
            //Get branch details
            $branchInfo = Branch::where('id', $studentInfo->branch_id)->with('region')->first();
        }

        if ($type == 'modal') {
            return view('students.transfer_case.transfer_print_form_modal', ['showFormInfo' => $showFormInfo, 'transferInfo' => $withdrawalInfo, 'studentInfo' => $studentInfo, 'branchInfo' => $branchInfo, 'type' => $type]);
        } else {
            $pdf = PDF::loadView('students.transfer_case.transfer_print_form_pdf', ['type' => $type, 'showFormInfo' => $showFormInfo]);
            return $pdf->download('StudentTransferCaseForm.pdf');
        }
    }

    /**
     * @param $academic_info
     * @param $studentId
     * @return mixed
     */
    protected function createGradeBookHistory($academic_info, $studentId)
    {
        // Use the actual academic year from the student's academic info instead of hardcoded value
        $academic_year_id = $academic_info->academic_years->id ?? null;
        
        if (!$academic_year_id) {
            \Log::warning("Student transfer: No academic year found for student {$studentId}");
            return false;
        }

        $studentBehaviourSkill = StudentBehaviourSkill::where('academic_year_id', $academic_year_id)
            ->where('branch_id', $academic_info->branch_class_sections->branch_id)
            ->where('class_id', $academic_info->branch_class_sections->class_id)
            ->where('section_id', $academic_info->branch_class_sections->section_id)
            ->first();

        if (!$studentBehaviourSkill) {
            \Log::warning("Student transfer: No StudentBehaviourSkill found for student {$studentId} with academic_year_id: {$academic_year_id}, branch_id: {$academic_info->branch_class_sections->branch_id}, class_id: {$academic_info->branch_class_sections->class_id}, section_id: {$academic_info->branch_class_sections->section_id}");
            
            // Try to create the StudentBehaviourSkill if it doesn't exist
            $studentBehaviourSkill = $this->createStudentBehaviourSkill($academic_info, $academic_year_id);
            if (!$studentBehaviourSkill) {
                return false;
            }
        }

        try {
            $result = GradeBookHistory::create([
                'branch_class_section_id' => $academic_info->branch_class_sections->id,
                'student_id' => $studentId,
                'student_behaviour_skill_id' => $studentBehaviourSkill->id,
            ]);

            \Log::info("Student transfer: GradeBookHistory created successfully for student {$studentId} with ID: {$result->id}");
            return $result;
        } catch (\Exception $e) {
            \Log::error("Student transfer: Failed to create GradeBookHistory for student {$studentId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create StudentBehaviourSkill if it doesn't exist
     * @param $academic_info
     * @param $academic_year_id
     * @return mixed
     */
    protected function createStudentBehaviourSkill($academic_info, $academic_year_id)
    {
        try {
            $studentBehaviourSkill = StudentBehaviourSkill::create([
                'academic_year_id' => $academic_year_id,
                'branch_id' => $academic_info->branch_class_sections->branch_id,
                'class_id' => $academic_info->branch_class_sections->class_id,
                'section_id' => $academic_info->branch_class_sections->section_id,
                'term_id' => null, // Set to null as per the model structure
                'subject_id' => null, // Set to null as per the model structure
            ]);

            \Log::info("Student transfer: Created new StudentBehaviourSkill with ID: {$studentBehaviourSkill->id}");
            return $studentBehaviourSkill;
        } catch (\Exception $e) {
            \Log::error("Student transfer: Failed to create StudentBehaviourSkill: " . $e->getMessage());
            return false;
        }
    }
}
