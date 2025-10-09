<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Employee;
use App\Models\FranchiseApplicationBdVisit;
use App\Models\NewSchoolFeeStructure;
use App\Models\StudentWithdrawal;
use App\Models\VisitDetail;
use App\Models\User;
use App\Models\Source;
use App\Models\FranchiseApplicationAttachmentType;
use App\Models\ClassGroup;
use Carbon\Carbon;
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

class QAController extends Controller
{
    public function index(Request $request)
    {
        // $this->dd_visit_request($request);
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
        $data['punjab_count'] = Branch::where('region_id', 1)->whereNotIn('id', [1, 2, 3])->count();
        $data['sindh_count'] = Branch::where('region_id', 3)->whereNotIn('id', [1, 2, 3])->count();
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
            'employees.qa_dashboard.dashboard',
            $data,
            ['academic_years' => $academic_years, 'branches' => $branches, 'fee_structure_records' => $fee_structure_records, 'states' => $states, 'cities' => $cities, 'campus_types' => $campus_types, 'school_type' => $school_type]
        );
    }
}
