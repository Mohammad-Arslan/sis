<?php

namespace App\Http\Controllers;


use App\Models\City;
use App\Models\Employee;
use App\Models\FranchiseApplicationBdVisit;
use App\Models\FranchiseApplicationsAttachment;
use App\Models\NewSchoolFeeStructure;
use App\Models\StudentWithdrawal;
use App\Models\VisitDetail;
use App\Models\User;
use App\Models\Source;
use App\Models\FranchiseApplicationAttachmentType;
use App\Models\ClassGroup;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\CampusOfficeType;
use App\Models\Student;
use App\Models\Company;
use App\Models\Region;
use App\Models\TaxType;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Models\FranchiseApplication;

class DeputyDirectorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $this->dd_visit_request($request);
        if ($request->ajax()) {
            $data = Branch::with(['region', 'company', 'nwa.user', 'nwa.contact_information', 'class_group', 'contact_information.state']);

            if ($request->company_id && $request->company_id > 0) {
                $data->where('company_id', $request->company_id);
            }

            if ($request->region_id && $request->region_id > 0) {
                $data->where('region_id', $request->region_id);
            }

            if ($request->state_id && $request->state_id > 0) {
                $data->whereHas('contact_information', function ($q) use ($request) {
                    $q->where('state_id', $request->state_id);
                });
            }

            if ($request->nwa_id && $request->nwa_id > 0) {
                $data->whereHas('nwa', function ($query) use ($request) {
                    $query->where('nwa_id', $request->nwa_id);
                });
            }

            if ($request->searchTerm && $request->searchTerm != null) {
                //dd($data->get()->toArray());
                $data = $data->where('branch_code', 'like', '%' . $request->searchTerm . '%')
                    ->orWhere('br_name', 'like', '%' . $request->searchTerm . '%')
                    ->orWhere('build_purpose', 'like', '%' . $request->searchTerm . '%')
                    ->orWhere('website', 'like', '%' . $request->searchTerm . '%');
            }

            //$data = $data->get();
            $limit = $request->input('length');
            $start = $request->input('start');
            $totalData = $data->count();
            $totalFiltered = $totalData;

            $rows = $data->offset($start)->limit($limit)->get();
            $return_data = [];
            //dd($rows->toArray());
            // $totalData = $rows->count();
            // To create data
            foreach ($rows as $row) {
                $row['br_name'] = '<a href="' . route('branches.edit', $row->id) . '?tab=home' . ' ">' . $row->br_name . '</a>';
                $row['action'] =
                    '
                <a href="' . route('branches.edit', $row->id) . '?tab=home' . ' " class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                    <i class="mdi mdi-lead-pencil"></i>
                </a>
            ';
                $return_data[] = $row;
            }

            // To return data
            $data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $return_data
            );

            return $data;
        }

        $data['companies'] = Company::all();
        $data['regions'] = Region::all();
        $data['taxes'] = TaxType::all();
        $data['states'] = State::all();
        $branches = Branch::all();
        $academic_years = AcademicYear::all();
        // $data['active_students_count'] = get_active_student_count(get_set_NWABranchId());
        $data['punjab_count'] = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->count();
        $data['sindh_count'] = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->count();
        $data['onroll'] = Student::where([['status', 'on_roll']])->count();
        $data['register'] = Student::where([['status', 'registered']])->count();
        $data['processing'] = Student::where('status', null)->count();
        $data['left'] = Student::where([['status', 'left']])->count();
        // $data['register_students_revenue'] = $data['register_students_count'] * 500;
        // $data['nwa_branch_id'] = get_branch_id();
        $states = State::all();
        $school_type = ClassGroup::all();
        $cities = City::all();
        $campus_types = CampusOfficeType::all();
        // $fee_structure_records_popup = FeeStructureDetail::all();
        $fee_structure_records = NewSchoolFeeStructure::with([
            'new_fee_structure_details' => function ($query) {
                $query->whereIn('fee_status_by_dd', ['Approved', 'Pending', 'Rejected']);
            }
        ])->get();
        return view(
            'employees.dashboard.dd-dashboard',
            $data,
            ['academic_years' => $academic_years, 'branches' => $branches, 'fee_structure_records' => $fee_structure_records, 'states' => $states, 'cities' => $cities, 'campus_types' => $campus_types, 'school_type' => $school_type]
        );
    }

    public function show(Request $request)
    {
        $query = FranchiseApplication::where('id', $request->id)->with(['states', 'cities', 'source', 'other_informations', 'franchise_application_qa', 'franchise_application_bd', 'franchise_application_tor', 'franchise_application_legal', 'franchise_application_dd'])->get();
        $bd_id = isset($query[0]->toArray()['franchise_application_bd']['approved_by']) ? $query[0]->toArray()['franchise_application_bd']['approved_by'] : 0;
        $bd_name = (isset($bd_id) && $bd_id != 0) ? Employee::where('id', $bd_id)->get('preferred_name') : [];
        $dd_id = isset($query[0]->toArray()['franchise_application_dd']['review_by']) ? $query[0]->toArray()['franchise_application_dd']['review_by'] : 0;
        $dd_name = (isset($dd_id) && $dd_id != 0) ? User::where('id', $dd_id)->get('name') : [];
        $tor_id = isset($query[0]->toArray()['franchise_application_tor']['review_by']) ? $query[0]->toArray()['franchise_application_tor']['review_by'] : 0;
        $tor_name = (isset($tor_id) && $tor_id != 0) ? User::where('id', $tor_id)->get('name') : [];
        $legal_id = isset($query[0]->toArray()['franchise_application_legal']['review_by']) ? $query[0]->toArray()['franchise_application_legal']['review_by'] : 0;
        $legal_name = (isset($legal_id) && $legal_id != 0) ? User::where('id', $legal_id)->get('name') : [];
        $qa_id = isset($query[0]->toArray()['franchise_application_qa']['qa_rep_id']) ? $query[0]->toArray()['franchise_application_qa']['qa_rep_id'] : 0;
        $qa_name = (isset($qa_id) && $qa_id != 0) ? Employee::where('id', $qa_id)->get('preferred_name') : [];
        // dd($query->toArray());
        return view('employees.dashboard.onboarding_status_modal', ['query' => $query, 'bd_name' => $bd_name, 'dd_name' => $dd_name, 'tor_name' => $tor_name, 'legal_name' => $legal_name, 'qa_name' => $qa_name]);
        // return view('students.invoice_detail_modal', ['student_invoice' => $studentInvoice, 'total' => $calculations]);
    }

    public function franchiseApplicationDocs(Request $request)
    {
        $franchise_application_id = $request->id;
        $details = FranchiseApplicationsAttachment::with(['user','attachment_type'])->where(['franchise_application_id' => $franchise_application_id]);

        if (!auth()->user())
            $details = $details->whereNull('uploaded_by');
        $details = $details->get();

        $rows = view('employees.dashboard.document_list_modal', ['franchise_application_attachments' => $details])->render();

        return $rows;
    }
    public function graph_data(Request $request)
    {

        if ($request->ajax()) {
            $month = ['08', '09', '10', '11', '12', '01', '02', '03', '04', '05', '06', '07']; // ['admission_wef', '2022-08-1'],
            //    dd($month);
            $data = [];
            $data1 = [];
            $data2 = [];
            $data['registered'] = [];
            $data1['on_roll'] = [];
            $data2['lefts'] = [];
            if ($request->academic_year_id_graph != null) {

                foreach ($month as $key => $value) {
                    if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                        $region_id = get_state_id();
                        if ($region_id == 1) {
                            $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($punjab_branches as $key => $branches) {
                                $data[] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $punjab_branches)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                                $data['registered'] = $data;
                            // }
                        } else if ($region_id == 3) {
                            $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');

                            // foreach ($sindh_branches as $key =>$branches) {
                                $data[] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $sindh_branches)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                                $data['registered'] = $data;
                            // }
                        }
                    } else if (auth()->user()->hasRole('network_associate')) {
                        $branch_id = get_NWABranchCode();
                        $data[] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->where('branch_id', $branch_id)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data['registered'] = $data;

                    } else {
                        $data[] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data['registered'] = $data;
                    }
                }

                foreach ($month as $key => $value) {
                    if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                        $region_id = get_state_id();
                        if ($region_id == 1) {
                            $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($punjab_branches as $key => $branches) {
                                $data1[] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $punjab_branches)->where(DB::raw('MONTH(admission_wef)'),$value )->count();
                                $data1['on_roll'] = $data1;
                                // print_r($data);
                            // }
                        } else if ($region_id == 3) {
                            $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($sindh_branches as $key => $branches) {
                                $data1[] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $sindh_branches)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                                $data1['on_roll'] = $data1;
                            // }
                        }
                    } else if (auth()->user()->hasRole('network_associate')) {
                        $branch_id = get_NWABranchCode();
                        $data1[] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->where('branch_id', $branch_id)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data1['on_roll'] = $data1;
                    } else {
                        $data1[] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data1['on_roll'] = $data1;
                    }
                }
                foreach ($month as $key => $value) {
                    // $data2[] = StudentWithdrawal::query()->with(['students' => function ($query) use ($request, $value) {
                    //     $query->where([['status', 'left'],['academic_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value);
                    // }])->count();
                    if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                        $region_id = get_state_id();
                        if ($region_id == 1) {
                            $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($punjab_branches as $key => $branches) {

                                $data2[] = StudentWithdrawal::whereHas(
                                    'student' , function ($q) use ($punjab_branches) {
                                        // Query the name field in status table
                                        $q->where('status', '=', 'left')->whereIn('branch_id', $punjab_branches); // '=' is optional
                                    }
                                )
                                    ->where(DB::raw('MONTH(last_day_at)'), $value)->where('academic_year_id', $request->academic_year_id_graph)
                                    ->count();
                                // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();
                                $data2['lefts'] = $data2;
                            // }
                        } else if ($region_id == 3) {
                            $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($sindh_branches as $key => $branches) {

                                $data2[] = StudentWithdrawal::whereHas(
                                    'student' , function ($q) use ($sindh_branches) {
                                        $q->where('status', '=', 'left')->whereIn('branch_id', $sindh_branches); // '=' is optional
                                    }
                                )
                                    ->where(DB::raw('MONTH(last_day_at)'), $value)->where('academic_year_id', $request->academic_year_id_graph)
                                    ->count();
                                $data2['lefts'] = $data2;
                            // }
                        }
                    } else if (auth()->user()->hasRole('network_associate')) {
                        $branch_id = get_NWABranchCode();
                        $data2[] = StudentWithdrawal::whereHas(
                            'student' , function ($q) use ($branch_id) {
                                // Query the name field in status table
                                $q->where([['status', '=', 'left'], ['branch_id', '=' ,$branch_id]]); // '=' is optional
                            }
                        )
                            ->where(DB::raw('MONTH(last_day_at)'), $value)->where('academic_year_id', $request->academic_year_id_graph)
                            ->count();
                        // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();
                        $data2['lefts'] = $data2;
                    } else {
                        $data2[] = StudentWithdrawal::whereHas(
                            'student' , function ($q) {
                                // Query the name field in status table
                                $q->where('status', '=', 'left')->whereNotIn('branch_id', [1,2,3,22,23,24,27,28]); // '=' is optional
                            }
                        )
                            ->where(DB::raw('MONTH(last_day_at)'), $value)->where('academic_year_id', $request->academic_year_id_graph)
                            ->count();
                        // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();
                        $data2['lefts'] = $data2;

                    }
                }

                if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                    $region_id = get_state_id();
                    if ($region_id == 1) {
                        $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($punjab_branches as $key => $branches) {
                            $data['register'] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $punjab_branches)->count();
                            $data['processing'] = Student::where('status', null)->whereIn('branch_id', $punjab_branches)->count();

                            $data['onroll'] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $punjab_branches)->count();
                            $data['left'] = StudentWithdrawal::whereHas(
                                'student' , function ($q) use ($punjab_branches) {
                                    // Query the name field in status table
                                    $q->where('status', '=', 'left')->whereIn('branch_id', $punjab_branches); // '=' is optional
                                }
                            )
                            ->where('academic_year_id', $request->academic_year_id_graph)
                                ->count();
                    // }
                    } else if ($region_id == 3) {
                        $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($sindh_branches as $key => $branches) {
                        $data['register'] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $sindh_branches)->count();
                        $data['processing'] = Student::where('status', null)->whereIn('branch_id', $sindh_branches)->count();

                        $data['onroll'] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->whereIn('branch_id', $sindh_branches)->count();
                            $data['left'] = StudentWithdrawal::whereHas(
                                'student' , function ($q) use ($sindh_branches) {
                                    // Query the name field in status table
                                    $q->where('status', '=', 'left')->whereIn('branch_id', $sindh_branches); // '=' is optional
                                }
                            )
                            ->where('academic_year_id', $request->academic_year_id_graph)
                                ->count();
                        // }
                    }
                } else if (auth()->user()->hasRole('network_associate')) {
                    $branch_id = get_NWABranchCode();
                    $data['register'] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->where('branch_id', $branch_id)->count();
                    $data['processing'] = Student::where('status', null)->where('branch_id', $branch_id)->count();

                    $data['onroll'] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->where('branch_id', $branch_id)->count();
                    $data['left']= StudentWithdrawal::whereHas(
                        'student' , function ($q) use ( $branch_id) {
                            // Query the name field in status table
                            $q->where('status', '=', 'left')->where('branch_id', $branch_id); // '=' is optional
                        }
                    )
                        ->where('academic_year_id', $request->academic_year_id_graph)
                        ->count();
                } else {
                    $data['register'] = Student::where([['status', 'registered'], ['admission_year_id', $request->academic_year_id_graph]])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->count();
                    $data['processing'] = Student::where('status', null)->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->count();

                    $data['onroll'] = Student::where([['status', 'on_roll'], ['admission_year_id', $request->academic_year_id_graph]])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->count();
                    $data['left'] = StudentWithdrawal::whereHas(
                        'student' , function ($q) {
                            // Query the name field in status table
                            $q->where('status', '=', 'left')->whereNotIn('branch_id', [1,2,3,22,23,24,27,28]); // '=' is optional
                        }
                    )
                        ->where('academic_year_id', $request->academic_year_id_graph)
                        ->count();
                }

            }

            if ($request->academic_year_id_graph == null) {

                if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                    $region_id = get_state_id();
                    if ($region_id == 1) {
                        $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($punjab_branches as $key => $branches) {
                        $data['onroll'] = Student::where([['status', 'on_roll']])->whereIn('branch_id', $punjab_branches)->count();
                        // }
                    } else if ($region_id == 3) {
                        $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($sindh_branches as $key => $branches) {
                            $data['onroll'] = Student::where([['status', 'on_roll']])->whereIn('branch_id', $sindh_branches)->count();
                        // }
                    }
                } else if (auth()->user()->hasRole('network_associate')) {
                    $branch_id = get_NWABranchCode();
                    $data['onroll'] = Student::where([['status', 'on_roll']])->where('branch_id', $branch_id)->count();

                } else {
                    $data['onroll'] = Student::where([['status', 'on_roll']])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->count();
                }
                if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                    $region_id = get_state_id();
                    if ($region_id == 1) {
                        $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($punjab_branches as $key => $branches) {
                            $data['processing'] = Student::where('status', null)->whereIn('branch_id', $punjab_branches)->count();
                        // }
                    } else if ($region_id == 3) {
                        $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($sindh_branches as $key => $branches) {
                            $data['processing'] = Student::where('status', null)->whereIn('branch_id', $sindh_branches)->count();
                        // }
                    }
                } else if (auth()->user()->hasRole('network_associate')) {
                    $branch_id = get_NWABranchCode();
                    $data['processing'] = Student::where('status', null)->where('branch_id', $branch_id)->count();

                } else {
                    $data['processing'] = Student::where('status', null)->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->count();
                }
                if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                    $region_id = get_state_id();
                    if ($region_id == 1) {
                        $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($punjab_branches as $key => $branches) {
                        $data['register'] = Student::where([['status', 'registered']])->whereIn('branch_id', $punjab_branches)->count();
                        // }
                    } else if ($region_id == 3) {
                        $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($sindh_branches as $key => $branches) {
                            $data['register'] = Student::where([['status', 'registered']])->whereIn('branch_id', $sindh_branches)->count();
                        // }
                    }
                } else if (auth()->user()->hasRole('network_associate')) {
                    $branch_id = get_NWABranchCode();
                    $data['register'] = Student::where([['status', 'registered']])->where('branch_id', $branch_id)->count();

                } else {
                    $data['register'] = Student::where([['status', 'registered']])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->count();
                }

                if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                    $region_id = get_state_id();
                    if ($region_id == 1) {
                        $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($punjab_branches as $key => $branches) {

                            $data['left'] = StudentWithdrawal::whereHas(
                                'student', function ($q) use ( $punjab_branches) {
                                    // Query the name field in status table
                                    $q->where('status', '=', 'left')->whereIn('branch_id', $punjab_branches); // '=' is optional
                                }
                            )
                                ->count();
                            // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();
                        // }
                    } else if ($region_id == 3) {
                        $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                        // foreach ($sindh_branches as $key => $branches) {

                            $data['left'] = StudentWithdrawal::whereHas(
                                'student' ,function ($q) use ($sindh_branches) {
                                    $q->where('status', '=', 'left')->whereIn('branch_id', $sindh_branches); // '=' is optional
                                }
                            )
                                ->count();

                        // }
                    }
                } else if (auth()->user()->hasRole('network_associate')) {
                    $branch_id = get_NWABranchCode();
                    $data['left']= StudentWithdrawal::whereHas(
                        'student', function ($q) use ($request, $branch_id) {
                            // Query the name field in status table
                            $q->where('status', '=', 'left')->where('branch_id', $branch_id); // '=' is optional
                        }
                    )
                        ->count();


                } else {
                    $data['left'] = StudentWithdrawal::whereHas(
                        'student' , function ($q) {
                            // Query the name field in status table
                            $q->where('status', '=', 'left')->whereNotIn('branch_id', [1,2,3,22,23,24,27,28]); // '=' is optional
                        }
                    )
                        ->count();
                    // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();


                }


                foreach ($month as $key => $value) {
                    // $data[] = Student::where([['status', 'registered']])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                    // $data['registered'] = $data;

                    // $data1[] = Student::where([['status', 'on_roll']])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                    // $data1['on_roll'] = $data1;

                    // $data2[] = StudentWithdrawal::with([
                    //     'students' => function ($q) use ($request, $value) {
                    //         $q->where('status', '=', 'left')->whereNotIn('branch_id', [1,2,3,22,23,24,27,28]);
                    //     }
                    // ])
                    //     ->where(DB::raw('MONTH(last_day_at)'), $value)
                    //     ->count();
                    // $data2['lefts'] = $data2;


                    if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                        $region_id = get_state_id();
                        if ($region_id == 1) {
                            $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($punjab_branches as $key => $branches) {
                                $data[] = Student::where([['status', 'registered']])->whereIn('branch_id', $punjab_branches)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                                $data['registered'] = $data;
                            // }
                        } else if ($region_id == 3) {
                            $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($sindh_branches as $key => $branches) {
                                $data[] = Student::where([['status', 'registered']])->whereIn('branch_id', $sindh_branches)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                                $data['registered'] = $data;
                            // }
                        }
                    } else if (auth()->user()->hasRole('network_associate')) {
                        $branch_id = get_NWABranchCode();
                        $data[] = Student::where([['status', 'registered']])->where('branch_id', $branch_id)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data['registered'] = $data;
                    } else {
                        $data[] = Student::where([['status', 'registered']])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data['registered'] = $data;
                    }

                    if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                        $region_id = get_state_id();
                        if ($region_id == 1) {
                            $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($punjab_branches as $key => $branches) {
                                $data1[] = Student::where([['status', 'on_roll']])->whereIn('branch_id', $punjab_branches)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                                $data1['on_roll'] = $data1;
                            // }
                        } else if ($region_id == 3) {
                            $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($sindh_branches as $key => $branches) {
                                $data1[] = Student::where([['status', 'on_roll']])->whereIn('branch_id', $sindh_branches)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                                $data1['on_roll'] = $data1;
                            // }
                        }
                    } else if (auth()->user()->hasRole('network_associate')) {
                        $branch_id = get_NWABranchCode();
                        $data1[] = Student::where([['status', 'on_roll']])->where('branch_id', $branch_id)->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data1['on_roll'] = $data1;
                    } else {
                        $data1[] = Student::where([['status', 'on_roll']])->whereNotIn('branch_id', [1,2,3,22,23,24,27,28])->where(DB::raw('MONTH(admission_wef)'), $value)->count();
                        $data1['on_roll'] = $data1;
                    }

                    if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')) {

                        $region_id = get_state_id();
                        if ($region_id == 1) {
                            $punjab_branches = Branch::where('region_id', 1)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($punjab_branches as $key => $branches) {

                                $data2[] = StudentWithdrawal::whereHas(
                                    'student' , function ($q) use ($punjab_branches) {
                                        // Query the name field in status table
                                        $q->where('status', '=', 'left')->whereIn('branch_id', $punjab_branches); // '=' is optional
                                    }
                                )
                                    ->where(DB::raw('MONTH(last_day_at)'), $value)
                                    ->count();
                                // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();
                                $data2['lefts'] = $data2;
                            // }
                        } else if ($region_id == 3) {
                            $sindh_branches = Branch::where('region_id', 3)->whereNotIn('id', [1,2,3,22,23,24,27,28])->pluck('id');
                            // foreach ($sindh_branches as $key => $branches) {

                                $data2[] = StudentWithdrawal::whereHas(
                                    'student' , function ($q) use ( $sindh_branches) {
                                        $q->where('status', '=', 'left')->whereIn('branch_id', $sindh_branches); // '=' is optional
                                    }
                                )
                                    ->where(DB::raw('MONTH(last_day_at)'), $value)
                                    ->count();
                                $data2['lefts'] = $data2;
                            // }
                        }
                    } else if (auth()->user()->hasRole('network_associate')) {
                        $branch_id = get_NWABranchCode();
                        $data2[] = StudentWithdrawal::whereHas(
                            'student' , function ($q) use ($request, $branch_id) {
                                // Query the name field in status table
                                $q->where('status', '=', 'left')->where('branch_id', $branch_id); // '=' is optional
                            }
                        )
                            ->where(DB::raw('MONTH(last_day_at)'), $value)
                            ->count();
                        // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();
                        $data2['lefts'] = $data2;

                    } else {
                        $data2[] = StudentWithdrawal::whereHas(
                            'student' , function ($q) use ($request, $value) {
                                // Query the name field in status table
                                $q->where('status', '=', 'left')->whereNotIn('branch_id', [1,2,3,22,23,24,27,28]); // '=' is optional
                            }
                        )
                            ->where(DB::raw('MONTH(last_day_at)'), $value)
                            ->count();
                        // $data2[] = Student::with(['student_withdrawals'])->where([['status', 'left'],['admission_year_id', $request->academic_year_id_graph]])->where(DB::raw('MONTH(last_day_at)'), $value)->count();
                        $data2['lefts'] = $data2;

                    }
                }
            }
            // Student::where([['status', 'left'],['academic_year_id', $request->academic_year_id_graph]])->count();
            return [$data, $data1, $data2];
        }
        return;

    }

    public function student_details_card(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')){
                $region_id = get_state_id();
                        if ($region_id) {
            $data = Branch::where('region_id', $region_id)->whereNotIn('id', [1,2,3,22,23,24,27,28])->with([
                'employee' => function ($query) {
                    $query->select('branch_id', 'status', 'admission_year_id', 'admission_wef');
                }
            ])->with([
                        'employee' => function ($query) {
                            $query->select('branch_id', 'user_id');
                        }
                    ]);
                }
            }
            else{
            $data = Branch::whereNotIn('id', [1,2,3,22,23,24,27,28])->with([
                'employee' => function ($query) {
                    $query->select('branch_id', 'status', 'admission_year_id', 'admission_wef');
                }
            ])->with([
                        'employee' => function ($query) {
                            $query->select('branch_id', 'user_id');
                        }
                    ]);
            }
            //  dd($data->get()->toArray());
            if ($request->academic_year_id) {
                // $data = $data->where('admission_year_id', $request->admission_year_id);
                $data = $data->whereHas('students', function ($query) use ($request) {
                    $query->select('branch_id', 'status', 'admission_year_id', 'admission_wef')->where('admission_year_id', $request->academic_year_id);
                });
            }
            if ($request->region_id) {
                // $data = $data->where('admission_year_id', $request->admission_year_id);
                $data = $data->where('region_id', $request->region_id);

            }
            // if ($request->audience) {
            //     $data = $data->where('audience', $request->audience);
            // }
            if ($request->branch_id) {
                $data = $data->where('id', $request->branch_id);

            }
            if ($request->state_id) {
                $data = $data->where('state_id', $request->state_id);

            }

            if ($request->start_date && $request->end_date) {
                $data = $data->whereHas('students', function ($query) use ($request) {
                    $query->select('branch_id', 'status', 'admission_year_id', 'admission_wef')
                        ->whereBetween('admission_wef', [Carbon::parse($request->start_date)->format('Y-m-d'), Carbon::parse($request->end_date)->format('Y-m-d')]);
                });
            } else if ($request->start_date) {
                $data = $data->whereHas('students', function ($query) use ($request) {
                    $query->select('branch_id', 'status', 'admission_year_id', 'admission_wef')
                        ->whereIn('admission_wef', [Carbon::parse($request->start_date)->format('Y-m-d')]);
                });
            }
            // dd($data->get());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_code', function ($row) {
                    return $row->branch_code;
                })
                ->addColumn('br_name', function ($row) {
                    return $row->br_name;
                })
                ->addColumn('on_roll_students', function ($row) {
                    $row = Student::where([['status', 'on_roll'], ['branch_id', $row->id]])->count();
                    return $row;
                })
                ->addColumn('registered_students', function ($row) {
                    $row = Student::where([['status', 'registered'], ['branch_id', $row->id]])->count();
                    return $row;
                })
                ->addColumn('left_students', function ($row) {
                    $row = Student::where([['status', 'left'], ['branch_id', $row->id]])->count();
                    return $row;
                })
                ->addColumn('employee', function ($row) {
                    // dd($row->employee);
                    $row = Employee::where([['branch_id', $row->id]])->count();
                    ;
                    return $row;
                })
                ->rawColumns(['branch_code', 'br_name', 'on_roll_students', 'registered_students', 'left_students'])
                ->make(true);
        }
        return;// view('employees.dashboard.dd-dashboard');
    }
    public function on_boarding_list(Request $request)
    {

        if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')){
            $region_id = get_state_id();
            if($region_id == 1)
            {
                $states = State::where('id', 1)->get();

            }
            else if($region_id == 3){
                $states = State::where('id', 2)->get();
            }
        }
        else{

            $states = State::all();
        }
        $cities = City::all();
        $sources = Source::all();
        $attachmentTypes = FranchiseApplicationAttachmentType::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources,
            'attachment_types' => $attachmentTypes,
        ];
        if ($request->ajax()) {
            if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')){
                $region_id = get_state_id();
                if($region_id == 1)
                {
                    $query = FranchiseApplication::with(['states', 'cities', 'source', 'other_informations', 'franchise_application_qa', 'franchise_application_bd', 'franchise_application_tor', 'franchise_application_legal', 'franchise_application_dd'])->where('state_id', 1);

                }
                else if($region_id ==3){
                    $query = FranchiseApplication::with(['states', 'cities', 'source', 'other_informations', 'franchise_application_qa', 'franchise_application_bd', 'franchise_application_tor', 'franchise_application_legal', 'franchise_application_dd'])->where('state_id', 2);

                }
            }
            else{

                $query = FranchiseApplication::with(['states', 'cities', 'source', 'other_informations', 'franchise_application_qa', 'franchise_application_bd', 'franchise_application_tor', 'franchise_application_legal', 'franchise_application_dd']);
            }

            // dd($request->franchise_state_id);
            if ($request->state_id && $request->state_id > 0) {
                $query->where('state_id', $request->state_id);
            }

            if ($request->city_id && $request->city_id > 0) {
                $query->where('city_id', $request->city_id);
            }

            if($request->school_type && $request->school_type)
            {

                $query->whereHas('franchise_application_bd', function ($q) use ($request) {
                    $q->where('school_type', $request->school_type);
                });


            }
            if($request->status && $request->status)
            {
                $query->whereHas('franchise_application_dd', function ($q) use ($request) {
                    $q->where('status', $request->status );
                });
            }
            if ($request->searchTerm && $request->searchTerm != null) {

                $query->orWhere('appl_name', 'like', '%' . $request->searchTerm . '%');
            }

            $franchiseApplication = $query->get();
            $data['data'] = $franchiseApplication;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $full_name = $row['appl_name'] . ' ' . $row['appl_last_name'];
                    return auth()->user()->hasPermission('edit-franchise-application') ? '<a href="' . route('franchise-applications.edit', $row->id) . '">' . $full_name . '</a>' : $full_name;
                })
                ->addColumn('purposed_school_name', function ($row) {
                    $badge = isset($row['franchise_application_bd']) && $row['franchise_application_bd']->getRawOriginal('proposed_school_name') ? $row['franchise_application_bd']->getRawOriginal('proposed_school_name') : '-';
                    return $badge;
                })
                ->addColumn('school_type', function ($row) {
                    $badge = isset($row['franchise_application_bd']) && $row['franchise_application_bd']->getRawOriginal('school_configuration') ? $row['franchise_application_bd']->getRawOriginal('school_configuration') : '-';
                    return $badge;
                })
                ->addColumn('agreement_type', function ($row) {
                    $badge = $row['agreement_type'];
                    return $badge;
                })
                ->addColumn('total_franchise_fee', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('total_franchise_fee') ? $row['franchise_application_tor']->getRawOriginal('total_franchise_fee') : '-';
                    return $badge;
                })
                ->addColumn('amount_received', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('amount_received') ? $row['franchise_application_tor']->getRawOriginal('amount_received') : '-';
                    return $badge;
                })
                ->addColumn('agreement_date', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('agreement_date') ? Carbon::parse($row['franchise_application_tor']->getRawOriginal('agreement_date'))->format('d-m-Y') : '-';
                    return $badge;
                })
                ->addColumn('operational_date', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('operational_date') ? Carbon::parse($row['franchise_application_tor']->getRawOriginal('operational_date'))->format('d-m-Y') : '-';
                    return $badge;
                })
                ->addColumn('statuses', function ($row) {
                    return view('employees.dashboard.onboarding_status_list', ['row' => $row]);
                })
                ->addColumn('franchise_application_dd_status', function ($row) {
                    $badge = isset($row['franchise_application_dd']) && $row['franchise_application_dd']->getRawOriginal('status') == 'approved' ? '<span class="badge bg-success">Approved</span>' : (isset($row['franchise_application_dd']) && $row['franchise_application_dd']->getRawOriginal('status') == 'not_approved' ? '<span class="badge bg-danger">Not Approved</span>' : '<span class="badge bg-primary">Pending</span>');
                    return $badge;
                })


            ->addColumn('action', function ($row) {
                    if(auth()->user()->hasRole('deputy-director') || auth()->user()->hasRole('super_admin') ){
                    return view('employees.dashboard.action', ['row' => $row]);
                    }
                })
                ->addColumn('reports_action', function ($row) {
                    return view('employees.dashboard.reports-action', ['row' => $row]);
                })
                ->rawColumns([
                    'full_name',
                    'purposed_school_name',
                    'school_type',
                    'agreement_type',
                    'franchise_application_dd_status',
                    'action',
                    'reports_action'
                ])
                ->make(true);

        }
        return view('employees.dashboard.dd-dashboard', $data);
    }
    public function fee_structure_dd(Request $request)
    {
        $states = State::all();
        $cities = City::all();
        // $fee_structure_records_popup = FeeStructureDetail::all();
        if (auth()->user()->hasRole('head_of_bd') || auth()->user()->hasRole('bd_sales')){

        $fee_structure_records = NewSchoolFeeStructure::with([
            'new_fee_structure_details' => function ($query) {
                $query->whereIn('fee_status_by_dd',['Approved', 'Pending', 'Rejected']);
            }
        ])
        ->where('status_approval_date', '!=', null)->get();
        }
        else{
        $fee_structure_records = NewSchoolFeeStructure::with([
            'new_fee_structure_details' => function ($query) {
                $query->whereIn('fee_status_by_dd', ['Approved', 'Pending', 'Rejected']);
            }
        ])->get();
        }
        $academic_years = AcademicYear::all();
        return compact('fee_structure_records', 'academic_years', 'states', 'cities');
    }
    public function fee_structure_filter(Request $request)
    {
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');
        $academicYear = $request->input('academic_year_id');

        $query = NewSchoolFeeStructure::query();

        if ($stateId) {
            $query->whereHas('state', function ($q) use ($stateId) {
                $q->where('state_id', $stateId);
            });
        }
        if ($cityId) {
            $query->whereHas('city', function ($q) use ($cityId) {
                $q->where('city_id', $cityId);
            });
        }
        if ($academicYear) {
            $query->whereHas('academic_years', function ($q) use ($academicYear) {
                $q->where('academic_year_id', $academicYear);
            });
        }
        $savedRecords = $query->get();
        return view('employees.dashboard.dd-dashboard', compact('savedRecords'));
        /* $filteredData = $query->get();

        return response()->json($filteredData); */
    }
    public function dd_visit_request(Request $request)
    { {
            if ($request->ajax()) {
                $data = VisitDetail::where('approved_by', auth()->user()->id)->with('branch', 'user', 'campus', 'fromCity', 'toCity', 'approvedBy');


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
            return view('employees.dashboard.dd-dashboard', compact(['branches', 'cities', 'campus_types', 'employees']));
        }

    }
}
