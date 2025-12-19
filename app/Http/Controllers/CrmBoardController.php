<?php

namespace App\Http\Controllers;

use App\Models\AdmissionQuery;
use App\Models\City;
use App\Models\StudentWithdrawal;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\Source;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\VisitDetail;
use App\Models\NewSchoolFeeStructure;
use App\Models\State;
use App\Models\Region;
use App\Models\ClassGroup;
use App\Models\CampusOfficeType;
use App\Models\Student;
use App\Models\Project;
use App\Models\TaskMember;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use App\Models\ProjectMember;
use App\Models\GeneralDocument;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeaveQuota;
use App\Models\FranchiseInquiryOld;
use App\Models\TaskStatusChangeLog;
use App\Models\AcademicYear;
use App\Models\FeeCharge;
use App\Models\NotificationLog;
use App\Models\Asset;
use App\Models\StudentInvoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\FranchiseInquiry;
use App\Models\StudentTransferCase;
use App\Models\StudentPromotionRequest;
use App\Models\ExitInterviewFeedback;
use App\Models\Payroll;
use App\Models\BranchAcademicYear;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CrmBoardController extends Controller
{
    // public function communication_manager()
    // {
    //     // dd("hee");
    //     // Add logic to display the communication manager dashboard here
    //     return view('employees.dashboard.dashboard_communication_manager');
    // }
    public function index(Request $request)
    {
        $project_id = $request->project_id;
        $data['project'] = Project::with(['members.user'])->where(['id' => 2])->first();

        $data['task_statuses'] = TaskStatus::with(['tasks.sub_tasks', 'tasks.project.project_type'])
            ->with('tasks', function ($q) use ($project_id) {
                if ($project_id > 0) {
                    $q->where('project_id', '=', $project_id);
                }
            })->get();

        // Get common data
        $commonData = $this->getCommonData($request);

        // Get role-specific data
        $user = auth()->user();
        if ($user->hasRole('super_admin')) {
            $data = array_merge($data, $this->getSuperAdminData($request));
        } elseif ($user->hasRole(['head-of-finance', 'ho-accountant']) && isHeadOfficeEmp()) {
            $data = array_merge($data, $this->getFinanceData($request));
        } elseif ($user->hasRole('network_associate')) {
            $data = array_merge($data, $this->getNetworkAssociateData($request));
        } elseif ($user->hasRole(['head_of_bd', 'bd_sales'])) {
            $data = array_merge($data, $this->getBusinessDevelopmentData($request));
        } else {
            $data['employee_info'] = $this->getEmployeeBasicData();
        }

        // Merge common data
        $data = array_merge($data, $commonData);

        // Handle specific user dashboards
        if ($user && $user->hasRole('communication_manager')) {
            return $this->getCommunicationManagerDashboard($data, $commonData);
        } elseif ($user && $user->hasRole('business_dashboard_user')) {
            return $this->getBusinessDashboard($data, $commonData);
        }

        // Default dashboard
        return view('crm.board.board', $data, $commonData);
    }

    private function getCommonData(Request $request)
    {
        $user = auth()->user();
        $states = State::all();
        $academic_years = AcademicYear::all();
        $branches = Branch::all();

        if ($user->hasRole(['head_of_bd', 'bd_sales'])) {
            $state_id = get_state_id();
            $region_id = get_region_id();
            $regions = Region::where('id', $region_id)->get();

            if ($region_id != 0) {
                $branches = Branch::where('state_id', $state_id)->get();
                if ($region_id == 1) {
                    $states = State::where('id', 1)->get();
                }
                if ($state_id == 2) {
                    $states = State::where('id', 2)->get();
                }
            }
        }

        $regions = Region::all();
        $school_type = ClassGroup::all();
        $cities = City::all();
        $campus_types = CampusOfficeType::all();
        $fee_structure_records = NewSchoolFeeStructure::with([
            'new_fee_structure_details' => function ($query) {
                $query->whereIn('fee_status_by_dd', ['Approved', 'Pending', 'Rejected']);
            }
        ])->get();

        return [
            'academic_years' => $academic_years,
            'branches' => $branches,
            'regions' => $regions,
            'states' => $states,
            'school_type' => $school_type,
            'cities' => $cities,
            'campus_types' => $campus_types,
            'fee_structure_records' => $fee_structure_records,
        ];
    }

    private function getSuperAdminData(Request $request)
    {
        $this->own_visit($request);

        // Get all states with branch counts
        $states_with_counts = State::withCount(['contactInformation' => function ($query) {
            $query->whereHas('contact_informationable', function ($q) {
                $q->where('contact_informationable_type', 'App\\Models\\Branch');
            });
        }])->get();

        $data['all_states'] = $states_with_counts->sortByDesc('contact_information_count')->values();

        // Student statistics
        $data['total_students'] = Student::whereNull('deleted_at')->count();
        $data['onroll'] = Student::where('status', 'on_roll')->whereNull('deleted_at')->count();
        $data['register'] = Student::where('status', 'registered')->whereNull('deleted_at')->count();
        $data['processing'] = Student::where(function ($query) {
            $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'processing');
        })->whereNull('deleted_at')->count();
        $data['left'] = Student::where('status', 'left')->whereNull('deleted_at')->count();

        // Additional super admin metrics
        $data['total_branches'] = Branch::count();
        $data['total_employees'] = Employee::count();
        $data['total_assets'] = Asset::count();
        $data['pending_invoices'] = StudentInvoice::where('is_paid', 0)->count();
        $data['overdue_invoices'] = StudentInvoice::where('due_date', '<', now())->where('is_paid', 0)->count();
        $data['purchase_orders'] = PurchaseOrder::count();
        $data['pending_purchase_requests'] = PurchaseRequest::where('status', 'pending')->count();
        $data['asset_transfer_requests'] = Asset::where('status', 'transfer_requested')->count();
        $data['admission_inquiries'] = AdmissionQuery::where('deleted_at', null)->count();
        $data['transfer_students'] = StudentTransferCase::count();
        $data['withdrawal_students'] = StudentWithdrawal::count();
        $data['promotion_students'] = StudentPromotionRequest::count();
        $data['exit_interviews'] = ExitInterviewFeedback::count();

        // Students with arrears (not paid 3+ invoices)
        $data['students_with_arrears'] = Student::whereHas('invoices', function ($q) {
            $q->where('status', '!=', 'paid');
        })->withCount(['invoices' => function ($q) {
            $q->where('status', '!=', 'paid');
        }])->having('invoices_count', '>=', 3)->count();

        // Revenue trends data
        $data['revenue_trends'] = $this->getRevenueTrendsData();
        $data['all_branches'] = Branch::select('id', 'br_name')->orderBy('br_name')->get();

        // Academic years data
        $data['academic_years'] = $this->getAcademicYearsData();

        // Recent students data
        $data['recent_students'] = $this->getRecentStudentsData();

        // Financial health data
        $data['financial_health'] = $this->getFinancialHealthData();

        // Growth indicators data
        $data['growth_indicators'] = $this->getGrowthIndicatorsData();

        // Student enrollment trends data
        $data['student_trends'] = $this->getStudentEnrollmentTrendsData();

        return $data;
    }

    private function getFinanceData(Request $request)
    {
        // Similar to super admin but finance-specific
        $states_with_counts = State::withCount(['contactInformation' => function ($query) {
            $query->whereHas('contact_informationable', function ($q) {
                $q->where('contact_informationable_type', 'App\\Models\\Branch');
            });
        }])->get();

        $data['all_states'] = $states_with_counts->sortByDesc('contact_information_count')->values();

        $data['total_students'] = Student::whereNull('deleted_at')->count();
        $data['onroll'] = Student::where('status', 'on_roll')->whereNull('deleted_at')->count();
        $data['register'] = Student::where('status', 'registered')->whereNull('deleted_at')->count();
        $data['processing'] = Student::where(function ($query) {
            $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'processing');
        })->whereNull('deleted_at')->count();
        $data['left'] = Student::where('status', 'left')->whereNull('deleted_at')->count();

        return $data;
    }

    private function getNetworkAssociateData(Request $request)
    {
        $data['school_manuals'] = GeneralDocument::whereHas(
            'attachment_type',
            function ($q) {
                $q->where('slug', 'school_manuals');
            }
        )->orderByDesc('id')->where('status', 'active');
        $data['school_manuals'] = GeneralDocument::filteration($request, $data['school_manuals']);
        $data['school_manuals'] = $data['school_manuals']->limit(6)->get();

        $data['active_students_count'] = get_active_student_count(get_set_NWABranchId());
        $data['register_students_count'] = get_new_register_student_count(get_set_NWABranchId());
        $data['register_students_revenue'] = $data['register_students_count'] * 500;
        $data['nwa_branch_id'] = get_branch_id();

        if ($request->academic_year_id) {
            $data['fee'] = FeeCharge::where('branch_id', $data['nwa_branch_id'])
                ->where('academic_year_id', $request->academic_year_id)
                ->with(['fee_charges_type'])->get();
        } else {
            $data['fee'] = FeeCharge::where('branch_id', $data['nwa_branch_id'])
                ->where('academic_year_id', 5)
                ->with(['fee_charges_type'])->first();
        }

        $branch_id = get_NWABranchCode();
        $data['total_students'] = Student::where('branch_id', $branch_id)->whereNull('deleted_at')->count();
        $data['onroll'] = Student::where('status', 'on_roll')->where('branch_id', $branch_id)->whereNull('deleted_at')->count();
        $data['register'] = Student::where('status', 'registered')->where('branch_id', $branch_id)->whereNull('deleted_at')->count();
        $data['processing'] = Student::where(function ($query) {
            $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'processing');
        })->where('branch_id', $branch_id)->whereNull('deleted_at')->count();
        $data['left'] = Student::where('status', 'left')->where('branch_id', $branch_id)->whereNull('deleted_at')->count();
        $data['academic_years'] = AcademicYear::all();

        return $data;
    }

    private function getBusinessDevelopmentData(Request $request)
    {
        $state_id = get_state_id();

        $states_with_counts = State::withCount(['contactInformation' => function ($query) {
            $query->whereHas('contact_informationable', function ($q) {
                $q->where('contact_informationable_type', 'App\\Models\\Branch');
            });
        }])->get();

        $data['all_states'] = $states_with_counts->sortByDesc('contact_information_count')->values();

        if ($state_id == 1) {
            $data['first_state_count'] = Branch::where('state_id', 1)->count();
            $data['second_state_count'] = Branch::where('state_id', 2)->count();
            $first_state_branches = Branch::where('state_id', 1)->pluck('id');
            $data['total_students'] = Student::whereIn('branch_id', $first_state_branches)->whereNull('deleted_at')->count();
            $data['onroll'] = Student::where('status', 'on_roll')->whereIn('branch_id', $first_state_branches)->whereNull('deleted_at')->count();
            $data['register'] = Student::where('status', 'registered')->whereIn('branch_id', $first_state_branches)->whereNull('deleted_at')->count();
            $data['processing'] = Student::where(function ($query) {
                $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'processing');
            })->whereIn('branch_id', $first_state_branches)->whereNull('deleted_at')->count();
            $data['left'] = Student::where('status', 'left')->whereIn('branch_id', $first_state_branches)->whereNull('deleted_at')->count();
        } elseif ($state_id == 2) {
            $data['first_state_count'] = Branch::where('state_id', 1)->count();
            $data['second_state_count'] = Branch::where('state_id', 2)->count();
            $second_state_branches = Branch::where('state_id', 2)->pluck('id');
            $data['total_students'] = Student::whereIn('branch_id', $second_state_branches)->whereNull('deleted_at')->count();
            $data['onroll'] = Student::where('status', 'on_roll')->whereIn('branch_id', $second_state_branches)->whereNull('deleted_at')->count();
            $data['register'] = Student::where('status', 'registered')->whereIn('branch_id', $second_state_branches)->whereNull('deleted_at')->count();
            $data['processing'] = Student::where(function ($query) {
                $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'processing');
            })->whereIn('branch_id', $second_state_branches)->whereNull('deleted_at')->count();
            $data['left'] = Student::where('status', 'left')->whereIn('branch_id', $second_state_branches)->whereNull('deleted_at')->count();
        } else {
            $data['first_state_count'] = Branch::where('state_id', 1)->count();
            $data['second_state_count'] = Branch::where('state_id', 2)->count();
            $data['total_students'] = Student::whereNull('deleted_at')->count();
            $data['onroll'] = Student::where('status', 'on_roll')->whereNull('deleted_at')->count();
            $data['register'] = Student::where('status', 'registered')->whereNull('deleted_at')->count();
            $data['processing'] = Student::where(function ($query) {
                $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'processing');
            })->whereNull('deleted_at')->count();
            $data['left'] = Student::where('status', 'left')->whereNull('deleted_at')->count();
        }

        $data['employee_info'] = $this->getEmployeeBasicData();

        return $data;
    }

    private function getCommunicationManagerDashboard($data, $commonData)
    {
        $notify = NotificationLog::all();
        $smsNotifications = $notify->where('notification_type', 'SMS');
        $EmailNotifications = $notify->where('notification_type', 'Email');
        $PushNotification = $notify->where('notification_type', 'Push Notification');

        $latestMessages = NotificationLog::orderBy('created_at', 'desc')
            ->select('notification_body', 'created_at')
            ->take(3)
            ->get();

        $groupedNotifications = $notify->groupBy(function ($notification) {
            return $notification->created_at->format('Y-m-d');
        });

        $messageCounts = [];
        foreach ($groupedNotifications as $date => $notifications) {
            $messageCounts[$date] = $notifications->count();
        }

        $annoucementPermit = Auth::user()->permissions()->whereName('announcements')->first();

        return view('employees.dashboard.dashboard_communication_manager', $data, array_merge($commonData, [
            'annoucementPermit' => $annoucementPermit,
            'smsNotifications' => $smsNotifications,
            'EmailNotifications' => $EmailNotifications,
            'PushNotification' => $PushNotification,
            'messageCounts' => $messageCounts,
            'latestMessages' => $latestMessages,
        ]));
    }

    private function getBusinessDashboard($data, $commonData)
    {
        return view('employees.dashboard.business_dashboard', $data, $commonData);
    }

    public function getEmployeeBasicData()
    {

        $employeeId = Auth::user()->employee->id;
        //$service_length = now()->diffInMonths(Carbon::parse(Auth::user()->employee->hiring_date));

        $leaveQuotas = EmployeeLeaveQuota::with('leaveType')->where('employee_id', auth()->user()->employee->id)->get();
        $today = date('Y-m-d');

        $today_start = $today . ' 00:00:00';
        $today_end = $today . ' 23:59:59';
        $mark_time_in = EmployeeAttendance::whereBetween('created_at', [$today_start, $today_end])->where('employee_id', auth()->user()->employee->id)->get(['id', 'time_in', 'time_out']);
        if (isset($mark_time_in[0]->time_in) && is_null($mark_time_in[0]->time_out)) {
            $time_in = $mark_time_in[0]->time_in;
            $time_out = null;
            $id = $mark_time_in[0]->id;
        } elseif (isset($mark_time_in[0]->time_in) && isset($mark_time_in[0]->time_out)) {
            $time_in = $mark_time_in[0]->time_in;
            $time_out = $mark_time_in[0]->time_out;
            $id = $mark_time_in[0]->id;
        } else {
            $time_in = null;
            $time_out = null;
            $id = null;
        }
        return [
            'user_id' => $employeeId,
            'leaveQuotas' => $leaveQuotas,
            'time_in' => $time_in,
            'time_out' => $time_out,
            'id' => $id
            //'service_length' => $service_length
        ];
    }


    public function addTask(Request $request)
    {
    }

    public function addTaskMember(Request $request)
    {
    }

    public function addProjectMember(Request $request)
    {
    }

    public function taskDetail(Request $request)
    {

        $task_id = $request->task_id;
        $task_statuses = TaskStatus::get();
        $task = Task::where(['id' => $task_id])->with(['project', 'status', 'members.user', 'sub_tasks'])->first();
        $task_detail_data = $task->toArray();
        $inquiry_id = $task_detail_data['taskable_id'];
        $inquiry_detail = FranchiseInquiryOld::where(['id' => $inquiry_id])->with(['source', 'cities'])->first();
        // dd($inquiry_detail->toArray());

        return view('crm.board.details_task', [
            'task_statuses' => $task_statuses,
            'task' => $task,
            'inquiry_details' => $inquiry_detail
        ]);
    }




    public function changeTaskStatus(Request $request)
    {
        $user_id = Auth::user()->id;
        $task_id = $request->task_id;
        $task_status = $request->task_status;

        $task = Task::find($task_id);
        $from_status = $task->task_status_id;
        $to_status = $task_status;

        if ($task) {
            $task->task_status_id = $task_status;
            $task->save();
            $logs = TaskStatusChangeLog::create([
                'task_id' => $task_id,
                'from_status' => $from_status,
                'to_status' => $to_status,
                'user_id' => $user_id
            ]);
        }
    }

    public function showProjectMembersList(Request $request)
    {
        $task_id = $request->task_id;
        $project_id = $request->project_id;

        $project_members = ProjectMember::with(['user.tasks'])->where(['project_id' => $project_id])->get();
        $task_members = TaskMember::where(['task_id' => $task_id])->with(['user'])->pluck('user_id')->toArray();

        return view('crm.board.project_members_list_modal', [
            'task_members' => $task_members,
            'project_members' => $project_members,
            'task_id' => $task_id,
            'project_id' => $project_id
        ]);
    }


    public function toggleTaskMember(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = $request->user_id;
        $project_id = $request->project_id;

        $member = TaskMember::where(['task_id' => $task_id, 'user_id' => $user_id])->withTrashed()->first();

        if ($member && $member->deleted_at == null) {
            $member->delete();
        } else {
            if ($member->deleted_at != null) {
                $member->restore();
            } else {
                TaskMember::create([
                    'task_id' => $task_id,
                    'user_id' => $user_id
                ]);
            }
        }
    }
    public function cardValue(Request $request)
    {
        $academic_year_id = $request->academic_year_id;
        $nwa_branch_id = get_branch_id();
        $fee = FeeCharge::where(
            'branch_id',
            $nwa_branch_id
        )->where('academic_year_id', $academic_year_id)->with(['fee_charges_type'])->first('amount');
        $academic_years = AcademicYear::all();
        $register_students_count = get_register_student_count(get_set_NWABranchId(), $academic_year_id);
        $register_students_revenue = $register_students_count * 500;
        // $active_students_count = get_active_student_count(get_set_NWABranchId());
        return [
            'fee' => $fee,
            'academic_years' => $academic_years,
            'register_students_count' => $register_students_count,
            'register_students_revenue' => $register_students_revenue,
            // 'active_students_count' => $active_students_count
        ];
    }

    public function own_visit(Request $request)
    {
        if ($request->ajax()) {
            $data = VisitDetail::where('user_id', auth()->user()->id)->with('branch', 'user', 'campus', 'fromCity', 'toCity', 'approvedBy');
            if ($request->branch_id && $request->branch_id > 0) {
                $data = $data->where('branch_id', $request->branch_id);
            }
            if ($request->from_city_id && $request->from_city_id > 0) {
                $data = $data->where('from_city_id', $request->from_city_id);
            }
            if ($request->to_city_id && $request->to_city_id > 0) {
                $data = $data->where('to_city_id', $request->to_city_id);
            }
            if ($request->total_duration && $request->total_duration > 0) {
                $data = $data->where('total_duration', $request->total_duration);
            }
            if ($request->campus_office_id && $request->campus_office_id > 0) {
                $data = $data->where('campus_office_id', $request->campus_office_id);
            }
            if ($request->user_id && $request->user_id > 0) {
                $data = $data->where('user_id', $request->user_id);
            }
            if ($request->approval_status && $request->approval_status != '') {
                $data = $data->where('approval_status', $request->approval_status);
            }

            //dd($data->get()->toArray());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $fullName = $row->user->first_name . ' ' . $row->user->last_name;
                    return $fullName;
                })
                ->addColumn('department', function ($row) {
                    $Department = Employee::where('user_id', $row->user->id)->with('department')->first();
                    return $Department->department->department_name;
                })
                ->addColumn('campus_office', function ($row) {
                    return $row->campus->type;
                })
                ->addColumn('branch_name', function ($row) {
                    return $row->branch->br_name;
                })
                ->addColumn('city_from', function ($row) {
                    return $row->fromCity->city_name;
                })
                ->addColumn('city_to', function ($row) {
                    return $row->toCity->city_name;
                })
                ->addColumn('travel_on', function ($row) {
                    return Carbon::parse($row->travel_on)->format('d-m-Y');
                })
                ->addColumn('return_on', function ($row) {
                    return Carbon::parse($row->return_on)->format('d-m-Y');
                })
                ->addColumn('approval_auth', function ($row) {
                    $fullName = $row->approvedBy->first_name . ' ' . $row->approvedBy->last_name;
                    return $fullName;
                })
                ->addColumn('action', function ($row) {
                    return view('Visitors.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $branches = Branch::all();
        $cities = City::all();
        $campus_types = CampusOfficeType::all();
        if (isSuperAdmin()) {
            $employees = Employee::where('branch_id', 2)->whereNull('left_date')->with('user', 'department')->orderBy('preferred_name')->get();
        } else {
            $employees = Employee::where('branch_id', 2)->where('reporting_to', auth()->user()->id)->whereNull('left_date')->with('user', 'department')->orderBy('preferred_name')->get();
        }
        return compact(['branches', 'cities', 'campus_types', 'employees']);
    }

    private function getRevenueTrendsData()
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Get student fee revenue (paid invoices) for each month across all years
        $revenueData = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            // Student fee revenue (paid invoices) - all time data for this month
            $revenue = StudentInvoice::where('is_paid', 1)
                ->whereRaw('MONTH(paid_date) = ?', [$i])
                ->sum('paid_amount');
            $revenueData[] = (int) $revenue;

            // Expenses: Payroll + Asset purchases - all time data for this month
            $payrollExpense = Payroll::whereIn('status', ['paid', 'processed'])
                ->where('month', $i)
                ->sum('net_salary');

            $assetExpense = PurchaseOrder::whereIn('status', ['approved', 'completed'])
                ->whereRaw('MONTH(created_at) = ?', [$i])
                ->sum('total_cost');

            $expenseData[] = (int) ($payrollExpense + $assetExpense);
        }

        return [
            'months' => $months,
            'revenue' => $revenueData,
            'expenses' => $expenseData,
            'profit' => array_map(function ($rev, $exp) {
                return $rev - $exp;
            }, $revenueData, $expenseData)
        ];
    }

    private function getRecentStudentsData()
    {
        return Student::with(['branch'])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($student) {
                // Concatenate name from first_name, middle_name, last_name with null checks
                $nameParts = [];
                if (! empty($student->first_name)) {
                    $nameParts[] = $student->first_name;
                }
                if (! empty($student->middle_name)) {
                    $nameParts[] = $student->middle_name;
                }
                if (! empty($student->last_name)) {
                    $nameParts[] = $student->last_name;
                }
                $fullName = ! empty($nameParts) ? implode(' ', $nameParts) : 'N/A';

                // Handle status - if empty or null, set to 'Processing'
                $status = $student->status;
                if (empty($status) || is_null($status)) {
                    $status = 'Processing';
                }

                return [
                    'id' => $student->id,
                    'name' => $fullName,
                    'roll_number' => $student->roll_no ?? 'N/A',
                    'branch_name' => $student->branch->br_name ?? 'N/A',
                    'status' => $status,
                    'created_at' => $student->created_at,
                    'formatted_date' => $student->created_at->diffForHumans(),
                    'formatted_date_full' => $student->created_at->format('M d, Y H:i')
                ];
            });
    }

    private function getFinancialHealthData()
    {
        // Total Revenue (all paid invoices)
        $totalRevenue = StudentInvoice::where('is_paid', 1)->sum('paid_amount');

        // Outstanding Payments (unpaid invoices)
        $outstandingPayments = StudentInvoice::where('is_paid', 0)->sum('total_payable');

        // Collection Rate (percentage of paid vs total)
        $totalInvoices = StudentInvoice::sum('total_payable');
        $collectionRate = $totalInvoices > 0 ? round(($totalRevenue / $totalInvoices) * 100, 1) : 0;

        return [
            'total_revenue' => $totalRevenue,
            'outstanding_payments' => $outstandingPayments,
            'collection_rate' => $collectionRate
        ];
    }

    private function getGrowthIndicatorsData()
    {
        // Monthly Growth (students added this month vs last month)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
        $lastMonthYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;

        $currentMonthStudents = Student::whereNull('deleted_at')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        $lastMonthStudents = Student::whereNull('deleted_at')
            ->whereYear('created_at', $lastMonthYear)
            ->whereMonth('created_at', $lastMonth)
            ->count();

        $monthlyGrowth = $lastMonthStudents > 0 ?
            round((($currentMonthStudents - $lastMonthStudents) / $lastMonthStudents) * 100, 1) : 0;

        // New Admissions (students added this month)
        $newAdmissions = Student::whereNull('deleted_at')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();

        // Branch Expansion (new branches added this year)
        $branchExpansion = Branch::whereYear('created_at', $currentYear)->count();

        return [
            'monthly_growth' => $monthlyGrowth,
            'new_admissions' => $newAdmissions,
            'branch_expansion' => $branchExpansion
        ];
    }

    private function getStudentEnrollmentTrendsData()
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $onRollData = [];
        $newRegistrationsData = [];
        $withdrawalsData = [];

        for ($i = 1; $i <= 12; $i++) {
            // On Roll students (all time data for this month)
            $onRoll = Student::where('status', 'on_roll')
                ->whereNull('deleted_at')
                ->whereRaw('MONTH(created_at) = ?', [$i])
                ->count();
            $onRollData[] = $onRoll;

            // New Registrations (registered students added in this month)
            $newRegistrations = Student::where('status', 'registered')
                ->whereNull('deleted_at')
                ->whereRaw('MONTH(created_at) = ?', [$i])
                ->count();
            $newRegistrationsData[] = $newRegistrations;

            // Withdrawals (students who left in this month)
            $withdrawals = Student::where('status', 'left')
                ->whereNull('deleted_at')
                ->whereRaw('MONTH(created_at) = ?', [$i])
                ->count();
            $withdrawalsData[] = $withdrawals;
        }

        return [
            'months' => $months,
            'on_roll' => $onRollData,
            'new_registrations' => $newRegistrationsData,
            'withdrawals' => $withdrawalsData
        ];
    }

    public function getStudentTrendsData(Request $request)
    {
        $academicYearId = $request->academic_year_id;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        $onRollData = [];
        $newRegistrationsData = [];
        $withdrawalsData = [];

        for ($i = 1; $i <= 12; $i++) {
            $query = Student::whereNull('deleted_at');

            // Apply academic year filter if provided
            if ($academicYearId) {
                $academicYear = BranchAcademicYear::where('academic_year_id', $academicYearId)->first();
                if ($academicYear) {
                    $query->whereBetween('created_at', [
                        $academicYear->start_date,
                        $academicYear->end_date
                    ]);
                }
            }

            // On Roll students for this month
            $onRoll = (clone $query)->where('status', 'on_roll')
                ->whereRaw('MONTH(created_at) = ?', [$i])
                ->count();
            $onRollData[] = $onRoll;

            // New Registrations for this month
            $newRegistrations = (clone $query)->where('status', 'registered')
                ->whereRaw('MONTH(created_at) = ?', [$i])
                ->count();
            $newRegistrationsData[] = $newRegistrations;

            // Withdrawals for this month
            $withdrawals = (clone $query)->where('status', 'left')
                ->whereRaw('MONTH(created_at) = ?', [$i])
                ->count();
            $withdrawalsData[] = $withdrawals;
        }

        return response()->json([
            'months' => $months,
            'on_roll' => $onRollData,
            'new_registrations' => $newRegistrationsData,
            'withdrawals' => $withdrawalsData
        ]);
    }

    private function getAcademicYearsData()
    {
        // Get current academic year based on current date
        $currentDate = now();

        // Get all academic years with their date ranges
        $academicYears = BranchAcademicYear::with('academic_year')
            ->where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->orWhere(function ($query) use ($currentDate) {
                $query->where('start_date', '>=', $currentDate->copy()->subYear())
                    ->where('end_date', '<=', $currentDate->copy()->addYear());
            })
            ->get()
            ->groupBy('academic_year_id')
            ->map(function ($group) {
                $firstRecord = $group->first();
                return [
                    'id' => $firstRecord->academic_year_id,
                    'title' => $firstRecord->academic_year->title ?? 'Academic Year ' . $firstRecord->academic_year_id,
                    'start_date' => $firstRecord->start_date,
                    'end_date' => $firstRecord->end_date,
                    'is_current' => $firstRecord->start_date <= now() && $firstRecord->end_date >= now()
                ];
            })
            ->sortByDesc('start_date')
            ->values();

        return $academicYears;
    }

    public function getBranchRevenueData(Request $request)
    {
        $branchId = $request->branch_id;
        $academicYearId = $request->academic_year_id;
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Get academic year date range if provided
        $academicYearDates = null;
        if ($academicYearId) {
            $academicYear = BranchAcademicYear::where('academic_year_id', $academicYearId)->first();
            if ($academicYear) {
                $academicYearDates = [
                    'start_date' => $academicYear->start_date,
                    'end_date' => $academicYear->end_date
                ];
            }
        }

        // Use 2024 as default year since that's likely where the data exists
        $defaultYear = 2024;
        $currentYear = now()->year;

        $revenueData = [];
        $expenseData = [];

        for ($i = 1; $i <= 12; $i++) {
            // Student fee revenue - filter by branch if provided
            $revenueQuery = StudentInvoice::where('is_paid', 1);

            if ($branchId) {
                $revenueQuery->whereHas('student', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }

            // If academic year is selected, filter by date range
            if ($academicYearDates) {
                $academicStartYear = Carbon::parse($academicYearDates['start_date'])->year;
                $startDate = Carbon::create($academicStartYear, $i, 1)->startOfMonth();
                $endDate = Carbon::create($academicStartYear, $i, 1)->endOfMonth();
                $revenueQuery->whereBetween('paid_date', [$startDate, $endDate]);
            }
            // If no academic year selected, get all-time data for this month across all years
            else {
                $revenueQuery->whereRaw('MONTH(paid_date) = ?', [$i]);
            }

            $revenue = $revenueQuery->sum('paid_amount');
            $revenueData[] = (int) $revenue;

            // Payroll expenses - filter by employee branch if provided
            $payrollQuery = Payroll::whereIn('status', ['paid', 'processed']);

            if ($branchId) {
                $payrollQuery->whereHas('employee', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                });
            }

            // If academic year is selected, filter by year and month
            if ($academicYearDates) {
                $payrollYear = Carbon::parse($academicYearDates['start_date'])->year;
                $payrollQuery->where('year', $payrollYear)->where('month', $i);
            }
            // If no academic year selected, get all-time data for this month across all years
            else {
                $payrollQuery->where('month', $i);
            }

            $payrollExpense = $payrollQuery->sum('net_salary');

            // Asset purchase expenses - filter by branch if provided
            $assetQuery = PurchaseOrder::whereIn('status', ['approved', 'completed']);

            if ($branchId) {
                $assetQuery->where('branch_id', $branchId);
            }

            // If academic year is selected, filter by date range
            if ($academicYearDates) {
                $academicStartYear = Carbon::parse($academicYearDates['start_date'])->year;
                $startDate = Carbon::create($academicStartYear, $i, 1)->startOfMonth();
                $endDate = Carbon::create($academicStartYear, $i, 1)->endOfMonth();
                $assetQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
            // If no academic year selected, get all-time data for this month across all years
            else {
                $assetQuery->whereRaw('MONTH(created_at) = ?', [$i]);
            }

            $assetExpense = $assetQuery->sum('total_cost');

            $expenseData[] = (int) ($payrollExpense + $assetExpense);
        }

        return response()->json([
            'months' => $months,
            'revenue' => $revenueData,
            'expenses' => $expenseData,
            'profit' => array_map(function ($rev, $exp) {
                return $rev - $exp;
            }, $revenueData, $expenseData)
        ]);
    }

    public function getStudentGrowthData(Request $request)
    {
        $branchId = $request->branch_id;
        $academicYearId = $request->academic_year_id;
        $dateRange = $request->date_range;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Build base query for students
        $studentQuery = Student::whereNull('deleted_at');

        // Apply branch filter
        if ($branchId) {
            $studentQuery->where('branch_id', $branchId);
        }

        // Apply academic year filter
        if ($academicYearId) {
            $academicYear = BranchAcademicYear::where('academic_year_id', $academicYearId)->first();
            if ($academicYear) {
                $studentQuery->whereBetween('created_at', [
                    $academicYear->start_date,
                    $academicYear->end_date
                ]);
            }
        }

        // Apply date range filter
        if ($dateRange && $dateRange !== 'all') {
            $now = now();
            switch ($dateRange) {
                case 'current_year':
                    $studentQuery->whereYear('created_at', $now->year);
                    break;
                case 'last_6_months':
                    $studentQuery->where('created_at', '>=', $now->subMonths(6));
                    break;
                case 'last_3_months':
                    $studentQuery->where('created_at', '>=', $now->subMonths(3));
                    break;
                case 'custom':
                    if ($startDate && $endDate) {
                        $studentQuery->whereBetween('created_at', [
                            Carbon::parse($startDate)->startOfDay(),
                            Carbon::parse($endDate)->endOfDay()
                        ]);
                    }
                    break;
            }
        }

        // Get student counts by status
        $totalStudents = $studentQuery->count();
        $activeStudents = (clone $studentQuery)->where('status', 'on_roll')->count();
        $newRegistrations = (clone $studentQuery)->where('status', 'registered')->count();
        $processing = (clone $studentQuery)->where(function ($query) {
            $query->whereNull('status')
                  ->orWhere('status', '')
                  ->orWhere('status', 'processing');
        })->count();
        $withdrawn = (clone $studentQuery)->where('status', 'left')->count();

        // Calculate percentages
        $total = $totalStudents > 0 ? $totalStudents : 1;
        $activePercentage = round(($activeStudents / $total) * 100, 1);
        $newRegPercentage = round(($newRegistrations / $total) * 100, 1);
        $processingPercentage = round(($processing / $total) * 100, 1);
        $withdrawnPercentage = round(($withdrawn / $total) * 100, 1);

        return response()->json([
            'data' => [
                ['value' => $activeStudents, 'name' => 'On Roll', 'percentage' => $activePercentage],
                ['value' => $newRegistrations, 'name' => 'New Registrations', 'percentage' => $newRegPercentage],
                ['value' => $processing, 'name' => 'Processing', 'percentage' => $processingPercentage],
                ['value' => $withdrawn, 'name' => 'Withdrawn', 'percentage' => $withdrawnPercentage]
            ],
            'total' => $totalStudents,
            'last_updated' => now()->format('M d, Y H:i')
        ]);
    }

    public function getDashboardMetrics(Request $request)
    {
        $branchId = $request->branch_id;
        $academicYearId = $request->academic_year_id;
        $dateRange = $request->date_range;

        // Build base queries
        $studentQuery = Student::whereNull('deleted_at');
        $employeeQuery = Employee::whereNull('left_date');
        $branchQuery = Branch::query();

        // Apply branch filter
        if ($branchId) {
            $studentQuery->where('branch_id', $branchId);
            $employeeQuery->where('branch_id', $branchId);
        }

        // Apply academic year filter
        if ($academicYearId) {
            $academicYear = BranchAcademicYear::where('academic_year_id', $academicYearId)->first();
            if ($academicYear) {
                $studentQuery->whereBetween('created_at', [
                    $academicYear->start_date,
                    $academicYear->end_date
                ]);
            }
        }

        // Apply date range filter
        if ($dateRange && $dateRange !== 'all') {
            $now = now();
            switch ($dateRange) {
                case 'current_year':
                    $studentQuery->whereYear('created_at', $now->year);
                    break;
                case 'last_6_months':
                    $studentQuery->where('created_at', '>=', $now->subMonths(6));
                    break;
                case 'last_3_months':
                    $studentQuery->where('created_at', '>=', $now->subMonths(3));
                    break;
            }
        }

        // Get counts
        $totalStudents = $studentQuery->count();
        $totalEmployees = $employeeQuery->count();
        $totalBranches = $branchQuery->count();

        $onRollStudents = (clone $studentQuery)->where('status', 'on_roll')->count();
        $registeredStudents = (clone $studentQuery)->where('status', 'registered')->count();
        $leftStudents = (clone $studentQuery)->where('status', 'left')->count();

        // Calculate metrics
        $studentTeacherRatio = $totalStudents > 0 && $totalEmployees > 0 ? round($totalStudents / $totalEmployees, 1) : 0;
        $avgStudentsPerBranch = $totalBranches > 0 ? round($totalStudents / $totalBranches, 1) : 0;
        $staffPerBranch = $totalBranches > 0 ? round($totalEmployees / $totalBranches, 1) : 0;

        $retentionRate = ($onRollStudents + $leftStudents) > 0 ? round(($onRollStudents / ($onRollStudents + $leftStudents)) * 100, 1) : 0;
        $newAdmissionsRate = ($onRollStudents + $registeredStudents) > 0 ? round(($registeredStudents / ($onRollStudents + $registeredStudents)) * 100, 1) : 0;
        $withdrawalRate = ($onRollStudents + $leftStudents) > 0 ? round(($leftStudents / ($onRollStudents + $leftStudents)) * 100, 1) : 0;

        // System health metrics (these could be made dynamic based on actual system monitoring)
        $systemUptime = 99.9; // This could be calculated from actual uptime logs
        $dataIntegrity = 100.0; // This could be calculated from data validation checks
        $securityScore = 'A+'; // This could be calculated from security assessments

        return response()->json([
            'staff_efficiency' => [
                'student_teacher_ratio' => $studentTeacherRatio,
                'avg_students_per_branch' => $avgStudentsPerBranch,
                'staff_per_branch' => $staffPerBranch
            ],
            'academic_performance' => [
                'retention_rate' => $retentionRate,
                'new_admissions_rate' => $newAdmissionsRate,
                'withdrawal_rate' => $withdrawalRate
            ],
            'system_health' => [
                'system_uptime' => $systemUptime,
                'data_integrity' => $dataIntegrity,
                'security_score' => $securityScore
            ],
            'totals' => [
                'students' => $totalStudents,
                'employees' => $totalEmployees,
                'branches' => $totalBranches
            ],
            'last_updated' => now()->format('M d, Y H:i')
        ]);
    }

    public function getBranchesPerformanceData(Request $request)
    {
        // Get all branches
        $branches = Branch::select('id', 'br_name')->get();

        $branchData = [];

        foreach ($branches as $branch) {
            $branchId = $branch->id;
            $branchName = $branch->br_name;

            // Calculate revenue (all time)
            $revenue = StudentInvoice::where('is_paid', 1)
                ->whereHas('student', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->sum('paid_amount');

            // Calculate expenses (all time)
            // Payroll expenses
            $payrollExpense = Payroll::whereIn('status', ['paid', 'processed'])
                ->whereHas('employee', function ($q) use ($branchId) {
                    $q->where('branch_id', $branchId);
                })
                ->sum('net_salary');

            // Asset purchase expenses
            $assetExpense = PurchaseOrder::whereIn('status', ['approved', 'completed'])
                ->where('branch_id', $branchId)
                ->sum('total_cost');

            $totalExpenses = $payrollExpense + $assetExpense;

            // Calculate profit
            $profit = $revenue - $totalExpenses;

            // Only include branches where expenses < revenue (profitable branches)
            if ($totalExpenses < $revenue && $profit > 0) {
                $branchData[] = [
                    'name' => $branchName,
                    'value' => round($profit, 2),
                    'revenue' => round($revenue, 2),
                    'expenses' => round($totalExpenses, 2),
                    'profit' => round($profit, 2)
                ];
            }
        }

        // Sort by profit (descending)
        usort($branchData, function ($a, $b) {
            return $b['profit'] <=> $a['profit'];
        });

        // Get top 10 profitable branches
        $topBranches = array_slice($branchData, 0, 10);

        return response()->json([
            'branches' => $topBranches,
            'metric' => 'profit',
            'last_updated' => now()->format('M d, Y H:i')
        ]);
    }

    public function getBranchWiseStaffStudentsData(Request $request)
    {
        $academicYearId = $request->academic_year_id;

        // Get all branches with their staff and student counts
        $branches = Branch::select('id', 'br_name')->get();

        $branchData = [];

        foreach ($branches as $branch) {
            $branchId = $branch->id;
            $branchName = $branch->br_name;

            // Get student count for this branch
            $studentQuery = Student::where('branch_id', $branchId)->whereNull('deleted_at');

            // Apply academic year filter if provided
            if ($academicYearId) {
                $academicYear = BranchAcademicYear::where('academic_year_id', $academicYearId)->first();
                if ($academicYear) {
                    $studentQuery->whereBetween('created_at', [
                        $academicYear->start_date,
                        $academicYear->end_date
                    ]);
                }
            }

            $totalStudents = $studentQuery->count();
            $onRollStudents = (clone $studentQuery)->where('status', 'on_roll')->count();
            $registeredStudents = (clone $studentQuery)->where('status', 'registered')->count();
            $leftStudents = (clone $studentQuery)->where('status', 'left')->count();

            // Get staff count for this branch
            $staffQuery = Employee::where('branch_id', $branchId)->whereNull('left_date');

            // Apply academic year filter if provided
            if ($academicYearId) {
                $academicYear = BranchAcademicYear::where('academic_year_id', $academicYearId)->first();
                if ($academicYear) {
                    $staffQuery->whereBetween('created_at', [
                        $academicYear->start_date,
                        $academicYear->end_date
                    ]);
                }
            }

            $totalStaff = $staffQuery->count();

            // Only include branches with data
            if ($totalStudents > 0 || $totalStaff > 0) {
                $branchData[] = [
                    'branch_name' => $branchName,
                    'total_students' => $totalStudents,
                    'on_roll_students' => $onRollStudents,
                    'registered_students' => $registeredStudents,
                    'left_students' => $leftStudents,
                    'total_staff' => $totalStaff,
                    'student_staff_ratio' => $totalStaff > 0 ? round($totalStudents / $totalStaff, 2) : 0
                ];
            }
        }

        // Sort by total students (descending)
        usort($branchData, function ($a, $b) {
            return $b['total_students'] <=> $a['total_students'];
        });

        return response()->json([
            'branches' => $branchData,
            'last_updated' => now()->format('M d, Y H:i')
        ]);
    }
}
