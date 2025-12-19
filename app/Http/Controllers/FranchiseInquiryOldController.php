<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FranchiseInquiryOld;
use App\Models\FranchiseOtherInformation;
use App\Models\FranchiseQualification;
use App\Models\FranchiseService;
use App\Models\FranchisePossessSite;
use App\Models\FranchiseApplicationAttachmentType;
use App\Models\FranchiseApplicationsAttachment;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\Region;
use App\Models\State;
use App\Models\Town;
use App\Models\User;
use App\Models\Source;
use App\Models\Task;
use App\Models\LedFranchise;
use App\Models\SchoolBuilding;
use DataTables;
use Carbon\Carbon;

class FranchiseInquiryOldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $states = State::all();
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
            $query = FranchiseInquiryOld::with(['states', 'cities', 'source', 'other_informations']);

            if ($request->state_id && $request->state_id > 0) {
                $query->where('state_id', $request->state_id);
            }

            if ($request->city_id && $request->city_id > 0) {
                $query->where('city_id', $request->city_id);
            }

            if ($request->source_id && $request->source_id > 0) {
                $query->where('source_id', $request->source_id);
            }

            if ($request->status && $request->status > 0) {
                $query->where('status', $request->status);
            }

            if ($request->date_range && $request->date_range > 0) {
                if (strpos($request->date_range, 'to') !== false) {
                    $range = explode('to', $request->date_range);
                    $startDate = Carbon::createFromFormat('Y-m-d', trim($range[0]));
                    $endDate = Carbon::createFromFormat('Y-m-d', trim($range[1]));
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }




            if ($request->searchTerm && $request->searchTerm != null) {
                $query->orWhere('appl_name', 'like', '%' . $request->searchTerm . '%');
                $query->orWhere('CNIC', 'like', '%' . $request->searchTerm . '%');
                $query->orWhere('personal_address', 'like', '%' . $request->searchTerm . '%');

                // $query->orWhere('setup_date', 'like', '%' . $request->searchTerm . '%');
            }

            $franchiseInquiry = $query->get();
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:m:s');
                })
                ->addColumn('action', function ($row) {
                    return view('franchises-inquiry.franchise_application_action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);

            // return view('franchises-inquiry.franchises_list' ,$data);
        }
        // else{

        //  return view('franchises-inquiry.franchises_list');
        // }

        return view('franchises-inquiry.franchises_list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $states = State::all();
        $cities = City::all();
        $sources = Source::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources
        ];
        // return view('franchises.franchise_inquiry', $data);
        return view('franchises-inquiry.franchise_inquiry_main', $data);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $franchise = FranchiseInquiryOld::create($input);
        $franchise->task()->create([
            'project_id' => 2,
            'task_status_id' => 1,
            'title' => 'Open Branch',
            'summary' => 'Required Branch in Lahore Gulbarg',
            'task_detail_route' => 'franchises.show'
        ]);

        if ($request->ajax()) {
            return $franchise;
        } else {
            return redirect()->route('franchises.edit', $franchise->id)->with('success', 'Application/Form Submit successfully');
        }
    }

    public function show(FranchiseInquiryOld $franchise)
    {
    }

    public function edit(FranchiseInquiryOld $franchise)
    {
        $data = $this->editFranchiseApplication($franchise);
        // return view('franchises.franchise_inquiry', $data);
        return view('franchises-inquiry.franchise_inquiry_main', $data);
    }

    public function guestEditFranchiseApplication($guid)
    {
        $franchise = FranchiseInquiryOld::findOrFail($guid);

        $data = $this->editFranchiseApplication($franchise);

        return view('franchises-inquiry.franchise_inquiry_main', $data);
    }

    public function editFranchiseApplication($franchise)
    {
        $cities = City::where(['state_id' => $franchise->state_id])->get();
        $sources = Source::all();
        $states = State::all();
        $users = User::all();
        $other_informations = FranchiseInquiryOld::with(['other_informations'])->get();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources,
            'franchise' => $franchise,
            'users' => $users,
            'other_informations' => $other_informations
        ];

        return $data;
    }

    public function update(Request $request, FranchiseInquiryOld $franchise)
    {
        $input = $request->all();

        if ($request->recommended_by) {
            $input['recommended_date'] = Carbon::now()->format('Y-m-d');
        }

        if ($request->approved_by) {
            $input['approved_date'] = Carbon::now()->format('Y-m-d');
        }

        if ($request->forwarded_by) {
            $input['forwarded_date'] = Carbon::now()->format('Y-m-d');
        }

        $franchise->update($input);
        return redirect()->back();
        // return $franchise;
    }

    public function destroy(FranchiseInquiryOld $franchise)
    {
        //
    }

    public function getFranchiseApplicationsDocs(Request $request)
    {
        $inquiry_id = $request->inquiry_id;
        $details = FranchiseApplicationsAttachment::with(['user'])->where(['inquiry_id' => $inquiry_id])->get();
        // dd($details->toArray());
        return $details;
    }

    public function saveFranchiseApplicationsDocs(Request $request)
    {
        $input = array();
        $uploaded_by = \Auth::user()->id;
        $uploaded_date = Carbon::now()->format('Y-m-d');

        $input['attachment_type_id'] = $request->attachment_type_id;
        $input['details'] = $request->details;
        $input['inquiry_id'] = $request->inquiry_id;


        if ($request->hasfile('file')) {
            $path = public_path('uploads/franchise_applications_attachments');
            if (! File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $fileName = time() . '.' . $request->file->extension();
            $type = $request->file->extension();

            $response2 = $request->file->move(public_path('uploads/franchise_applications_attachments'), $fileName);

            $input['file_name'] = $fileName;
            $input['type'] = $type;
            $input['uploaded_by'] = $uploaded_by;
            $input['uploaded_date'] = $uploaded_date;
        }
        $response = array();
        $result = FranchiseApplicationsAttachment::create($input);
        return back()->with('success', 'You have successfully upload file.')->with('file', $fileName);
    }


    public function loadInquiryQualification(Request $request)
    {
        // dd($request->inquiry_id);
        $states = State::all();
        $cities = City::all();
        $sources = Source::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources
        ];
        if ($request->ajax()) {
            $franchiseInquiry = FranchiseQualification::where('inquiry_id', $request->inquiry_id)->orderBy('created_at', 'ASC')->get();
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_qualification" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" data-target="#editQualificationModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-inquiry-qualification', $row->id) . '" class="link-danger fs-15 delete-record" data-table="qualificationTable" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchises-inquiry.personal_fact_sheet', $data);
    }

    public function saveInquiryQualification(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchiseQualification::where(['id' => $id, 'inquiry_id' => $inquiry_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchiseQualification::create($input);
            if ($result) {
                $response['message'] = 'Record successfully added';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        }
        return $response;
    }

    public function removeInquiryQualification(FranchiseQualification $franchise_qualification)
    {
        try {
            return $franchise_qualification->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function getInquiryQualification(Request $request)
    {
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $details = FranchiseQualification::where(['id' => $id, 'inquiry_id' => $inquiry_id])->first();
        return $details;
    }


    public function loadInquiryServiceData(Request $request)
    {
        // dd($request->all());
        $states = State::all();
        $cities = City::all();
        $sources = Source::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources
        ];
        if ($request->ajax()) {
            $franchiseInquiry = FranchiseService::where('inquiry_id', $request->inquiry_id)->get();
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_inquiry_service" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" data-target="#editOccupationModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-inquiry-service', $row->id) . '" class="link-danger fs-15 remove_inquiry_service delete-record" data-table="serviceOccupationTable" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchises-inquiry.personal_fact_sheet', $data);
    }

    public function saveInquiryService(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchiseService::where(['id' => $id, 'inquiry_id' => $inquiry_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchiseService::create($input);
            if ($result) {
                $response['message'] = 'Record successfully added';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        }
        return $response;
    }

    public function getInquiryService(Request $request)
    {
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $details = FranchiseService::where(['id' => $id, 'inquiry_id' => $inquiry_id])->first();
        return $details;
    }

    public function removeInquiryService(FranchiseService $franchise_service)
    {
        try {
            return $franchise_service->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function loadInquiryOtherInformation(Request $request)
    {
        // dd($request->all());
        $states = State::all();
        $cities = City::all();
        $sources = Source::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources
        ];
        if ($request->ajax()) {
            $franchiseInquiry = FranchiseOtherInformation::where('inquiry_id', $request->inquiry_id)->get();
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_other_information" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" data-target="#editOtherInfoModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-inquiry-other-information', $row->id) . '" class="link-danger fs-15 remove_other_information delete-record" data-table="otherInfoTable" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchises-inquiry.other_information', $data);
    }


    public function saveInquiryOtherInformation(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchiseOtherInformation::where(['id' => $id, 'inquiry_id' => $inquiry_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchiseOtherInformation::create($input);
            if ($result) {
                $response['message'] = 'Record successfully added';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        }
        return $response;
    }

    public function getInquiryOtherInformation(Request $request)
    {
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $details = FranchiseOtherInformation::where(['id' => $id, 'inquiry_id' => $inquiry_id])->first();
        return $details;
    }

    public function removeInquiryOtherInformation(FranchiseOtherInformation $franchise_other_information)
    {
        try {
            return $franchise_other_information->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function loadPossesSiteData(Request $request)
    {
        // dd($request->all());
        $states = State::all();
        $cities = City::all();
        $sources = Source::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources
        ];
        if ($request->ajax()) {
            $franchiseInquiry = FranchisePossessSite::where('inquiry_id', $request->inquiry_id)->get();
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_possess_site" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" data-target="#editDesiredFranchiseInfoModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-possess-site-data', $row->id) . '" class="link-danger fs-15 remove_possess_site delete-record" data-table="possessSiteTable" data-id = "' . $row->id . '" data-inquiry_id = "' . $row->inquiry_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchises-inquiry.desired_franchise_details', $data);
    }

    public function savePossesSiteData(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchisePossessSite::where(['id' => $id, 'inquiry_id' => $inquiry_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchisePossessSite::create($input);
            if ($result) {
                $response['message'] = 'Record successfully added';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        }
        return $response;
    }

    public function getPossesSiteData(Request $request)
    {
        $id = $request->id;
        $inquiry_id = $request->inquiry_id;
        $details = FranchisePossessSite::where(['id' => $id, 'inquiry_id' => $inquiry_id])->first();
        return $details;
    }

    public function removePossesSiteData(FranchisePossessSite $franchise_possess_site)
    {
        try {
            return $franchise_possess_site->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function inquiryEducationalOrganization(Request $request)
    {
        $edu_org_update = FranchiseInquiryOld::where("id", $request->inquirer_id)->update([
            "edu_organization_name" => $request->edu_organization_name,
            "inquirer_edu_designation" => $request->inquirer_edu_designation,
            "inquirer_experience" => $request->inquirer_experience,
            "edu_organization_desc" => $request->edu_organization_desc
        ]);
    }
    public function inquiryLedFranchise(Request $request)
    {
        // dd($request->all());
        $ledFranchise = LedFranchise::create($request->all());
        return $ledFranchise;
    }
    public function loadInquiryLedFranchise(Request $request)
    {
        if ($request->ajax()) {
            $data = ledFranchise::with(['inquirer']);
            if ($request->inquirer_id) {
                $data->where('inquirer_id', $request->inquirer_id);
            }
            $data = $data->get();
            // dd($data->toArray());
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
            return view('franchises.registration-forms.franchise_Info_form', $data);
        } else {
            return view('franchises.registration-forms.franchise_Info_form');
        }
    }
    public function loadInquirySchoolBuilding(Request $request)
    {
        if ($request->ajax()) {
            $data = SchoolBuilding::with(['inquirer', 'cities']);
            if ($request->inquirer_id) {
                $data->where('inquirer_id', $request->inquirer_id);
            }
            $data = $data->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
            return view('franchises.registration-forms.school_building_options_form', $data);
        } else {
            return view('franchises.registration-forms.school_building_options_form');
        }
    }

    public function inquirySchoolBuilding(Request $request)
    {
        $schoolBuilding = SchoolBuilding::create($request->all());
        return $schoolBuilding;
    }
    public function inquiryExistingBuilding(Request $request)
    {
        $existing_building_update = FranchiseInquiryOld::where("id", $request->inquirer_id)->update([
            "building_status" => $request->building_status,
            "building_ownership" => $request->building_ownership,
            "building_state_id" => $request->state_id,
            "building_city_id" => $request->city_id,
            "building_town_id" => $request->town_id,
            "building_area" => $request->building_area,
            "building_address" => $request->building_address,
            "building_existing_area_size" => $request->building_existing_area_size,
            "building_covered_area" => $request->building_covered_area
        ]);
    }
    public function inquiryPropertyDetails(Request $request)
    {
        $location_prop_update = FranchiseInquiryOld::where("id", $request->inquirer_id)->update([
            "time_required" => $request->time_required,
            "proposed_investment" => $request->proposed_investment,
            "public_schools" => $request->public_schools,
            "private_schools" => $request->private_schools,
            "property_status" => $request->property_status,
            "financing_plan" => $request->financing_plan
        ]);
    }

    public function inquiryPersonalDetailupdate(Request $request)
    {

        $id = FranchiseInquiryOld::where("id", $request->inquirer_id)->update([
            "appl_name" => $request->appl_name,
            "appl_last_name" => $request->appl_last_name,
            "CNIC" => $request->CNIC,
            "email" => $request->email,
            "state_id" => $request->state_id,
            "city_id" => $request->city_id,
            "primary_mobile_no" => $request->primary_mobile_no,
            "secondary_mobile_no" => $request->secondary_mobile_no,
            "source_id" => $request->source_id,
            "other_profession" => $request->other_profession,
            "organization_name" => $request->organization_name,
            "inquirer_designation" => $request->inquirer_designation,
            "inquirer_qualification" => $request->inquirer_qualification,
            "personal_address" => $request->personal_address
        ]);
        $franchise['id'] = $id;
        return $franchise;
    }

    public function applications($value = 0)
    {
        if ($value == 'qa') {
            return view('franchises-inquiry.quality_assurance_form');
        } else if ($value == 'bd') {
            return view('franchises-inquiry.business_development_form');
        } else if ($value == 'dd') {
            return view('franchises-inquiry.deputy_director_form');
        }
        // else if ($value == 'qa_visit_form')
        //     return view('franchises-inquiry.qa_visit_report');
        else {
            return view('franchises-inquiry.review_by_legal_form');
        }
    }
}
