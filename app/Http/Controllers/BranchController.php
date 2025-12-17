<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Town;
use App\Models\State;
use App\Models\Branch;
use App\Models\BranchTax;
use App\Models\Region;
use App\Models\Company;
use App\Models\Country;
use App\Models\Section;
use App\Models\TaxType;
use App\Models\ComClass;
use App\Models\FeePeriod;
use App\Models\ClassGroup;
use App\Models\BuildingType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\DesignationType;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            if(Auth::user()->hasRole('deputy-director|head-of-finance|ho-accountant|head_of_bd|bd_sales'))
                $data = Branch::whereNotIn('id', [1,2,3,22,23,24,27,28])->with(['region', 'company', 'nwa.user', 'nwa.contact_information', 'class_group', 'contact_information.state']);
            else
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
            // return DataTables::of($data)
            //     ->addIndexColumn()
            //     ->addColumn('br_name', function ($row) {
            //         return '<a href="' . route('branches.edit', $row->id) . '?tab=home' . ' ">' . $row->br_name . '</a>';
            //     })
            //     ->addColumn('student_id_range', function ($row) {
            //         return $row['student_id_from'] . ' - ' . $row['student_id_to'];
            //     })
            //     ->addColumn('student_count', function ($row) {
            //         return $row['students']->count();
            //     })
            //     ->addColumn('setup_date', function ($row) {
            //         return Carbon::parse($row->setup_date)->format('d-m-Y');
            //     })
            //     ->addColumn('branch_royalty', function ($row) {
            //         return isset($row['active_royalty']) ? $row['active_royalty']['royalty_rate'] : '';
            //     })
            //     ->addColumn('royalty_date', function ($row) {
            //         return isset($row['active_royalty']) ? Carbon::parse($row['active_royalty']['with_effect_from'])->format('d-m-Y') . ' - ' . Carbon::parse($row['active_royalty']['closing_date'])->format('d-m-Y') : '';
            //     })
            //     ->addColumn('action', function ($row) {
            //         $btn = '
            //         <a href="' . route('branches.edit', $row->id) . '?tab=home' . ' " class="btn btn-sm btn-success btn-icon waves-effect waves-light">
            //             <i class="mdi mdi-lead-pencil"></i>
            //         </a>
            //     ';

            //         return $btn;
            //     })
            //     ->rawColumns(['action', 'status', 'br_name'])
            //     ->make(true);
        }

        $data['companies'] = Company::all();
        $data['regions'] = Region::all();
        $data['taxes'] = TaxType::all();
        $data['states'] = State::all();

        return view('branches.branches', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::all();
        $states = State::all();
        $class_groups = ClassGroup::all();
        $countries = Country::all();
        $companies = Company::all();
        $feeperiods = FeePeriod::all();
        $buildtypes = BuildingType::all();
        $taxesTypes = TaxType::all();

        $data = [
            'countries' => $countries,
            'regions' => $regions,
            'class_groups' => $class_groups,
            'companies' => $companies,
            // 'feeperiods' => $feeperiods,
            'buildtypes' => $buildtypes,
            'tax_types' => $taxesTypes,
            'states' => $states,
        ];

        return view('branches.add_branch', $data);
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

        request()->validate([
            'br_name' => 'required|unique:branches',
            'branch_banner' => 'image|mimes:jpeg,png,jpg|max:2048',
            'region_id' => 'required',
            'state_id' => 'required',
            'class_group_id' => 'required',
            'setup_date' => 'required',
        ]);
        $input = $request->all();

        if ($request->hasfile('branch_banner')) {
            /*$path = public_path().'/uploads/branchimages/';
            if(!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $destination_path = public_path('/uploads/branchimages');*/
            $branch_img_filename = Str::random(32) . '.' . $request->branch_banner->getClientOriginalExtension();
            // $request->branch_banner->move($destination_path, $branch_img_filename);
            $input['branch_banner'] = $branch_img_filename;
            $filepath = 'images/' . $branch_img_filename;
            $s3path = Storage::disk('s3')->put($filepath, file_get_contents($request->branch_banner));
            // $s3path = Storage::disk('s3')->url($s3path);
        }

        $words = explode(" ", $request->br_name);
        $abbreviation = "";
        foreach ($words as $w) {
            $abbreviation .= $w[0];
        }
        $input['abbreviation'] = $abbreviation;
        $branch = Branch::create($input);
        $branch->tax_types()->attach($request->tax_types);

        return redirect(route('branches.edit', $branch->id) . '?tab=contact')->with('success', 'Branch Created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        return view('branches.details_branch', ['branch' => $branch]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        $countries = Country::all();
        $regions = Region::all();
        $states = State::all();
        $class_groups = ClassGroup::all();
        $contactInformation = $branch->contact_information;
        $companies = Company::all();
        $classes = ComClass::all();
        $sections = Section::all();
        $feeperiods = FeePeriod::all();
        $buildtypes = BuildingType::all();
        $taxesTypes = TaxType::all();
        $branchTaxes = $branch->tax_types->pluck('id')->toArray();
        $designation_types = DesignationType::all();

        $data = [
            'branch_associate' => 'edit-form',
            'contact_information' => $contactInformation,
            'countries' => $countries,
            'regions' => $regions,
            'states' => $states,
            'companies' => $companies,
            'class_groups' => $class_groups,
            'branch' => $branch,
            'classes' => $classes,
            'sections' => $sections,
            'designation_type' => $designation_types,
            'feeperiods' => $feeperiods,
            'buildtypes' => $buildtypes,
            'tax_types' => $taxesTypes,
            'branch_taxes' => $branchTaxes
        ];

        if (!empty($contactInformation)) {
            $states = State::where('country_id', $contactInformation->country_id)->get();
            $cities = City::where('state_id', $contactInformation->state_id)->get();
            $towns = Town::where('city_id', $contactInformation->city_id)->get();
            $data['states'] = $states;
            $data['cities'] = $cities;
            $data['towns'] = $towns;
        }
        return view('branches.add_branch', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        request()->validate([
            'br_name' => 'required|unique:countries,country_name,' . $branch->id,
            'branch_banner' => 'image|mimes:jpeg,png,jpg|max:2048',
            'region_id' => 'required',
            'state_id' => 'required',
            'class_group_id' => 'required'
        ]);

        $input = $request->all();

        $branch_record = Branch::find($branch->id);
        if ($request->hasfile('branch_banner')) {
            /*$path = public_path().'/uploads/branchimages/';
            if(!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $destination_path = public_path('/uploads/branchimages');*/
            $branch_img_filename = Str::random(32) . '.' . $request->branch_banner->getClientOriginalExtension();
            // $request->branch_banner->move($destination_path, $branch_img_filename);
            $input['branch_banner'] = $branch_img_filename;
            $filepath = 'images/' . $branch_img_filename;
            $s3path = Storage::disk('s3')->put($filepath, file_get_contents($request->branch_banner));
            // $s3path = Storage::disk('s3')->url($s3path);
            if (Storage::disk('s3')->exists('images/' . $branch_record['branch_banner'])) {
                // FIle::delete($path.$branch_record['branch_banner']);
                Storage::disk('s3')->delete('images/' . $branch_record['branch_banner']);
            }
        }

        //dd($input);
        $branch->update($input);
        $branch->tax_types()->sync($request->tax_types);
        return redirect(route('branches.index'))->with('success', 'Branch Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        //
    }
}
