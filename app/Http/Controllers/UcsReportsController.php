<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\SiblingInformation;
use Carbon\Carbon;
use App\Models\State;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Company;
use App\Models\Section;
use App\Models\Student;
use App\Models\TaxType;
use App\Models\ComClass;
use App\Models\Guardian;
use App\Models\FeePeriod;
use App\Models\BranchClass;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Designation;
use App\Models\DesignationType;
use App\Models\Nationality;
use App\Models\Religion;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\StudentInvoice;
use App\Models\StudentPayment;
use App\Models\Payroll;
use App\Models\Asset;
use App\Models\NetworkAssociate;
use App\Models\PromotionRequest;
use Log;
use Yajra\DataTables\DataTables;
use App\Models\FamilyInformation;
use App\Exports\ExportPaidStudent;
use App\Exports\ExportGeneralLedger;
use App\Models\BranchClassSection;
use App\Models\StudentTransferCase;
use App\Exports\ExportTransferInOut;
use App\Exports\ExportUnpaidStudent;
use App\Exports\ExportStudentConcession;
use App\Exports\ExportEmployee;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StudentBehaviourSkill;
use App\Exports\ExportStudentRelation;

use function PHPUnit\Framework\isNull;

use App\Models\StudentPromotionRequest;
use Illuminate\Database\QueryException;
use App\Exports\ExportStudentPromortions;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Models\StudentBehaviourSkillRemark;
use App\Models\StudentArrearsHistory;

class UcsReportsController extends Controller
{
    public function UnpaidStudentReport(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = StudentInvoice::paid_unpaid_invoices_report('unpaid');
                $data = StudentInvoice::paid_unpaid_invoice_filteration($request, $data);

            // Exclude students with processing, registered, or empty status
                $data = $data->whereHas('student', function ($query) {
                    $query->where(function ($q) {
                        $q->where('status', '!=', 'processing')
                          ->where('status', '!=', 'registered')
                          ->where('status', '!=', '')
                          ->whereNotNull('status');
                    });
                });

            // Remove the arrears subquery to avoid duplicate column issues

            // Order by student_id and due_date to group similar records together
                $data = $data->orderBy('student_invoices.student_id')
                ->orderBy('student_invoices.due_date');

                return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('state', function ($row) {
                    return isset($row['student']['state']['state_name']) ? $row['student']['state']['state_name'] : '';
                })
                ->addColumn('region', function ($row) {
                    return isset($row['student']['branch']['region']) ? $row['student']['branch']['region']['region_name'] : '';
                })
                ->addColumn('branch_code', function ($row) {
                    return isset($row['student']['branch']['branch_code']) ? $row['student']['branch']['branch_code'] : '';
                })
                ->addColumn('br_name', function ($row) {
                    return isset($row['student']['branch']['br_name']) ? $row['student']['branch']['br_name'] : '';
                })
                ->addColumn('invoice_no', function ($row) {
                    return isset($row['invoice_no']) ? view('students.invoice_report_link', ['row' => $row]) : '';
                })
                ->addColumn('issue_date', function ($row) {
                    return isset($row['issue_date']) ? date('d-m-Y', strtotime($row['issue_date'])) : '';
                })
                ->addColumn('due_date', function ($row) {
                    return isset($row['due_date']) ? date('d-m-Y', strtotime($row['due_date'])) : '';
                })
                ->addColumn('validity_date', function ($row) {
                    return isset($row['validity_date']) ? date('d-m-Y', strtotime($row['validity_date'])) : '';
                })
                ->addColumn('fee_month', function ($row) {
                    // Extract month from due date instead of fee period
                    if (isset($row['due_date'])) {
                        return get_month_name($row['due_date']);
                    }
                    return '';
                })
                ->addColumn('full_name', function ($row) {
                    return isset($row['student']) ? view('students.student_image_tr', ['row' => $row['student']]) : '';
                })
                ->addColumn('student_id', function ($row) {
                    $studentID = '';
                    if (isset($row['student'])) {
                        $studentID = $row['student']['registration_no'] ? $row['student']['registration_no'] : $row['student']['roll_no'];
                    }

                    return $studentID;
                })
                ->addColumn('class_name', function ($row) {
                    $className = '';
                    if (isset($row['student']['active_class']['branch_class_sections']['com_classes'])) {
                        $className = $row['student']['active_class']['branch_class_sections']['com_classes']['class_name'];
                    }

                    return $className;
                })
                ->addColumn('section_name', function ($row) {
                    return isset($row['student_fee_package']['section']) ? $row['student_fee_package']['section']['section_name'] : '';
                })
                ->addColumn('academic_year', function ($row) {
                    return isset($row['student_fee_package']['academic_year']['title']) ? $row['student_fee_package']['academic_year']['title'] : '';
                })
                ->addColumn('package_name', function ($row) {
                    return isset($row['student_fee_package']['fee_package']) ? $row['student_fee_package']['fee_package']['package_name'] : '';
                })
                ->addColumn('admission_date', function ($row) {
                    return isset($row['student']['admission_wef']) ? date('d-m-Y', strtotime($row['student']['admission_wef'])) : '';
                })
                ->addColumn('status', function ($row) {
                    $status = $row['student']['status'] ?? '';
                    if ($status) {
                        // Format status with proper styling
                        $statusLabels = [
                            'on_roll' => '<span class="badge bg-success">On Roll</span>',
                            'registered' => '<span class="badge bg-primary">Registered</span>',
                            'left' => '<span class="badge bg-danger">Left</span>',
                            'pass-out' => '<span class="badge bg-info">Pass Out</span>',
                            'processing' => '<span class="badge bg-warning">Processing</span>',
                            'transferred' => '<span class="badge bg-secondary">Transferred</span>'
                        ];
                        return $statusLabels[$status] ?? '<span class="badge bg-light text-dark">' . ucfirst($status) . '</span>';
                    }
                    return '<span class="badge bg-light text-dark">N/A</span>';
                })
                ->rawColumns(['status'])
                ->addColumn('cost', function ($row) {
                    $cost = '';
                    if (isset($row)) {
                        $data = calculate_total_price_by_invoice($row);
                        $cost = isset($data['total']) ? number_format($data['total']) : '';
                    }
                    return $cost;
                })
                ->addColumn('arrears', function ($row) {
                    // Calculate arrears amount directly for this student
                    $studentId = $row['student_id'] ?? null;
                    if ($studentId) {
                        $arrearsAmount = StudentArrearsHistory::where('student_id', $studentId)
                            ->whereNull('cleared_date')
                            ->where('amount', '>', 0)
                            ->sum('amount');
                        return $arrearsAmount > 0 ?  number_format($arrearsAmount) : 'N/A';
                    }
                    return 'N/A';
                })
                ->make(true);
            } catch (\Exception $e) {
                \Log::error('UnpaidStudentReport Error: ' . $e->getMessage(), [
                    'request' => $request->all(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'An error occurred while loading the report. Please try again.',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
        }


        $academic_years = AcademicYear::all();
        $branches = Branch::all();
        $regions = Region::all();
        $states = State::all();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            $classes = ComClass::all();
            $sections = Section::all();
        }

        return view('reports.unpaid_students_list', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
            'regions' => $regions,
            'states' => $states,
            'academic_years' => $academic_years
        ]);
    }

    public function export_unpaid_student()
    {
        return Excel::download(new ExportUnpaidStudent(), 'unpaid_students_report.xlsx');
    }

    public function PaidStudentReport(Request $request)
    {
        try {
            session()->forget('paid_data');

            if ($request->ajax()) {
                // Increase memory and execution time for server
                ini_set('memory_limit', '512M');
                ini_set('max_execution_time', 300);

                // Get pagination parameters
                $limit = $request->input('length', 10);
                $start = $request->input('start', 0);

                // Build optimized query
                $query = StudentInvoice::where('is_paid', 1)
                    ->where('bank_payment_status', 'paid')
                    ->with([
                        'student:id,first_name,last_name,registration_no,roll_no,branch_id',
                        'student.branch:id,br_name,branch_code,state_id,region_id',
                        'student.branch.region:id,region_name',
                        'student.branch.state:id,state_name',
                        'student.active_class:id,student_id,branch_class_section_id',
                        'student.active_class.branch_class_sections:id,class_id,section_id',
                        'student.active_class.branch_class_sections.com_classes:id,class_name',
                        'student.active_class.branch_class_sections.sections:id,section_name',
                        'student_fee_package:id,student_id,academic_year_id,section_id,fee_package_id',
                        'student_fee_package.fee_package:id,package_name',
                        'student_fee_package.academic_year:id,title',
                        'student_fee_package.section:id,section_name',
                        'fee_period:id,from_date,to_date'
                    ]);

                // Apply branch filter for non-admin users
                if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
                    $branch_id = get_branch_id();
                    $query->whereHas('student', function ($q) use ($branch_id) {
                        $q->where('branch_id', $branch_id);
                    });
                }

                // Apply filters
                if ($request->filled('region_id')) {
                    $query->whereHas('student.branch.region', function ($q) use ($request) {
                        $q->where('id', $request->region_id);
                    });
                }

                if ($request->filled('state_id')) {
                    $query->whereHas('student.branch.state', function ($q) use ($request) {
                        $q->where('id', $request->state_id);
                    });
                }

                if ($request->filled('branch_id')) {
                    $query->whereHas('student', function ($q) use ($request) {
                        $q->where('branch_id', $request->branch_id);
                    });
                }

                if ($request->filled('academic_year_id')) {
                    $query->whereHas('student_fee_package', function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                    });
                }

                if ($request->filled('class_id')) {
                    $query->whereHas('student.active_class.branch_class_sections', function ($q) use ($request) {
                        $q->where('class_id', $request->class_id);
                    });
                }

                if ($request->filled('section_id')) {
                    $query->whereHas('student_fee_package', function ($q) use ($request) {
                        $q->where('section_id', $request->section_id);
                    });
                }

                if ($request->filled('paid_date')) {
                    $query->where('paid_date', $request->paid_date);
                }

                // Get total count
                $totalData = $query->count();
                $totalFiltered = $totalData;

                // Get paginated results
                $invoices = $query->offset($start)->limit($limit)->get();

                // Prepare data for DataTables
                $data = [];
                foreach ($invoices as $invoice) {
                    $student = $invoice->student;
                    $branch = $student?->branch;

                    $data[] = [
                        'state' => $branch?->state?->state_name ?? '',
                        'region' => $branch?->region?->region_name ?? '',
                        'branch_code' => $branch?->branch_code ?? '',
                        'br_name' => $branch?->br_name ?? '',
                        'invoice_no' => $invoice->invoice_no ?? '',
                        'invoice_frequency' => $invoice->invoice_frequency ?? '-',
                        'issue_date' => $invoice->issue_date ? date('d-m-Y', strtotime($invoice->issue_date)) : '',
                        'due_date' => $invoice->due_date ? date('d-m-Y', strtotime($invoice->due_date)) : '',
                        'validity_date' => $invoice->validity_date ? date('d-m-Y', strtotime($invoice->validity_date)) : '',
                        'fee_month' => $this->getFeeMonth($invoice->fee_period),
                        'full_name' => $student ? $student->first_name . ' ' . $student->last_name : '',
                        'paid_date' => $invoice->paid_date ? date('d-m-Y', strtotime($invoice->paid_date)) : '',
                        'student_id' => $student?->registration_no ?: $student?->roll_no ?: '',
                        'class_name' => $student?->active_class?->branch_class_sections?->com_classes?->class_name ?? '',
                        'section_name' => $invoice->student_fee_package?->section?->section_name ?? '',
                        'academic_year' => $invoice->student_fee_package?->academic_year?->title ?? '',
                        'package_name' => $invoice->student_fee_package?->fee_package?->package_name ?? '',
                        'cost' => $this->getInvoiceCost($invoice),
                        'arrears' => $this->getStudentArrears($invoice->student_id),
                    ];
                }

                return response()->json([
                    "draw" => intval($request->input('draw')),
                    "recordsTotal" => $totalData,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
            }

            // Load filter data efficiently (only required fields)
            $academic_years = AcademicYear::select('id', 'title', 'active')->get();
            $regions = Region::select('id', 'region_name')->get();
            $states = State::select('id', 'state_name')->get();

            // Optimize branches loading based on user role
            if (! Auth::user()->hasRole('super_admin') && ! isHeadOfficeEmp()) {
                $user = Auth::user();
                $branch_id = get_branch_id();

                if (Auth::user()->hasRole('network_associate')) {
                    $employee = NetworkAssociate::where('user_id', $user->id)->with('branches:id,br_name,branch_code')->first();
                    $branches = $employee?->branches ?? collect();
                } else {
                    $employee = Employee::where('user_id', $user->id)->with('branch:id,br_name,branch_code')->first();
                    $branches = $employee?->branch ? collect([$employee->branch]) : collect();
                }

                $classes = BranchClass::where('branch_id', $branch_id)
                    ->with(['com_classes:id,class_name'])
                    ->get();
                $sections = Section::select('id', 'section_name')->get();
            } else {
                $branches = Branch::select('id', 'br_name', 'branch_code')->get();
                $classes = BranchClass::with(['com_classes:id,class_name'])->get();
                $sections = Section::select('id', 'section_name')->get();
            }

            return view('reports.paid_students_list', [
                'classes' => $classes,
                'sections' => $sections,
                'branches' => $branches,
                'regions' => $regions,
                'states' => $states,
                'academic_years' => $academic_years,
            ]);
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('PaidStudentReport Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a proper error response
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'An error occurred while loading the report. Please try again.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Server error'
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while loading the report.');
        }
    }

    /**
     * Get fee month for display
     */
    private function getFeeMonth($feePeriod)
    {
        if (! $feePeriod || ! $feePeriod->from_date) {
            return 'N/A';
        }

        try {
            if (function_exists('get_month_name') && function_exists('get_month_diff')) {
                if (get_month_name($feePeriod->from_date) == 'February') {
                    return 'February';
                } else {
                    $monthDiff = get_month_diff($feePeriod->from_date, $feePeriod->to_date);
                    $monthName = get_month_name($feePeriod->from_date);
                    return $monthDiff == 1 ? $monthName : $monthName . ' - ' . get_month_name($feePeriod->to_date);
                }
            } else {
                // Fallback if helper functions don't exist
                return date('M Y', strtotime($feePeriod->from_date));
            }
        } catch (Exception $e) {
            return 'N/A';
        }
    }

    /**
     * Get invoice cost safely
     */
    private function getInvoiceCost($invoice)
    {
        try {
            if ($invoice->bank_received_amount !== null) {
                return number_format($invoice->bank_received_amount);
            } elseif ($invoice->paid_amount !== null) {
                return number_format($invoice->paid_amount);
            } else {
                // Try to calculate using helper function
                if (function_exists('calculate_total_price_by_invoice')) {
                    $data = calculate_total_price_by_invoice($invoice);
                    return isset($data['total']) ? number_format($data['total']) : '0';
                }
                return '0';
            }
        } catch (Exception $e) {
            return '0';
        }
    }

    /**
     * Get student arrears safely
     */
    private function getStudentArrears($studentId)
    {
        try {
            if (! $studentId) {
                return 'N/A';
            }

            $arrearsAmount = StudentArrearsHistory::where('student_id', $studentId)
                ->whereNull('cleared_date')
                ->where('amount', '>', 0)
                ->sum('amount');

            return $arrearsAmount > 0 ? number_format($arrearsAmount) : 'N/A';
        } catch (Exception $e) {
            return 'N/A';
        }
    }

    public function export_paid_student()
    {
        return Excel::download(new ExportPaidStudent(), 'paid_students_report.xlsx');
    }

    public function EmployeeReport(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Increase memory and execution time for server
                ini_set('memory_limit', '512M');
                ini_set('max_execution_time', 300);

                // Get pagination parameters
                $limit = $request->input('length', 10);
                $start = $request->input('start', 0);

                // Build optimized query
                $query = Employee::with([
                    'user:id,name,email',
                    'branch:id,br_name,branch_code,state_id,region_id',
                    'branch.region:id,region_name',
                    'branch.state:id,state_name',
                    'company:id,company_name',
                    'department:id,department_name',
                    'designation:id,designation_name',
                    'designation_type:id,type_name',
                    'nationality:id,nationality_name',
                    'religion:id,religion_name',
                    'countries:id,country_name',
                    'states:id,state_name',
                    'cities:id,city_name'
                ]);

                // Apply branch filter for non-admin users
                if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
                    $branch_id = get_branch_id();
                    $query->where('branch_id', $branch_id);
                }

                // Apply filters
                if ($request->filled('company_id')) {
                    $query->where('company_id', $request->company_id);
                }

                if ($request->filled('region_id')) {
                    $query->whereHas('branch.region', function ($q) use ($request) {
                        $q->where('id', $request->region_id);
                    });
                }

                if ($request->filled('state_id')) {
                    $query->whereHas('branch.state', function ($q) use ($request) {
                        $q->where('id', $request->state_id);
                    });
                }

                if ($request->filled('branch_id')) {
                    $query->where('branch_id', $request->branch_id);
                }

                if ($request->filled('department_id')) {
                    $query->where('department_id', $request->department_id);
                }

                if ($request->filled('designation_id')) {
                    $query->where('designation_id', $request->designation_id);
                }

                if ($request->filled('job_status')) {
                    $query->where('job_status', $request->job_status);
                }

                if ($request->filled('marital_status')) {
                    $query->where('marital_status', $request->marital_status);
                }

                if ($request->filled('gender')) {
                    $query->whereHas('user', function ($q) use ($request) {
                        $q->where('gender', $request->gender);
                    });
                }

                // Search functionality
                if ($request->filled('searchTerm')) {
                    $searchTerm = $request->searchTerm;
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('preferred_name', 'like', '%' . $searchTerm . '%')
                          ->orWhere('employee_id', 'like', '%' . $searchTerm . '%')
                          ->orWhere('mobile_number', 'like', '%' . $searchTerm . '%')
                          ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                              $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                                       ->orWhere('email', 'like', '%' . $searchTerm . '%');
                          });
                    });
                }

                // Get total count
                $totalData = $query->count();
                $totalFiltered = $totalData;

                // Get paginated results
                $employees = $query->offset($start)->limit($limit)->get();

                // Prepare data for DataTables
                $data = [];
                foreach ($employees as $employee) {
                    $data[] = [
                        'employee_id' => $employee->employee_id ?? '-',
                        'full_name' => $employee->preferred_name ?? '-',
                        'email' => $employee->user?->email ?? '-',
                        'mobile_number' => $employee->mobile_number ?? '-',
                        'branch_name' => $employee->branch?->br_name ?? '-',
                        'branch_code' => $employee->branch?->branch_code ?? '-',
                        'region_name' => $employee->branch?->region?->region_name ?? '-',
                        'state_name' => $employee->branch?->state?->state_name ?? '-',
                        'company_name' => $employee->company?->company_name ?? '-',
                        'department_name' => $employee->department?->department_name ?? '-',
                        'designation_name' => $employee->designation?->designation_name ?? '-',
                        'designation_type' => $employee->designation_type?->type_name ?? '-',
                        'job_status' => $employee->job_status ?? '-',
                        'marital_status' => $employee->marital_status ?? '-',
                        'hiring_date' => $employee->hiring_date ? date('d-m-Y', strtotime($employee->hiring_date)) : '-',
                        'total_service' => $this->calculateTotalService($employee),
                        'confirm_date' => $employee->confirm_date ? date('d-m-Y', strtotime($employee->confirm_date)) : '-',
                        'nationality' => $employee->nationality?->nationality_name ?? '-',
                        'religion' => $employee->religion?->religion_name ?? '-',
                    ];
                }

                return response()->json([
                    "draw" => intval($request->input('draw')),
                    "recordsTotal" => $totalData,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
            }

            // Load filter data efficiently
            $companies = Company::select('id', 'company_name')->get();
            $regions = Region::select('id', 'region_name')->get();
            $states = State::select('id', 'state_name')->get();
            $departments = Department::select('id', 'department_name')->get();
            $designations = Designation::select('id', 'designation_name')->get();
            $designation_types = DesignationType::select('id', 'type_name')->get();
            $nationalities = Nationality::select('id', 'nationality_name')->get();
            $religions = Religion::select('id', 'religion_name')->get();

            // Optimize branches loading based on user role
            if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
                $branch_id = get_branch_id();
                $branches = Branch::where('id', $branch_id)->select('id', 'br_name', 'branch_code')->get();
            } else {
                $branches = Branch::select('id', 'br_name', 'branch_code')->get();
            }

            return view('reports.employee_report', [
                'companies' => $companies,
                'regions' => $regions,
                'states' => $states,
                'branches' => $branches,
                'departments' => $departments,
                'designations' => $designations,
                'designation_types' => $designation_types,
                'nationalities' => $nationalities,
                'religions' => $religions,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('EmployeeReport Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a proper error response
            if ($request->ajax()) {
                return response()->json([
                    'error' => 'An error occurred while loading the report. Please try again.',
                    'message' => config('app.debug') ? $e->getMessage() : 'Server error'
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while loading the report.');
        }
    }

    /**
     * Get job status badge for display
     */
    private function getJobStatusBadge($status)
    {
        switch ($status) {
            case 'regular':
                return '<span class="badge badge-success">Regular</span>';
            case 'probation':
                return '<span class="badge badge-warning">Probation</span>';
            case 'left':
                return '<span class="badge badge-danger">Left</span>';
            case 'adhoc':
                return '<span class="badge badge-info">Adhoc</span>';
            case 'contractual':
                return '<span class="badge badge-primary">Contractual</span>';
            default:
                return '<span class="badge badge-secondary">' . ucfirst($status ?? 'Unknown') . '</span>';
        }
    }
    public function export_student_concession()
    {
        return Excel::download(new ExportStudentConcession(), 'student_concession_report.xlsx');
    }

    public function export_employee()
    {
        return Excel::download(new ExportEmployee(), 'employee_report.xlsx');
    }

    public function StudentsRelationReport(Request $request)
    {
        if ($request->ajax()) {
            try {
                $branch_id = 0;
                if (! Auth::user()->hasRole('super_admin') && ! isHeadOfficeEmp()) {
                    $branch_id = get_branch_id();
                }

                // Build base query with optimized eager loading
                $data = Student::query();

                // Apply branch filter first for better performance
                if ($branch_id != 0) {
                    $data = $data->where('branch_id', $branch_id);
                }

                // Apply additional filters
                if ($request->branch_id && $request->branch_id > 0) {
                    $data = $data->where('branch_id', $request->branch_id);
                }

                if ($request->section_id && $request->section_id > 0) {
                    $data = $data->whereHas('std_fee_package', function ($query) use ($request) {
                        $query->where('section_id', $request->section_id);
                    });
                }

                if ($request->class_id && $request->class_id > 0) {
                    $data = $data->whereHas('active_class.branch_class_sections', function ($query) use ($request) {
                        $query->where('class_id', $request->class_id);
                    });
                }

                if ($request->gender && $request->gender != '') {
                    $data = $data->where('gender', $request->gender);
                }

                // Optimized search functionality
                if ($request->searchName && strlen($request->searchName) > 2) {
                    $data = $data->where(function ($query) use ($request) {
                        $query->where('first_name', 'like', '%' . $request->searchName . '%')
                              ->orWhere('middle_name', 'like', '%' . $request->searchName . '%')
                              ->orWhere('last_name', 'like', '%' . $request->searchName . '%')
                              ->orWhere('registration_no', 'like', '%' . $request->searchName . '%')
                              ->orWhere('roll_no', 'like', '%' . $request->searchName . '%')
                              ->orWhereHas('guardian', function ($q) use ($request) {
                                  $q->where('guardian_name', 'like', '%' . $request->searchName . '%')
                                    ->orWhere('mobile', 'like', '%' . $request->searchName . '%');
                              });
                    });
                }

                // Optimized eager loading - only load what's needed
                $data = $data->with([
                    'branch:id,branch_code,br_name',
                    'active_class.branch_class_sections.com_classes:id,class_name',
                    'std_fee_package.fee_package:id,package_name',
                    'std_fee_package.section:id,section_name',
                    'guardians:id,student_id,guardian_name,mobile',
                    'student_invoices:id,student_id,total_payable,created_at'
                ]);

                // Use DataTables server-side processing instead of loading all data
                return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_code', function ($row) {
                    return $row->branch->branch_code ?? '';
                })
                ->addColumn('br_name', function ($row) {
                    return $row->branch->br_name ?? '';
                })
                ->addColumn('full_name', function ($row) {
                    //$fullName = $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                    return view('students.student_image_tr', ['row' => $row]);
                })
                ->addColumn('student_id', function ($row) {
                    if ($row->roll_no == null) {
                        $studentID = $row->registration_no;
                    } else {
                        $studentID = $row->roll_no;
                    }
                    return $studentID;
                })
                ->addColumn('gender', function ($row) {
                    return $row->gender ?? '';
                })
                ->addColumn('class_name', function ($row) {
                    return $row->active_class->branch_class_sections->com_classes->class_name ?? '';
                })
                ->addColumn('section_name', function ($row) {
                    return $row->std_fee_package->section->section_name ?? '';
                })
                ->addColumn('package_name', function ($row) {
                    return $row->std_fee_package->fee_package->package_name ?? '';
                })
                ->addColumn('cost', function ($row) {
                    // Use eager-loaded student_invoices data
                    if ($row->student_invoices && $row->student_invoices->count() > 0) {
                        $latestInvoice = $row->student_invoices->sortByDesc('created_at')->first();
                        if ($latestInvoice && $latestInvoice->total_payable) {
                            return number_format($latestInvoice->total_payable);
                        }
                    }
                    return '';
                })
                ->addColumn('parent_name', function ($row) {
                    // Use eager-loaded guardians data
                    if ($row->guardians && $row->guardians->count() > 0) {
                        return $row->guardians->first()->guardian_name ?? '';
                    }
                    return '';
                })
                ->addColumn('parent_contact', function ($row) {
                    // Use eager-loaded guardians data
                    if ($row->guardians && $row->guardians->count() > 0) {
                        return $row->guardians->first()->mobile ?? '';
                    }
                    return '';
                })
                ->make(true);
            } catch (\Exception $e) {
                \Log::error('StudentsRelationReport Error: ' . $e->getMessage(), [
                    'request' => $request->all(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'An error occurred while loading the report. Please try again.',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
        }

        $branches = Branch::all();
        if (! Auth::user()->hasRole('super_admin') && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            // For super_admin, load all branch classes with com_classes relationship
            $classes = BranchClass::with(['com_classes'])->get();
            $sections = Section::all();
        }

        return view('reports.students_relation_list', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches
        ]);
    }

    public function export_student_relation()
    {
        return Excel::download(new ExportStudentRelation(), 'students_relation_report.xlsx');
    }

    public function sibling_report(Request $request)
    {
        $branches = Branch::all();
        // if ($request->ajax()) {
        //     $familyInfo = [];
        //     $branchID = $request->branch_id;
        //     $getAllFamilyInfos = FamilyInformation::get();
        //     // dd($getAllFamilyInfos);
        //     foreach ($getAllFamilyInfos as $family) {
        //         foreach ($family->children as $familyChildren) {
        //             if ($familyChildren->student->branch_id == $branchID) {

        //                 array_push($familyInfo, $family);
        //                 break;
        //                 // dd($familyInfo);
        //             }
        //         }
        //     }
        //     collect($familyInfo);
        //     return view('reports.sibling_report_partial', compact('familyInfo'));
        // } else {
        //     $familyInfo = FamilyInformation::where('family_no', 643420)->get();
        // }

        if ($request->ajax()) {
            try {
                // Use raw SQL query for better performance
                // Recommended database indexes for optimal performance:
                // - students: (status, deleted_at), (branch_id), (roll_no), (registration_no)
                // - sibling_information: (family_information_id), (student_id)
                // - family_information: (guardian_id)
                // - guardians: (CNIC), (mobile), (guardian_name)
                // - class_students: (student_id, is_valid)
                $query = \DB::table('family_information as fi')
                    ->join('sibling_information as si', 'fi.id', '=', 'si.family_information_id')
                    ->join('students as s', 'si.student_id', '=', 's.id')
                    ->leftJoin('guardians as g', 'fi.guardian_id', '=', 'g.id')
                    ->leftJoin('relations as r', 'g.relation_id', '=', 'r.id')
                    ->leftJoin('branches as b', 's.branch_id', '=', 'b.id')
                    ->leftJoin('class_students as cs', function ($join) {
                        $join->on('s.id', '=', 'cs.student_id')
                             ->where('cs.is_valid', '=', 1);
                    })
                    ->leftJoin('branch_class_sections as bcs', 'cs.branch_class_section_id', '=', 'bcs.id')
                    ->leftJoin('com_classes as cc', 'bcs.class_id', '=', 'cc.id')
                    ->leftJoin('sections as sec', 'bcs.section_id', '=', 'sec.id')
                    ->leftJoin('student_concessions as sc', 's.id', '=', 'sc.student_id')
                    ->leftJoin('fee_concessions as fc', 'sc.fee_concession_id', '=', 'fc.id')
                    ->leftJoin('fee_concession_types as fct', 'fc.fee_concession_type_id', '=', 'fct.id')
                    ->where('s.status', '!=', 'left')
                    ->whereNull('s.deleted_at')
                    ->select([
                        'fi.id as family_id',
                        'g.guardian_name as parent_name',
                        'g.CNIC as parent_cnic',
                        'g.mobile as parent_mobile',
                        'r.relation_name as relation',
                        \DB::raw('COALESCE(s.roll_no, s.registration_no) as student_id'),
                        \DB::raw('CONCAT(s.first_name, " ", s.last_name) as student_name'),
                        'cc.class_name',
                        'sec.section_name',
                        'b.br_name as branch_name',
                        \DB::raw('COALESCE(fct.name, "NO") as concession'),
                        \DB::raw('COALESCE(fc.concession_percentage, "0") as concession_percentage')
                    ]);

                // Apply branch filter
                if ($request->branch_id) {
                    $query->where('s.branch_id', $request->branch_id);
                }

                // Apply search filter
                if ($request->search_text) {
                    $searchText = $request->search_text;
                    $query->where(function ($q) use ($searchText) {
                        $q->where('g.guardian_name', 'like', '%' . $searchText . '%')
                          ->orWhere('g.mobile', 'like', '%' . $searchText . '%')
                          ->orWhere('g.CNIC', 'like', '%' . $searchText . '%')
                          ->orWhere('s.first_name', 'like', '%' . $searchText . '%')
                          ->orWhere('s.last_name', 'like', '%' . $searchText . '%')
                          ->orWhere('s.registration_no', 'like', '%' . $searchText . '%')
                          ->orWhere('s.roll_no', 'like', '%' . $searchText . '%');
                    });
                }

                // Get total count for DataTables
                $totalRecords = $query->count();

                // Apply pagination
                $start = $request->start ?? 0;
                $length = $request->length ?? 25;

                $data = $query->offset($start)
                             ->limit($length)
                             ->orderBy('fi.id', 'asc')
                             ->get();

                // Convert stdClass objects to arrays for DataTables
                $data = $data->map(function ($item) {
                    return (array) $item;
                })->toArray();

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->with([
                        'recordsTotal' => $totalRecords,
                        'recordsFiltered' => $totalRecords
                    ])
                ->make(true);
            } catch (\Exception $e) {
                \Log::error('Sibling Report Error: ' . $e->getMessage(), [
                    'request' => $request->all(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'error' => 'An error occurred while loading the report. Please try again.',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
        }
        $regions = Region::all();
        $states = State::all();
        $classes = BranchClass::all();
        $sections = Section::all();

        // Initialize familyInfo as empty collection for initial page load
        $familyInfo = collect();

        return view('reports.sibling_report', [
            'branches' => $branches,
            'regions' => $regions,
            'states' => $states,
            'classes' => $classes,
            'sections' => $sections,
            'familyInfo' => $familyInfo,
        ]);
    }

    public function sibling_report_export(Request $request)
    {
        try {
            Log::info('Sibling Report Export Request', $request->all());

            // Use the same optimized query as the main report
            $query = DB::table('family_information as fi')
                ->join('sibling_information as si', 'fi.id', '=', 'si.family_information_id')
                ->join('students as s', 'si.student_id', '=', 's.id')
                ->leftJoin('guardians as g', 'fi.guardian_id', '=', 'g.id')
                ->leftJoin('relations as r', 'g.relation_id', '=', 'r.id')
                ->leftJoin('branches as b', 's.branch_id', '=', 'b.id')
                ->leftJoin('class_students as cs', function ($join) {
                    $join->on('s.id', '=', 'cs.student_id')
                         ->where('cs.is_valid', '=', 1);
                })
                ->leftJoin('branch_class_sections as bcs', 'cs.branch_class_section_id', '=', 'bcs.id')
                ->leftJoin('com_classes as cc', 'bcs.class_id', '=', 'cc.id')
                ->leftJoin('sections as sec', 'bcs.section_id', '=', 'sec.id')
                ->leftJoin('student_concessions as sc', 's.id', '=', 'sc.student_id')
                ->leftJoin('fee_concessions as fc', 'sc.fee_concession_id', '=', 'fc.id')
                ->leftJoin('fee_concession_types as fct', 'fc.fee_concession_type_id', '=', 'fct.id')
                ->where('s.status', '!=', 'left')
                ->whereNull('s.deleted_at')
                ->select([
                    'fi.id as family_id',
                    'g.guardian_name as parent_name',
                    'g.CNIC as parent_cnic',
                    'g.mobile as parent_mobile',
                    'r.relation_name as relation',
                    DB::raw('COALESCE(s.roll_no, s.registration_no) as student_id'),
                    DB::raw('CONCAT(s.first_name, " ", s.last_name) as student_name'),
                    'cc.class_name',
                    'sec.section_name',
                    'b.br_name as branch_name',
                    DB::raw('COALESCE(fct.name, "NO") as concession'),
                    DB::raw('COALESCE(fc.concession_percentage, "0") as concession_percentage')
                ]);

            // Apply branch filter
            if ($request->branch_id) {
                $query->where('s.branch_id', $request->branch_id);
            }

            // Apply search filter
            if ($request->search_text) {
                $searchText = $request->search_text;
                $query->where(function ($q) use ($searchText) {
                    $q->where('g.guardian_name', 'like', '%' . $searchText . '%')
                      ->orWhere('g.mobile', 'like', '%' . $searchText . '%')
                      ->orWhere('g.CNIC', 'like', '%' . $searchText . '%')
                      ->orWhere('s.first_name', 'like', '%' . $searchText . '%')
                      ->orWhere('s.last_name', 'like', '%' . $searchText . '%')
                      ->orWhere('s.registration_no', 'like', '%' . $searchText . '%')
                      ->orWhere('s.roll_no', 'like', '%' . $searchText . '%');
                });
            }

            $data = $query->orderBy('fi.id', 'asc')->get();

            // Convert stdClass objects to arrays for PDF view
            $data = $data->map(function ($item) {
                return (array) $item;
            })->toArray();

            Log::info('Sibling Report Export Data Count', ['count' => count($data)]);

            // Get branch name for PDF
            $branchName = 'All Branches';
            if ($request->branch_id) {
                $branch = \DB::table('branches')->where('id', $request->branch_id)->first();
                $branchName = $branch ? $branch->br_name : 'All Branches';
            }

            // Generate PDF using DomPDF
            $pdf = PDF::loadView('reports.sibling_report_pdf', [
                'data' => $data,
                'branch_name' => $branchName,
                'generated_at' => now()->format('d-M-Y H:i:s')
            ]);

            $pdf->setPaper('A4', 'landscape');

            $filename = 'sibling_report_' . date('Y-m-d_H-i-s') . '.pdf';

            Log::info('Sibling Report Export Success', ['filename' => $filename]);

            return $pdf->download($filename);
        } catch (Exception $e) {
            Log::error('Sibling Report Export Error: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return a proper error response instead of redirect
            return response()->json([
                'error' => 'Failed to generate PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    public function student_concession_report(Request $request)
    {

        if ($request->ajax()) {
            if (! isHeadOfficeEmp() && ! isSuperAdmin()) {
                $data = Student::with(
                    [
                        'student_concession.fee_concession.child_concession_type',
                        'student_concession.academic_year',
                        'active_class.branch_class_sections.com_classes',
                        'active_class.branch_class_sections.sections',
                        'employee_guardian.employee.user',
                        'employee_guardian.employee.branch'
                    ]
                )->where('status', 'on_roll')->where('branch_id', get_branch_id())->whereHas('student_concession.fee_concession.child_concession_type');
            } else {
                $data = Student::with(
                    [
                        'student_concession.fee_concession.child_concession_type',
                        'student_concession.academic_year',
                        'active_class.branch_class_sections.com_classes',
                        'active_class.branch_class_sections.sections',
                        'employee_guardian.employee.user',
                        'employee_guardian.employee.branch'
                    ]
                )->where('status', 'on_roll')->where('branch_id', $request->branch_id)->whereHas('student_concession.fee_concession.child_concession_type');
            }
            if (isset($request->academic_year_id) && $request->academic_year_id > 0) {
                $data = $data->whereHas('student_concession', function ($query) use ($request) {
                    $query->where('academic_year_id', $request->academic_year_id);
                });
            }
            if ($request->class_id && $request->class_id != null) {
                $data = $data->whereHas('active_class', function ($query) use ($request) {
                    $query->whereHas('branch_class_sections', function ($query) use ($request) {
                        $query->where('class_id', $request->class_id);
                    });
                });
            }
            if ($request->section_id && $request->section_id != null) {
                $data = $data->whereHas('active_class', function ($query) use ($request) {
                    $query->whereHas('branch_class_sections', function ($query) use ($request) {
                        $query->whereHas('sections', function ($query) use ($request) {
                            $query->where('id', $request->section_id);
                        });
                    });
                });
            }
            if ($request->start_date && $request->start_date != null) {
                $data = $data->whereHas('student_concession', function ($query) use ($request) {
                    $query->where('start_date', $request->start_date);
                });
            }
            if ($request->end_date && $request->end_date != null) {
                $data = $data->whereHas('student_concession', function ($query) use ($request) {
                    $query->where('end_date', $request->end_date);
                });
            }
            //$data = $data->get();dd($data->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('roll_no', function ($row) {
                    if ($row->roll_no == null) {
                        $roll_no = $row->registration_no;
                    } else {
                        $roll_no = $row->roll_no;
                    }
                    return $roll_no;
                })
                ->addColumn('full_name', function ($row) {
                    $fullName = $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;
                    return $fullName;
                })
                ->addColumn('class_name_section', function ($row) {
                    if (isset($row['active_class']['branch_class_sections']['com_classes'])) {
                        $className = $row['active_class']['branch_class_sections']['com_classes']['class_name'];
                    } else {
                        $className = '';
                    }
                    if (isset($row['active_class']['branch_class_sections']['sections'])) {
                        $sectionName = $row['active_class']['branch_class_sections']['sections']['section_name'];
                    } else {
                        $sectionName = '';
                    }
                    return $className . '/' . $sectionName;
                })
                ->addColumn('academic_year', function ($row) {
                    if (isset($row['student_concession']['academic_year'])) {
                        $academic_year = $row['student_concession']['academic_year']['title'];
                    } else {
                        $academic_year = '-';
                    }
                    return $academic_year;
                })
                ->addColumn('concession_type', function ($row) {
                    if (isset($row['student_concession']['fee_concession']['child_concession_type'])) {
                        $concession_type = $row['student_concession']['fee_concession']['child_concession_type']['name'];
                    } else {
                        $concession_type = '-';
                    }
                    return $concession_type;
                })
                ->addColumn('hundered_percent', function ($row) {
                    if (isset($row['student_concession'])) {
                        if ($row['student_concession']['fee_concession']['concession_percentage'] == '100') {
                            $hundered_percent = 'Yes';
                        } else {
                            $hundered_percent = '-';
                        }
                    } else {
                        $hundered_percent = '-';
                    }
                    return $hundered_percent;
                })
                ->addColumn('fifty_percent', function ($row) {
                    if (isset($row['student_concession'])) {
                        if ($row['student_concession']['fee_concession']['concession_percentage'] == '50') {
                            $fifty_percent = 'Yes';
                        } else {
                            $fifty_percent = '-';
                        }
                    } else {
                        $fifty_percent = '-';
                    }
                    return $fifty_percent;
                })
                ->addColumn('from_date', function ($row) {
                    if (isset($row['student_concession']['start_date'])) {
                        $from_date = $row['student_concession']['start_date'];
                    } else {
                        $from_date = '';
                    }
                    return $from_date;
                })
                ->addColumn('to_date', function ($row) {
                    if (isset($row['student_concession']['end_date'])) {
                        $to_date = $row['student_concession']['end_date'];
                    } else {
                        $to_date = '';
                    }
                    return $to_date;
                })
                ->addColumn('emp_id', function ($row) {
                    if (isset($row['employee_guardian'])) {
                        $emp_id = $row['employee_guardian']['employee_no'];
                    } else {
                        $emp_id = '';
                    }
                    return $emp_id;
                })
                ->addColumn('emp_name', function ($row) {
                    if (isset($row['employee_guardian']['employee']['user'])) {
                        $emp_name = $row['employee_guardian']['employee']['user']['first_name'] . ' ' . $row['employee_guardian']['employee']['user']['last_name'];
                    } else {
                        $emp_name = '';
                    }
                    return $emp_name;
                })
                ->addColumn('br_name', function ($row) {
                    if (isset($row['employee_guardian']['employee']['branch'])) {
                        $br_name = $row['employee_guardian']['employee']['branch']['br_name'];
                    } else {
                        $br_name = '';
                    }
                    return $br_name;
                })
                ->make(true);
        }
        $academic_years = AcademicYear::all();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            $branches = Branch::all();
            $classes = ComClass::all();
            $sections = Section::all();
        }
        return view('reports.student_concession_report', compact(['academic_years', 'branches', 'classes', 'sections']));
    }

    public function branches_report(Request $request)
    {
        if ($request->ajax()) {
            // Optimized query with only necessary relationships and student count
            $query = Branch::with([
                'class_group',
                'default_bank_account',
                'contact_information.state'
            ])->withCount('students');

            // Apply filters with proper where conditions
            if ($request->filled('company_id') && $request->company_id > 0) {
                $query->where('company_id', $request->company_id);
            }

            if ($request->filled('region_id') && $request->region_id > 0) {
                $query->where('region_id', $request->region_id);
            }

            if ($request->filled('state_id') && $request->state_id > 0) {
                $query->whereHas('contact_information', function ($q) use ($request) {
                    $q->where('state_id', $request->state_id);
                });
            }

            if ($request->filled('nwa_id') && $request->nwa_id > 0) {
                $query->whereHas('nwa', function ($q) use ($request) {
                    $q->where('nwa_id', $request->nwa_id);
                });
            }

            // Fix search functionality - use where instead of orWhere
            if ($request->filled('searchTerm')) {
                $searchTerm = $request->searchTerm;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('br_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('abbreviation', 'like', '%' . $searchTerm . '%');
                });
            }

            // Get total count before pagination
            $totalData = $query->count();
            $totalFiltered = $totalData;

            // Apply pagination
            $limit = $request->input('length', 10);
            $start = $request->input('start', 0);

            $branches = $query->offset($start)->limit($limit)->get();

            // Prepare data for DataTables
            $return_data = [];
            foreach ($branches as $branch) {
                $return_data[] = [
                    'branch_code' => $branch->branch_code ?? $branch->id,
                    'br_name' => $branch->br_name,
                    'state_name' => $branch->contact_information?->state?->state_name ?? '-',
                    'class_group_name' => $branch->class_group?->name ?? '-',
                    'student_id_range' => $branch->student_id_from . ' - ' . $branch->student_id_to,
                    'student_count' => $branch->students_count,
                    'bank_name' => $branch->default_bank_account?->bank_name ?? '-',
                    'account_title' => $branch->default_bank_account?->account_title ?? '-',
                    'account_no' => $branch->default_bank_account?->account_no ?? '-',
                    'iban' => $branch->default_bank_account?->IBAN ?? '-',
                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => $return_data
            ]);
        }

        // Load filter data efficiently
        $data = [
            'companies' => Company::select('id', 'company_name')->get(),
            'regions' => Region::select('id', 'region_name')->get(),
            'states' => State::select('id', 'state_name')->get(),
        ];

        return view('reports.branches_report', $data);
    }

    public function InvoiceStatusReport(Request $request)
    {
        $branches = Branch::all();
        $data = [];
        if ($request->ajax()) {
            $i = $PaidStudent = $UnpaidStudent = 0;
            if (isset($request->branch_id) && $request->branch_id != '' && $request->branch_id > 0) {
                $PaidStudent = $UnpaidStudent = $InvoiceGenerated = $InvoiceNotGenerated = 0;
                $branch = Branch::where('id', $request->branch_id)->first()->toArray();
                $totalStudent = Student::where('branch_id', $branch['id'])->where('status', 'on_roll')->whereNull('deleted_at')->count();
                $Students = Student::where('branch_id', $branch['id'])->where('status', 'on_roll')->whereNull('deleted_at')->get();
                foreach ($Students as $Student) {
                    if ($request->fee_period_id && $request->fee_period_id > 0) {
                        //$fee_period_id = StudentInvoice::max_fee_period_id($Student['id']);
                        $date_range = FeePeriod::where('id', $request->fee_period_id)->get(['from_date', 'to_date'])->toArray();
                        $from_dt = explode('-', $date_range[0]['from_date']);
                        $from_date = $from_dt[2] . '-' . $from_dt[1] . '-' . $from_dt[0];
                        $to_dt = explode('-', $date_range[0]['to_date']);
                        $to_date = $to_dt[2] . '-' . $to_dt[1] . '-' . $to_dt[0];
                        $Paid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'paid')->whereBetween('due_date', [$from_date, $to_date])->get();
                        //dd($Paid_Invoice);
                        $Unpaid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'unpaid')->whereBetween('due_date', [$from_date, $to_date])->get();
                        if (count($Paid_Invoice) > 0) {
                            $PaidStudent++;
                            $InvoiceGenerated++;
                        } elseif (count($Unpaid_Invoice) > 0) {
                            $UnpaidStudent++;
                            $InvoiceGenerated++;
                        } else {
                            $InvoiceNotGenerated++;
                        }
                    } else {
                        $fee_period_id = StudentInvoice::max_fee_period_id($Student['id']);
                        $date_range = FeePeriod::where('id', $fee_period_id)->get(['from_date', 'to_date'])->toArray();

                        // Check if date_range has data before accessing it
                        if (! empty($date_range) && isset($date_range[0])) {
                            $from_dt = explode('-', $date_range[0]['from_date']);
                            $from_date = $from_dt[2] . '-' . $from_dt[1] . '-' . $from_dt[0];
                            $to_dt = explode('-', $date_range[0]['to_date']);
                            $to_date = $to_dt[2] . '-' . $to_dt[1] . '-' . $to_dt[0];
                            $Paid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'paid')->whereBetween('due_date', [$from_date, $to_date])->get();
                            $Unpaid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'unpaid')->whereBetween('due_date', [$from_date, $to_date])->get();
                            if (count($Paid_Invoice) > 0) {
                                $PaidStudent++;
                                $InvoiceGenerated++;
                            } elseif (count($Unpaid_Invoice) > 0) {
                                $UnpaidStudent++;
                                $InvoiceGenerated++;
                            } else {
                                $InvoiceNotGenerated++;
                            }
                        } else {
                            // If no fee period found, count as not generated
                            $InvoiceNotGenerated++;
                        }
                    }
                }
                $data[$i]['br_name'] = $branch['br_name'];
                $data[$i]['branch_code'] = intval($branch['branch_code']);
                $data[$i]['student_count'] = $totalStudent;
                $data[$i]['paid_count'] = $PaidStudent;
                $data[$i]['unpaid_count'] = $UnpaidStudent;
                $data[$i]['generated_count'] = $InvoiceGenerated; //$PaidStudent + $UnpaidStudent;
                $data[$i]['not_generated_count'] = $InvoiceNotGenerated; //(($PaidStudent + $UnpaidStudent) - $totalStudent);
            } else {
                if ($request->region_id && $request->region_id > 0) {
                    $branches = $branches->where('region_id', $request->region_id);
                }
                foreach ($branches as $branch) {
                    $PaidStudent = $UnpaidStudent = $InvoiceGenerated = $InvoiceNotGenerated = 0;
                    $totalStudent = Student::where('branch_id', $branch['id'])->where('status', 'on_roll')->whereNull('deleted_at')->count();
                    $Students = Student::where('branch_id', $branch['id'])->where('status', 'on_roll')->whereNull('deleted_at')->get();
                    //dd($Students);
                    foreach ($Students as $Student) {
                        if ($request->fee_period_id && $request->fee_period_id > 0) {
                            //$fee_period_id = StudentInvoice::max_fee_period_id($Student['id']);
                            $date_range = FeePeriod::where('id', $request->fee_period_id)->get(['from_date', 'to_date'])->toArray();
                            $from_dt = explode('-', $date_range[0]['from_date']);
                            $from_date = $from_dt[2] . '-' . $from_dt[1] . '-' . $from_dt[0];
                            $to_dt = explode('-', $date_range[0]['to_date']);
                            $to_date = $to_dt[2] . '-' . $to_dt[1] . '-' . $to_dt[0];
                            $Paid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'paid')->whereBetween('due_date', [$from_date, $to_date])->get();
                            $Unpaid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'unpaid')->whereBetween('due_date', [$from_date, $to_date])->get();

                            if (count($Paid_Invoice) > 0) {
                                $PaidStudent++;
                                $InvoiceGenerated++;
                            } elseif (count($Unpaid_Invoice) > 0) {
                                $UnpaidStudent++;
                                $InvoiceGenerated++;
                            } else {
                                $InvoiceNotGenerated++;
                            }
                        } else { //
                            $fee_period_id = StudentInvoice::max_fee_period_id($Student['id']);
                            $date_range = FeePeriod::where('id', $fee_period_id)->get(['from_date', 'to_date'])->toArray();

                            // Check if date_range has data before accessing it
                            if (! empty($date_range) && isset($date_range[0])) {
                                $from_dt = explode('-', $date_range[0]['from_date']);
                                $from_date = $from_dt[2] . '-' . $from_dt[1] . '-' . $from_dt[0];
                                $to_dt = explode('-', $date_range[0]['to_date']);
                                $to_date = $to_dt[2] . '-' . $to_dt[1] . '-' . $to_dt[0];
                                $Paid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'paid')->whereBetween('due_date', [$from_date, $to_date])->get();
                                $Unpaid_Invoice = StudentInvoice::where('student_id', $Student['id'])->where('invoice_frequency', 'Monthly')->where('bank_payment_status', 'unpaid')->whereBetween('due_date', [$from_date, $to_date])->get();
                                if (count($Paid_Invoice) > 0) {
                                    $PaidStudent++;
                                    $InvoiceGenerated++;
                                } elseif (count($Unpaid_Invoice) > 0) {
                                    $UnpaidStudent++;
                                    $InvoiceGenerated++;
                                } else {
                                    $InvoiceNotGenerated++;
                                }
                            } else {
                                // If no fee period found, count as not generated
                                $InvoiceNotGenerated++;
                            }
                        }
                    }
                    $data[$i]['br_name'] = $branch['br_name'];
                    $data[$i]['branch_code'] = intval($branch['branch_code']);
                    $data[$i]['student_count'] = $totalStudent;
                    $data[$i]['paid_count'] = $PaidStudent;
                    $data[$i]['unpaid_count'] = $UnpaidStudent;
                    $data[$i]['generated_count'] = $InvoiceGenerated; //$PaidStudent + $UnpaidStudent;
                    $data[$i]['not_generated_count'] = $InvoiceNotGenerated; //(($PaidStudent + $UnpaidStudent) - $totalStudent);
                    $i++;
                }
            }
            //$totalStudent = Student::where('branch_id',14)->where('status', 'on_roll')->count();
            //dd($data);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_code', function ($row) {
                    return isset($row['branch_code']) ? $row['branch_code'] : '';
                })
                ->addColumn('br_name', function ($row) {
                    return isset($row['br_name']) ? $row['br_name'] : '';
                })
                ->addColumn('student_count', function ($row) {
                    return isset($row['student_count']) ? $row['student_count'] : '';
                })
                ->addColumn('paid_count', function ($row) {
                    return isset($row['paid_count']) ? $row['paid_count'] : '';
                })
                ->addColumn('unpaid_count', function ($row) {
                    return isset($row['unpaid_count']) ? $row['unpaid_count'] : '';
                })
                ->addColumn('generated_count', function ($row) {
                    return isset($row['generated_count']) ? $row['generated_count'] : '';
                })
                ->addColumn('not_generated_count', function ($row) {
                    return isset($row['not_generated_count']) ? $row['not_generated_count'] : '';
                })
                ->make(true);
        }

        $regions = Region::all();
        return view('reports.invoice_status_report', [
            'branches' => $branches,
            'regions' => $regions,
        ]);
    }

    public function TransferInOutReport(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentTransferCase::with([
                'student',
                'reason',
                'student.active_class.branch_class_sections.com_classes',
                'student.active_class.branch_class_sections.sections',
                'created_by',
                'approved_by',
                'from_branch_model',
                'to_branch_model',
                'academic_year'
            ]);
            //dd($data->toArray());

            //Filter by student status
            if (isset($request->status)) {
                if (in_array($request->status, ['APPROVED', 'PENDING', 'CANCELLED'])) {
                    $data->where('status', $request->status);
                }
                //else
                //$query->whereNull('status');
            }
            //Filter by student name
            if (! empty($request->searchName)) {
                $data->where('first_name', 'like', $request->searchName . '%')
                    ->orWhere('middle_name', 'like', $request->searchName . '%')
                    ->orWhere('last_name', 'like', $request->searchName . '%');
            }
            //Filter by Branch, Section & Class
            if ($request->branch_id && $request->branch_id > 0 && (isHeadOfficeEmp() || isSuperAdmin())) {
                if (isset($request->report_order) && $request->report_order == 'in') {
                    $data->where('to_branch', $request->branch_id);
                } elseif (isset($request->report_order) && $request->report_order == 'out') {
                    $data->where('from_branch', $request->branch_id);
                } else {
                    $data->where('from_branch', $request->branch_id)->orWhere('to_branch', $request->branch_id);
                }
            } elseif (! isHeadOfficeEmp() && ! isSuperAdmin()) {
                if (isset($request->report_order) && $request->report_order == 'in') {
                    $data->where('to_branch', get_branch_id());
                } elseif (isset($request->report_order) && $request->report_order == 'out') {
                    $data->where('from_branch', get_branch_id());
                } else {
                    $data->where('from_branch', get_branch_id())->orWhere('to_branch', get_branch_id());
                }
            }

            if ($request->academic_year_id && $request->academic_year_id > 0) {
                $data->where('academic_year_id', $request->academic_year_id);
            }

            if (isset($request->report_type) && isset($request->start_date) && isset($request->end_date)) {
                if ($request->report_type == 'wef') {
                    $data->whereBetween('transfer_wef', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
                } else {
                    $data->whereBetween('request_date', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
                }
            }
            $data = $data->get(); //dd($data->toArray());
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
                    return $studentInfo->first_name . ' ' . $studentInfo->middle_name . ' ' . $studentInfo->last_name;
                })
                ->addColumn('class_section', function ($row) {
                    return isset($row['student']['active_class']['branch_class_sections']['com_classes']['class_name']) ? $row['student']['active_class']['branch_class_sections']['com_classes']['class_name'] . '-' . $row['student']['active_class']['branch_class_sections']['sections']['section_name'] : '';
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
                ->make(true);
        }

        $branches = Branch::all();
        $academic_years = AcademicYear::all();
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes'])->get();
            $sections = Section::all();
        } else {
            $classes = ComClass::all();
            $sections = Section::all();
            $regions = Region::all();
        }

        return view('reports.transfer_in_out', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
            'regions' => $regions,
            'academic_years' => $academic_years
        ]);
    }

    public function export_transfer_in_out()
    {
        return Excel::download(new ExportTransferInOut(), 'transfer_in_out_report.xlsx');
    }

    public function match_remarks_columns()
    {
        return \DB::table('student_behaviour_skill_remarks')
            ->selectRaw('student_behaviour_skill_remarks.student_id as student_id,student_behaviour_skill_remarks.teacher_comments as teacher_comments , student_behaviour_skill_remarks.schoolhead_comments as schoolhead_comments , student_behaviour_skill_remarks.is_promoted as is_promoted')
            ->join('student_behaviour_skills', 'student_behaviour_skill_remarks.student_behaviour_skill_id', '=', 'student_behaviour_skills.id')
            ->where('student_behaviour_skills.term_id', 2)->orderBy('student_behaviour_skill_remarks.student_id')->get();
    }

    public function StudentPromotionsReport(Request $request)
    {
        if ($request->ajax()) {
            // Optimized query using Eloquent relationships
            $query = StudentBehaviourSkillRemark::with([
                'student_behaviour_skill' => function ($q) {
                    $q->with(['branch', 'com_class', 'section']);
                },
                'student' => function ($q) {
                    $q->where('status', 'on_roll')
                      ->with(['branch', 'active_class.branch_class_sections.com_classes', 'active_class.branch_class_sections.sections']);
                }
            ])
            ->whereHas('student_behaviour_skill', function ($q) use ($request) {
                $q->where('term_id', 2);

                // Apply branch filter
                if (isHeadOfficeEmp() || isSuperAdmin()) {
                    if ($request->filled('branch_id') && $request->branch_id > 0) {
                        $q->where('branch_id', $request->branch_id);
                    }
                } else {
                    $q->where('branch_id', get_branch_id());
                }

                // Apply other filters
                if ($request->filled('academic_year_id') && $request->academic_year_id > 0) {
                    $q->where('academic_year_id', $request->academic_year_id);
                }
                if ($request->filled('class_id') && $request->class_id > 0) {
                    $q->where('class_id', $request->class_id);
                }
                if ($request->filled('section_id') && $request->section_id > 0) {
                    $q->where('section_id', $request->section_id);
                }
            })
            ->whereNull('deleted_at');

            // Apply promotion status filter
            if ($request->filled('is_promoted')) {
                $isPromoted = ($request->is_promoted == 'one') ? 1 : 0;
                $query->where('is_promoted', $isPromoted);
            }

            // Get pagination parameters
            $limit = $request->input('length', 10);
            $start = $request->input('start', 0);

            // Get total count
            $totalData = $query->count();
            $totalFiltered = $totalData;

            // Get paginated results
            $studentRemarks = $query->offset($start)->limit($limit)->get();

            // Prepare data for DataTables
            $data = [];
            foreach ($studentRemarks as $remark) {
                if ($remark->student && $remark->student_behaviour_skill) {
                    $student = $remark->student;
                    $classSection = $remark->student_behaviour_skill;

                    $data[] = [
                        'branch' => $student->branch ? $student->branch->br_name . ' (' . $student->branch->branch_code . ')' : '',
                        'student_id' => $student->roll_no ?: $student->registration_no,
                        'full_name' => $student->first_name . ' ' . $student->last_name,
                        'class_section' => ($classSection->com_class->class_name ?? 'N/A') . ' / ' . ($classSection->section->section_name ?? 'N/A'),
                        'status' => $remark->is_promoted ? 'Promoted' : 'Not Promoted',
                        'schoolhead_comments' => $remark->schoolhead_comments ?: '',
                        'teacher_comments' => $remark->teacher_comments ?: '',
                    ];
                }
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalData,
                "recordsFiltered" => $totalFiltered,
                "data" => $data
            ]);
        }

        // Load filter data efficiently
        if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $branch_id = get_branch_id();
            $branches = Branch::where('id', $branch_id)->select('id', 'br_name', 'branch_code')->get();
            $classes = BranchClass::where('branch_id', $branch_id)->with(['com_classes:id,class_name'])->get();
            $sections = Section::select('id', 'section_name')->get();
            $academic_years = AcademicYear::select('id', 'title', 'active')->get();
        } else {
            $branches = Branch::select('id', 'br_name', 'branch_code')->get();
            $classes = ComClass::select('id', 'class_name')->get();
            $sections = Section::select('id', 'section_name')->get();
            $academic_years = AcademicYear::select('id', 'title', 'active')->get();
        }

        return view('reports.promotion_report', [
            'classes' => $classes,
            'sections' => $sections,
            'branches' => $branches,
            'academic_years' => $academic_years,
        ]);
    }

    public function StudentPromotionReport()
    {
        //$request->query('academic_year');
        return Excel::download(new ExportStudentPromortions(), 'student_promortions_report.xlsx');
    }

    // public function sibling_report(Request $request)
    // {
    //     $branches = Branch::all();
    //     $branch_id = null;
    //     if ($request->ajax()) {
    //         $branch_id = $request->branch_id;
    //         $data['sibling_data'] =
    //             \DB::Select("Select Distinct  L.relation_name,b.guardian_name as Name, b.CNIC, b.mobile,a.family_no,d.id as 'STUDENT_ID',
    //         CASE
    //             WHEN d.middle_name IS NULL THEN
    //             CONCAT( d.first_name, ' ', d.last_name ) ELSE CONCAT( d.first_name, ' ', d.middle_name, ' ', d.last_name )
    //         END AS 'Student_name',k.section_name,j.class_name,br_name as 'Branch_Name',
    //         Case when g.concession_percentage is null then 'N'
    //             else 'Y' END as 'Concsession',
    //             g.concession_percentage
    //             from family_information a
    //             inner join guardians b on b.id=a.guardian_id
    //             Inner join sibling_information c on c.family_information_id=a.id
    //             Inner join students d on d.id= c.student_id
    //             Inner join branches e on e.id = d.branch_id
    //             LEFT Join student_concessions f on f.student_id = d.id
    //             LEFT join fee_concessions g on g.id= f.fee_concession_id
    //             Inner Join class_students h on h. student_id= d.id
    //             Inner Join branch_class_sections I on I.id= h.branch_class_section_id
    //             Inner Join com_classes j on j.id=I.class_id
    //             Inner join sections k on k.id= I.section_id
    //             Inner Join relations L on L.id= b.relation_id
    //             where e.id =" . $branch_id . "");
    //         return $data['sibling_data'];
    //     }
    //     //write your query below in DB::select clause
    //     $data['sibling_data'] = \DB::Select("Select Distinct  L.relation_name,b.guardian_name as Name, b.CNIC, b.mobile,a.family_no,d.id as 'STUDENT_ID',
    //     CASE
    //         WHEN d.middle_name IS NULL THEN
    //         CONCAT( d.first_name, ' ', d.last_name ) ELSE CONCAT( d.first_name, ' ', d.middle_name, ' ', d.last_name )
    //     END AS 'Student_name',k.section_name,j.class_name,br_name as 'Branch_Name',
    //     Case when g.concession_percentage is null then 'N'
    //         else 'Y' END as 'Concsession',
    //         g.concession_percentage
    //         from family_information a
    //     inner join guardians b on b.id=a.guardian_id
    //     Inner join sibling_information c on c.family_information_id=a.id
    //     Inner join students d on d.id= c.student_id
    //     Inner join branches e on e.id = d.branch_id
    //     LEFT Join student_concessions f on f.student_id = d.id
    //     LEFT join fee_concessions g on g.id= f.fee_concession_id
    //     Inner Join class_students h on h. student_id= d.id
    //     Inner Join branch_class_sections I on I.id= h.branch_class_section_id
    //     Inner Join com_classes j on j.id=I.class_id
    //     Inner join sections k on k.id= I.section_id
    //     Inner Join relations L on L.id= b.relation_id
    //     where a.family_no = '467384'");


    //     // dd($data['sibling_data']);W


    //     return view('reports.sibling_report', ['data' => $data['sibling_data'], 'branches' => $branches]);

    //     // return view('reports.students_relation_list', $data);
    // }

    /**
     * Calculate total service duration for an employee
     *
     * @param Employee $employee
     * @return string
     */
    private function calculateTotalService($employee)
    {
        try {
            if (! $employee->hiring_date) {
                return '-';
            }

            $hiringDate = Carbon::parse($employee->hiring_date);

            // If employee has left, calculate from hiring date to left date
            if ($employee->job_status === 'left' && $employee->left_date) {
                $endDate = Carbon::parse($employee->left_date);
            } else {
                // For active employees, calculate from hiring date to current date
                $endDate = Carbon::now();
            }

            // Calculate the difference
            $diff = $hiringDate->diff($endDate);

            $years = $diff->y;
            $months = $diff->m;
            $days = $diff->d;

            // Format the result
            $result = [];

            if ($years > 0) {
                $result[] = $years . ' ' . ($years == 1 ? 'Year' : 'Years');
            }

            if ($months > 0) {
                $result[] = $months . ' ' . ($months == 1 ? 'Month' : 'Months');
            }

            if ($days > 0 && $years == 0) {
                $result[] = $days . ' ' . ($days == 1 ? 'Day' : 'Days');
            }

            return empty($result) ? 'Less than 1 day' : implode(', ', $result);
        } catch (Exception $e) {
            Log::error('Error calculating total service for employee ID: ' . $employee->id . ' - ' . $e->getMessage());
            return '-';
        }
    }

    /**
     * General Ledger Report
     * Shows all financial transactions organized by account categories
     */
    public function GeneralLedgerReport(Request $request)
    {
        try {
            if ($request->ajax()) {
                // Increase memory and execution time for server
                ini_set('memory_limit', '512M');
                ini_set('max_execution_time', 300);

                // Get pagination parameters
                $limit = $request->input('length', 25);
                $start = $request->input('start', 0);

                // Get filter parameters
                $fromDate = $request->filled('from_date') ? $request->from_date : null;
                $toDate = $request->filled('to_date') ? $request->to_date : null;
                $branchId = $request->filled('branch_id') ? $request->branch_id : null;
                $accountType = $request->filled('account_type') ? $request->account_type : null;
                $transactionType = $request->filled('transaction_type') ? $request->transaction_type : null;
                $searchTerm = $request->filled('searchTerm') ? $request->searchTerm : null;

                // Apply branch filter for non-admin users
                if (! isSuperAdmin() && ! isHeadOfficeEmp()) {
                    $branchId = get_branch_id();
                }

                // Collect all financial transactions
                $transactions = collect();

                try {
                    // 1. Student Fee Revenue and Related Transactions
                    $studentInvoicesQuery = StudentInvoice::with(['student:id,first_name,last_name,branch_id', 'student.branch:id,br_name'])
                        ->whereNotNull('issue_date');

                    // Apply date filtering
                    if ($fromDate && $toDate) {
                        $studentInvoicesQuery->whereBetween('issue_date', [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $studentInvoicesQuery->where('issue_date', '>=', $fromDate);
                    } elseif ($toDate) {
                        $studentInvoicesQuery->where('issue_date', '<=', $toDate);
                    }

                    // Apply branch filter
                    if ($branchId) {
                        $studentInvoicesQuery->whereHas('student', function ($query) use ($branchId) {
                            $query->where('branch_id', $branchId);
                        });
                    }

                    $studentInvoices = $studentInvoicesQuery->get();

                    foreach ($studentInvoices as $invoice) {
                        // Main fee revenue
                        if ($invoice->total_payable > 0) {
                            $transactions->push([
                                'date' => $invoice->issue_date,
                                'account' => 'Student Fee Revenue',
                                'account_type' => 'Revenue',
                                'description' => 'Fee Invoice #' . ($invoice->invoice_no ?? 'N/A') . ' - ' . ($invoice->student->first_name ?? '') . ' ' . ($invoice->student->last_name ?? ''),
                                'reference' => $invoice->invoice_no ?? 'N/A',
                                'debit' => 0,
                                'credit' => $invoice->total_payable,
                                'branch' => $invoice->student->branch->br_name ?? 'Unknown Branch',
                                'transaction_type' => 'Student Fee'
                            ]);
                        }

                        // Calculate Late Fee (2.5% of total_payable if due date passed)
                        if ($invoice->due_date && $invoice->due_date < now()->toDateString()) {
                            $lateFeeAmount = $invoice->total_payable * 0.025; // 2.5% of total payable
                            if ($lateFeeAmount > 0) {
                                $transactions->push([
                                    'date' => $invoice->due_date,
                                    'account' => 'Late Fee Revenue',
                                    'account_type' => 'Revenue',
                                    'description' => 'Late Fee (2.5%) for Invoice #' . ($invoice->invoice_no ?? 'N/A') . ' - Due: ' . date('d-m-Y', strtotime($invoice->due_date)),
                                    'reference' => $invoice->invoice_no ?? 'N/A',
                                    'debit' => 0,
                                    'credit' => $lateFeeAmount,
                                    'branch' => $invoice->student->branch->br_name ?? 'Unknown Branch',
                                    'transaction_type' => 'Late Fee'
                                ]);
                            }
                        }
                    }
                } catch (Exception $e) {
                    Log::error('Error in StudentInvoice query: ' . $e->getMessage());
                }


                try {
                    // 2.5. Arrears Fines from arrears_history table
                    $arrearsQuery = StudentArrearsHistory::with([
                        'student:id,first_name,last_name,branch_id',
                        'student.branch:id,br_name',
                        'from_invoice:id,invoice_no'
                    ])->whereNull('cleared_date'); // Only uncleared arrears

                    // Apply date filtering
                    if ($fromDate && $toDate) {
                        $arrearsQuery->whereBetween('carried_date', [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $arrearsQuery->where('carried_date', '>=', $fromDate);
                    } elseif ($toDate) {
                        $arrearsQuery->where('carried_date', '<=', $toDate);
                    }

                    // Apply branch filter
                    if ($branchId) {
                        $arrearsQuery->whereHas('student', function ($query) use ($branchId) {
                            $query->where('branch_id', $branchId);
                        });
                    }

                    $arrears = $arrearsQuery->get();

                    foreach ($arrears as $arrear) {
                        $transactions->push([
                            'date' => $arrear->carried_date,
                            'account' => 'Arrears Fine Revenue',
                            'account_type' => 'Revenue',
                            'description' => 'Arrears Fine for ' . ($arrear->student->first_name ?? '') . ' ' . ($arrear->student->last_name ?? '') . ' - Invoice #' . ($arrear->from_invoice->invoice_no ?? 'N/A'),
                            'reference' => $arrear->from_invoice->invoice_no ?? 'ARR-' . $arrear->id,
                            'debit' => 0,
                            'credit' => $arrear->amount,
                            'branch' => $arrear->student->branch->br_name ?? 'Unknown Branch',
                            'transaction_type' => 'Arrears Fine'
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error('Error in StudentArrearsHistory query: ' . $e->getMessage());
                }

                try {
                    // 3. Payroll Expenses
                    $payrollQuery = Payroll::with([
                        'employee:id,preferred_name,branch_id',
                        'employee.branch:id,br_name'
                    ]);

                    // Apply date filtering
                    if ($fromDate && $toDate) {
                        $payrollQuery->whereBetween('processed_at', [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $payrollQuery->where('processed_at', '>=', $fromDate);
                    } elseif ($toDate) {
                        $payrollQuery->where('processed_at', '<=', $toDate);
                    }

                    // Apply branch filter
                    if ($branchId) {
                        $payrollQuery->whereHas('employee', function ($query) use ($branchId) {
                            $query->where('branch_id', $branchId);
                        });
                    }

                    $payrolls = $payrollQuery->get();

                    foreach ($payrolls as $payroll) {
                        $transactions->push([
                            'date' => $payroll->processed_at,
                            'account' => 'Employee Salaries',
                            'account_type' => 'Expense',
                            'description' => 'Salary for ' . ($payroll->employee->preferred_name ?? '') . ' - ' . $payroll->month . '/' . $payroll->year,
                            'reference' => 'PAY-' . $payroll->id,
                            'debit' => $payroll->net_salary,
                            'credit' => 0,
                            'branch' => $payroll->employee->branch->br_name ?? 'Unknown Branch',
                            'transaction_type' => 'Payroll'
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error('Error in Payroll query: ' . $e->getMessage());
                }

                try {
                    // 4. Asset Purchases
                    $assetQuery = Asset::with([
                        'currentBranch:id,br_name',
                        'category:id,name'
                    ]);

                    // Apply date filtering
                    if ($fromDate && $toDate) {
                        $assetQuery->whereBetween('purchase_date', [$fromDate, $toDate]);
                    } elseif ($fromDate) {
                        $assetQuery->where('purchase_date', '>=', $fromDate);
                    } elseif ($toDate) {
                        $assetQuery->where('purchase_date', '<=', $toDate);
                    }

                    // Apply branch filter
                    if ($branchId) {
                        $assetQuery->where('current_branch_id', $branchId);
                    }

                    $assets = $assetQuery->get();

                    foreach ($assets as $asset) {
                        // Asset purchase as expense (business expense)
                        $transactions->push([
                            'date' => $asset->purchase_date,
                            'account' => 'Asset Purchase Expense',
                            'account_type' => 'Expense',
                            'description' => 'Asset Purchase: ' . $asset->name . ' (' . $asset->asset_tag . ') - Assigned to ' . ($asset->currentBranch->br_name ?? 'Unknown Branch'),
                            'reference' => $asset->asset_tag,
                            'debit' => $asset->purchase_price,
                            'credit' => 0,
                            'branch' => $asset->currentBranch->br_name ?? 'Unknown Branch',
                            'transaction_type' => 'Asset Purchase'
                        ]);
                    }
                } catch (Exception $e) {
                    Log::error('Error in Asset query: ' . $e->getMessage());
                }

                // Log transaction counts for debugging
                Log::info('General Ledger Transactions Count: ' . $transactions->count());
                Log::info('Account Types: ' . $transactions->pluck('account_type')->unique()->implode(', '));
                Log::info('Transaction Types: ' . $transactions->pluck('transaction_type')->unique()->implode(', '));

                // Apply additional filters
                if ($accountType) {
                    $transactions = $transactions->filter(function ($transaction) use ($accountType) {
                        return $transaction['account_type'] === $accountType;
                    });
                }

                if ($transactionType) {
                    $transactions = $transactions->filter(function ($transaction) use ($transactionType) {
                        return $transaction['transaction_type'] === $transactionType;
                    });
                }

                if ($searchTerm) {
                    $searchTerm = strtolower($searchTerm);
                    $transactions = $transactions->filter(function ($transaction) use ($searchTerm) {
                        return strpos(strtolower($transaction['account']), $searchTerm) !== false ||
                               strpos(strtolower($transaction['description']), $searchTerm) !== false ||
                               strpos(strtolower($transaction['reference']), $searchTerm) !== false;
                    });
                }

                // Sort transactions by date (newest first)
                $transactions = $transactions->sortByDesc('date');

                // Get totals
                $totalData = $transactions->count();
                $totalFiltered = $totalData;

                // Paginate results
                $paginatedTransactions = $transactions->slice($start, $limit)->values();

                // Prepare data for DataTables
                $data = [];
                foreach ($paginatedTransactions as $transaction) {
                    $data[] = [
                        'date' => $transaction['date'] ? date('d-m-Y', strtotime($transaction['date'])) : '-',
                        'account' => $transaction['account'],
                        'account_type' => $transaction['account_type'],
                        'description' => $transaction['description'],
                        'reference' => $transaction['reference'],
                        'debit' => number_format($transaction['debit'], 2),
                        'credit' => number_format($transaction['credit'], 2),
                        'branch' => $transaction['branch'],
                        'transaction_type' => $transaction['transaction_type'],
                    ];
                }

                return response()->json([
                    "draw" => intval($request->input('draw')),
                    "recordsTotal" => $totalData,
                    "recordsFiltered" => $totalFiltered,
                    "data" => $data
                ]);
            }

            // Load filter data
            $branches = Branch::select('id', 'br_name', 'branch_code')->get();

            return view('reports.general_ledger_report', [
                'branches' => $branches,
            ]);
        } catch (Exception $e) {
            Log::error('Error in GeneralLedgerReport: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'An error occurred while generating the report: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Export General Ledger Report
     */
    public function export_general_ledger(Request $request)
    {
        // Get filter parameters from request
        $fromDate = $request->filled('from_date') ? $request->from_date : null;
        $toDate = $request->filled('to_date') ? $request->to_date : null;
        $branchId = $request->filled('branch_id') ? $request->branch_id : null;
        $accountType = $request->filled('account_type') ? $request->account_type : null;
        $transactionType = $request->filled('transaction_type') ? $request->transaction_type : null;
        $searchTerm = $request->filled('searchTerm') ? $request->searchTerm : null;

        return Excel::download(
            new ExportGeneralLedger($fromDate, $toDate, $branchId, $accountType, $transactionType, $searchTerm),
            'general_ledger_report.xlsx'
        );
    }
}
