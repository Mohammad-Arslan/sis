<?php

namespace App\Http\Controllers;

use Excel;
use Carbon\Carbon;
use App\Models\City;
use App\Models\Town;
use App\Models\Branch;
use App\Models\Source;
use App\Models\ComClass;
use App\Models\Employee;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\InquiriesType;
use App\Models\AdmissionQuery;
use Yajra\DataTables\DataTables;
use App\Models\ContactInformation;
use App\Exports\AdmissionQueryExport;
use App\Models\NetworkAssociate;

class AdmissionQueryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = [];
            // if(isset($request->branch_id))
            // {
            if (isHeadOfficeEmp() || isSuperAdmin()) {
                $data = AdmissionQuery::with(
                    'city',
                    'town',
                    'branch',
                    'com_class',
                    'source',
                    'academic_year',
                    'inquiry_type',
                )->orderByDesc('created_at');
            } else {
                $data = AdmissionQuery::where('branch_id', get_branch_id())->with(
                    'city',
                    'town',
                    'branch',
                    'com_class',
                    'source',
                    'academic_year',
                    'inquiry_type',
                )->orderByDesc('created_at');
            }

            if ($request->academic_year_id) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('academic_year_id', $request->academic_year_id);
                });
            }

            if ($request->branch_id) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('branch_id', $request->branch_id);
                });
            }

            if ($request->source_id) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('source_id', $request->source_id);
                });
            }

            if ($request->inquiry_type_id) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('inquiry_type_id', $request->inquiry_type_id);
                });
            }

            if (isset($request->class_id)) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('class_id', $request->class_id);
                    $query->orWhereNull('class_id');
                });
            }

            if (isset($request->city_id)) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('city_id', $request->city_id);
                    $query->orWhereNull('city_id');
                });
            }

            if (isset($request->town_id)) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('town_id', $request->town_id);
                    $query->orWhereNull('town_id');
                });
            }

            if (isset($request->from_date)) {
                $data = $data->whereDate('created_at', '>=', $request->from_date);
            }

            if (isset($request->to_date)) {
                $data = $data->whereDate('created_at', '<=', $request->to_date);
            }

                $data = $data->get();
            // }
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('inquiry_number', function ($row) {
                    return isset($row['inquiry_number']) ? $row['inquiry_number'] : '-';
                })
                ->addColumn('type', function ($row) {
                    return isset($row['inquiry_type']) ? $row['inquiry_type']['type'] : 'N/A';
                })
                ->addColumn('city_name', function ($row) {
                    return isset($row['city']) ? $row['city']['city_name'] : 'N/A';
                })
                ->addColumn('town_name', function ($row) {
                    return isset($row['town']) ? $row['town']['town_name'] : 'N/A';
                })
                ->addColumn('branch_name', function ($row) {
                    return isset($row['branch']) ? $row['branch']['br_name'] : 'N/A';
                })
                ->addColumn('class_name', function ($row) {
                    return isset($row['com_class']) ? $row['com_class']['class_name'] : 'N/A';
                })
                ->addColumn('source_name', function ($row) {
                    return isset($row['source']) ? $row['source']['source_name'] : 'N/A';
                })
                ->addColumn('application_date', function ($row) {
                    return isset($row['created_at']) ? Carbon::parse($row['created_at'])->format('Y-m-d') : 'N/A';
                })
                ->addColumn('academic_year', function ($row) {
                    return isset($row['academic_year']) ? $row['academic_year']['title'] : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('admission_queries.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        if (isHeadOfficeEmp() || isSuperAdmin()) {
            $data['academic_years'] = AcademicYear::all();
            $data['branches'] = Branch::get();
            $data['classes'] = ComClass::all();
            $data['cities'] = City::all();
            $data['towns'] = Town::all();
            $data['sources'] = Source::all();
            $data['inquirytypes'] = InquiriesType::all();
        } else {
            $data['academic_years'] = AcademicYear::all();
            $branch_id = get_branch_id();
            if (auth()->user()->hasRole('network_associate')) {
                $data['branches'] = Branch::where('id', $branch_id)->get();
                $branch_class_ids = get_branch_class_ids($data['branches'][0]->id);
                $data['classes'] = ComClass::whereIn('id', $branch_class_ids)->get();
                $nwa = NetworkAssociate::where('user_id', auth()->user()->id)->get();
                $nwa_info = ContactInformation::where('contact_informationable_id', $nwa[0]->id)->where('contact_informationable_type', 'App\Models\NetworkAssociate')->get();
                $data['cities'] = City::where('id', $nwa_info[0]->city_id)->get();
                $data['towns'] = Town::where('city_id', $nwa_info[0]->city_id)->get();
            } else {
                $data['branches'] = Branch::where('id', $branch_id)->get();
                $branch_class_ids = get_branch_class_ids($data['branches'][0]->id);
                $data['classes'] = ComClass::whereIn('id', $branch_class_ids)->get();
                $employee = Employee::where('id', auth()->user()->id)->get();
                $data['cities'] = City::where('id', $employee[0]->city_id)->get();
                $data['towns'] = Town::where('city_id', $employee[0]->city_id)->get();
            }
            $data['sources'] = Source::all();
            $data['inquirytypes'] = InquiriesType::all();
        }

        return view('admission_queries.index', $data);
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
        $request->validate([
            'student_name' => 'required',
            'student_age' => 'required',
            'parent_name' => 'required',
            'parent_email' => 'required',
            'parent_contact' => 'required',
            'inquiry_type_id' => 'required',
            'city_id' => 'required',
            'town_id' => 'required',
            'branch_id' => 'required',
            'class_id' => 'required',
            'source_id' => 'required',
        ]);

        $academicYear = AcademicYear::where('active', 1)->get('id')->toArray();
        $input = $request->all();
        $input['academic_year_id'] = $academicYear[0]['id'];
        $input['inquiry_number'] = random_int(10000000, 99999999);

        AdmissionQuery::create($input);

        return redirect()->route('admission-query.index')
        ->with('success', 'Admission inquiry has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AdmissionQuery $admission_query)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(AdmissionQuery $admission_query)
    {
        if (isHeadOfficeEmp() || isSuperAdmin()) {
            $academic_years = AcademicYear::all();
            $branches = Branch::all();
            $classes = ComClass::all();
            $cities = City::all();
            $towns = Town::all();
            $sources = Source::all();
            $inquirytypes = InquiriesType::all();
        } else {
            $academic_years = AcademicYear::all();
            $branch_id = get_branch_id();
            if (auth()->user()->hasRole('network_associate')) {
                $branches = Branch::where('id', $branch_id)->get();
                $branch_class_ids = get_branch_class_ids($branches[0]->id);
                $classes = ComClass::whereIn('id', $branch_class_ids)->get();
                $nwa = NetworkAssociate::where('user_id', auth()->user()->id)->get();
                $nwa_info = ContactInformation::where('contact_informationable_id', $nwa[0]->id)->where('contact_informationable_type', 'App\Models\NetworkAssociate')->get();
                $cities = City::where('id', $nwa_info[0]->city_id)->get();
                $towns = Town::where('city_id', $nwa_info[0]->city_id)->get();
            } else {
                $branches = Branch::where('id', $branch_id)->get();
                $branch_class_ids = get_branch_class_ids($branches[0]->id);
                $classes = ComClass::whereIn('id', $branch_class_ids)->get();
                $employee = Employee::where('id', auth()->user()->id)->get();
                $cities = City::where('id', $employee[0]->city_id)->get();
                $towns = Town::where('city_id', $employee[0]->city_id)->get();
            }
            $sources = Source::all();
            $inquirytypes = InquiriesType::all();
        }
        return view(
            'admission_queries.index',
            [
                        'admission_query' => $admission_query,
                        'academic_years' => $academic_years,
                        'branches' => $branches,
                        'classes' => $classes,
                        'cities' => $cities,
                        'towns' => $towns,
                        'sources' => $sources,
                        'inquirytypes' => $inquirytypes,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdmissionQuery $admission_query)
    {
        $admission_query->update($request->all());

        return redirect()->route('admission-query.index')
            ->with('success', 'Inquiry record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdmissionQuery $admission_query)
    {
        //
    }

    public function exportAdmissionQueries(Request $request)
    {
        return Excel::download(new AdmissionQueryExport(), 'AdmissionQueries.xlsx');
    }
}
