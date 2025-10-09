<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\State;
use App\Models\Source;
use App\Models\Constituency;
use App\Models\LedFranchise;
use Illuminate\Http\Request;
use App\Models\SchoolBuilding;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\FranchiseService;
use App\Models\FranchiseInquiryOld;
use App\Models\FranchiseApplication;
use App\Models\FranchisePossessSite;
use Illuminate\Support\Facades\File;
use App\Models\FranchiseQualification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Models\FrachiseApplicationRemark;
use App\Models\FranchiseOtherInformation;
use App\Models\FranchiseApplicationService;
use App\Models\FranchiseApplicationPossessSite;
use App\Models\FranchiseApplicationsAttachment;
use App\Models\FranchiseApplicationQualification;
use App\Models\FranchiseApplicationAttachmentType;
use App\Models\FranchiseApplicationOtherInformation;

class FranchiseApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
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


            $query = FranchiseApplication::with(['states', 'cities', 'source', 'other_informations','franchise_application_qa','franchise_application_bd','franchise_application_tor','franchise_application_legal','franchise_application_dd']);

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

            if ($request->qa_status && $request->qa_status > 0) {
                $query->whereHas('franchise_application_qa', function ($query) use ($request) {
                    $query->where('qa_status', $request->qa_status);
                });
            }

            if ($request->bd_status && $request->bd_status > 0) {
                $query->whereHas('franchise_application_bd', function ($query) use ($request) {
                    $query->where('bd_status', $request->bd_status);
                });
            }

            if ($request->tor_status && $request->tor_status > 0) {
                $query->whereHas('franchise_application_tor', function ($query) use ($request) {
                    $query->where('status', $request->tor_status);
                });
            }

            if ($request->legal_status && $request->legal_status > 0) {
                $query->whereHas('franchise_application_legal', function ($query) use ($request) {
                    $query->where('status', $request->legal_status);
                });
            }

            if ($request->dd_status && $request->dd_status > 0) {
                $query->whereHas('franchise_application_dd', function ($query) use ($request) {
                    $query->where('status', $request->dd_status);
                });
            }

            if ($request->agreement_type && $request->agreement_type != '') {
                if ($request->agreement_type == 'APP') {
                    $query->where('agreement_type', $request->agreement_type)->orWhereNull('agreement_type');
                }
                else{
                    $query->where('agreement_type', $request->agreement_type);
                }
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


                $query->whereHas('franchise_application_bd', function ($query) use ($request) {
                    $query->where('proposed_school_name', 'like', '%' . $request->searchTerm . '%');
                });
                 $query->orWhere('appl_name', 'like', '%' . $request->searchTerm . '%');
                $query->orWhere('CNIC', 'like', '%' . $request->searchTerm . '%');
                $query->orWhere('personal_address', 'like', '%' . $request->searchTerm . '%');
                $query->orWhere('agreement_type', 'like', '%' . $request->searchTerm . '%');

                // $query->orWhere('setup_date', 'like', '%' . $request->searchTerm . '%');
            }

            $franchiseApplication = $query->get();

            //$data['data'] = $franchiseApplication;
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseApplication;
            //dd($data['data']);
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $full_name = $row['appl_name'] . ' ' . $row['appl_last_name'];
                    return auth()->user()->hasPermission('edit-franchise-application') ? '<a href="'.route('franchise-applications.edit', $row->id).'">'.$full_name.'</a>' : $full_name;
                })
                ->addColumn('province_city', function ($row) {
                    return $row['states']['state_name'] . '/' . $row['cities']['city_name'];
                })
                ->addColumn('status', function ($row) {
                    $badge = $row->status == 'A' ? '<span class="badge bg-success">Approved</span>' : ($row->status == 'C' ? '<span class="badge bg-danger">Cancelled</span>' : '<span class="badge bg-primary">Pending</span>');
                    return $badge;
                })
                ->addColumn('franchise_application_qa_status', function ($row) {
                    $badge = isset($row['franchise_application_qa']) && $row['franchise_application_qa']['qa_status'] == 'Approved' ? '<span class="badge bg-success">Approved</span>' : (isset($row['franchise_application_qa']) && $row['franchise_application_qa']['qa_status'] == 'Forwarded' ? '<span class="badge bg-warning">Forwarded with Observation</span>' : '<span class="badge bg-primary">Pending</span>');
                    return $badge;
                })
                ->addColumn('franchise_application_bd_status', function ($row) {
                    $badge = isset($row['franchise_application_bd']) && $row['franchise_application_bd']->getRawOriginal('bd_status') == 'approved' ? '<span class="badge bg-success">Approved</span>' : (isset($row['franchise_application_bd']) && $row['franchise_application_bd']->getRawOriginal('bd_status') == 'not_approved' ? '<span class="badge bg-danger">Not Approved</span>' : '<span class="badge bg-primary">Pending</span>');
                    return $badge;
                })
                ->addColumn('franchise_application_tor_status', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('status') == 'approved' ? '<span class="badge bg-success">Approved</span>' : (isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('status') == 'not_approved' ? '<span class="badge bg-danger">Not Approved</span>' : '<span class="badge bg-primary">Pending</span>');
                    return $badge;
                })
                ->addColumn('franchise_application_legal_status', function ($row) {
                    $badge = isset($row['franchise_application_legal']) && $row['franchise_application_legal']->getRawOriginal('status') == 'ready_for_mou' ? '<span class="badge bg-success">Ready For MOU/FA</span>' : (isset($row['franchise_application_legal']) && $row['franchise_application_legal']->getRawOriginal('status') == 'need_recommendation' ? '<span class="badge bg-warning">Need Recommendation</span>' : '<span class="badge bg-primary">Pending</span>');
                    return $badge;
                })
                ->addColumn('franchise_application_dd_status', function ($row) {
                    $badge = isset($row['franchise_application_dd']) && $row['franchise_application_dd']->getRawOriginal('status') == 'approved' ? '<span class="badge bg-success">Approved</span>' : (isset($row['franchise_application_dd']) && $row['franchise_application_dd']->getRawOriginal('status') == 'not_approved' ? '<span class="badge bg-danger">Not Approved</span>' : '<span class="badge bg-primary">Pending</span>');
                    return $badge;
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
                    if($row['agreement_type']=='' || $row['agreement_type']=='APP')
                    { $badge="Applied"; }
                    else{ $badge =  $row['agreement_type'];}
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
                ->addColumn('executionDate', function ($row) {
                    $badge = isset($row['execution_date']) ? Carbon::parse($row['execution_date'])->format('d-m-Y') : '-';
                    return $badge;
                })
                ->addColumn('cut_offDate', function ($row) {
                    $badge = isset($row['cut_off_date']) ? Carbon::parse($row['cut_off_date'])->format('d-m-Y') : '-';
                    return $badge;
                })
                ->addColumn('extensionDate', function ($row) {
                    $badge = isset($row['extension_date']) ? Carbon::parse($row['extension_date'])->format('d-m-Y') : '-';
                    return $badge;
                })
                ->addColumn('operational_date', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('operational_date') ? Carbon::parse($row['franchise_application_tor']->getRawOriginal('operational_date'))->format('d-m-Y') : '-';
                    return $badge;
                })
                ->addColumn('actual_operational_date', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('actual_operational_date') ? Carbon::parse($row['franchise_application_tor']->getRawOriginal('actual_operational_date'))->format('d-m-Y') : '-';
                    return $badge;
                })
                ->addColumn('renewal_date', function ($row) {
                    $badge = isset($row['franchise_application_tor']) && $row['franchise_application_tor']->getRawOriginal('renewal_date') ? Carbon::parse($row['franchise_application_tor']->getRawOriginal('renewal_date'))->format('d-m-Y') : '-';
                    return $badge;
                })
                /*->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:m:s');
                })*/
                ->addColumn('action', function ($row) {
                    return view('franchise_application.franchise_application_action', ['row' => $row]);
                })
                ->rawColumns([
                    'full_name',
                    'franchise_application_qa_status',
                    'franchise_application_bd_status',
                    'franchise_application_tor_status',
                    'franchise_application_legal_status',
                    'franchise_application_dd_status',
                    'status', 'purposed_school_name',
                    'agreement_type', 'total_franchise_fee' , 'actual_operational_date','amount_received',
                    'renewal_date', 'agreement_date' ,'operational_date', 'executionDate', 'cut_offDate', 'extensionDate',
                    'school_type', 'action'])
                ->make(true);

            // return view('franchises-inquiry.franchises_list' ,$data);
        }
        // else{

        //  return view('franchises-inquiry.franchises_list');
        // }

        return view('franchise_application.franchise_applications', $data);
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
        return view('franchise_application.franchise_application_main', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $franchise = FranchiseApplication::create($input);
        $franchise->task()->create([
            'project_id' => 2,
            'task_status_id' => 1,
            'title' => 'Open Branch',
            'summary' => 'Required Branch in Lahore Gulberg',
            'task_detail_route' => 'franchise-applications.show'
        ]);

        if ($request->ajax()) {
            return $franchise;
        } else {
            return redirect()->route('franchise-applications.edit', $franchise->id)->with('success', 'Application/Form submitted successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranchiseApplication  $franchiseApplication
     * @return \Illuminate\Http\Response
     */
    public function show(FranchiseApplication $franchiseApplication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranchiseApplication  $franchiseApplication
     * @return \Illuminate\Http\Response
     */
    public function edit(FranchiseApplication $franchiseApplication)
    {
        $data = $this->editFranchiseApplication($franchiseApplication);
        // return view('franchises.franchise_inquiry', $data);
        return view('franchise_application.franchise_application_main', $data);
    }

    public function guestEditFranchiseApplication($guid)
    {
        $franchise = FranchiseApplication::findOrFail($guid);

        $data = $this->editFranchiseApplication($franchise);

        return view('franchise_application.franchise_application_main', $data);
    }

    public function editFranchiseApplication($franchise_application)
    {
        $cities = City::where(['state_id' => $franchise_application->state_id])->get();
        $constituencies = Constituency::where(['state_id' => $franchise_application->state_id])->get();
        $sources = Source::all();
        $states = State::all();
        $users = User::all();
        $recommended_by = getUserByDepartmentAndBranchID(5,2);
        $approved_by = getUserByDepartmentAndBranchID(5,2);
        $other_informations = FranchiseApplication::with(['other_informations'])->get();
        $attachment_types = FranchiseApplicationAttachmentType::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources,
            'franchise' => $franchise_application,
            'users' => $users,
            'recommended_by' => $recommended_by,
            'approved_by' => $approved_by,
            'other_informations' => $other_informations,
            'attachment_types' => $attachment_types,
            'constituencies' => $constituencies
        ];

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranchiseApplication $franchise_application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FranchiseApplication $franchise_application)
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

        if ($request->execution_date) {
            $input['execution_date'] = Carbon::parse($request->execution_date)->format('Y-m-d');
        }
        if ($request->cut_off_date) {
            $input['cut_off_date'] = Carbon::parse($request->cut_off_date)->format('Y-m-d');
        }
        if ($request->extension_date) {
            $input['extension_date'] = Carbon::parse($request->extension_date)->format('Y-m-d');
        }
        //dd($input);
        $franchise_application->update($input);
        return redirect()->back()->with('success', 'Application/Form Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranchiseApplication  $franchiseApplication
     * @return \Illuminate\Http\Response
     */
    public function destroy(FranchiseApplication $franchiseApplication)
    {
        //
    }

    public function getFranchiseApplicationDocs(Request $request)
    {
        $franchise_application_id = $request->franchise_application_id;
        $details = FranchiseApplicationsAttachment::with(['user','attachment_type'])->where(['franchise_application_id' => $franchise_application_id]);

        if (!auth()->user())
            $details = $details->whereNull('uploaded_by');
        $details = $details->get();

        $rows = view('franchise_application.franchise_application_docs_table_row', ['franchise_application_attachments' => $details])->render();

        return $rows;
    }

    public function saveFranchiseApplicationDocs(Request $request)
    {
        $input = array();
        $uploaded_by = \Auth::user() ? \Auth::user()->id : null;
        $uploaded_date = Carbon::now()->format('Y-m-d');

        $input['attachment_type_id'] = $request->attachment_type_id;
        $input['details'] = $request->details;
        $input['franchise_application_id'] = $request->franchise_application_id;


        if ($request->hasfile('file')) {
            /*$path = public_path('uploads/franchise_applications_attachments');
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }*/

            $type = $request->file->extension();
            $file_original_name = basename($request->file->getClientOriginalName(),'.'.$type);
            $fileName = $file_original_name . '-' . getCurrentMiliSec() . '.' . $type;
            // $request->file->move($path, $fileName);

             $s3_storage_path = 'franchise_application_attachments/' . $request->franchise_application_id . '/'. $fileName;
             Storage::disk('s3')->put($s3_storage_path, file_get_contents($request->file));

            $input['file_name'] = $fileName;
            $input['type'] = $type;
            $input['uploaded_by'] = $uploaded_by;
            $input['uploaded_date'] = $uploaded_date;
        }
        $response = array();
        $result = FranchiseApplicationsAttachment::create($input);
        return back()->with('success', 'You have successfully upload file.');
    }

    public function removeFranchiseApplicationDocs(FranchiseApplicationsAttachment $franchise_application_doc)
    {
        try {
            return $franchise_application_doc->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function loadApplicationQualifications(Request $request)
    {
        $states = State::all();
        $cities = City::all();
        $sources = Source::all();
        $data = [
            'states' => $states,
            'cities' => $cities,
            'sources' => $sources
        ];
        if ($request->ajax()) {
            $franchiseInquiry = FranchiseApplicationQualification::where('franchise_application_id', $request->franchise_application_id)->orderBy('created_at', 'ASC')->get();
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_qualification" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" data-target="#editQualificationModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-application-qualification', $row->id) . '" class="link-danger fs-15 delete-record" data-table="qualificationTable" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchise_application.personal_fact_sheet', $data);
    }

    public function saveApplicationQualification(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchiseApplicationQualification::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchiseApplicationQualification::create($input);
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

    public function removeApplicationQualification(FranchiseApplicationQualification $application_qualification)
    {
        try {
            return $application_qualification->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function getApplicationQualification(Request $request)
    {
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $details = FranchiseApplicationQualification::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->first();
        return $details;
    }


    public function loadApplicationServices(Request $request)
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
            $franchiseInquiry = FranchiseApplicationService::where('franchise_application_id', $request->franchise_application_id)->get();
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_inquiry_service" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" data-target="#editOccupationModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-application-service', $row->id) . '" class="link-danger fs-15 remove_inquiry_service delete-record" data-table="serviceOccupationTable" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchises-inquiry.personal_fact_sheet', $data);
    }

    public function saveApplicationService(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchiseApplicationService::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchiseApplicationService::create($input);
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

    public function getApplicationService(Request $request)
    {
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $details = FranchiseApplicationService::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->first();
        return $details;
    }

    public function removeApplicationService(FranchiseApplicationService $application_service)
    {
        try {
            return $application_service->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function loadApplicationOtherInformation(Request $request)
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
            $franchiseInquiry = FranchiseApplicationOtherInformation::where('franchise_application_id', $request->franchise_application_id)->get();
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_other_information" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" data-target="#editOtherInfoModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-application-other-information', $row->id) . '" class="link-danger fs-15 remove_other_information delete-record" data-table="otherInfoTable" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchises-inquiry.other_information', $data);
    }

    public function saveApplicationOtherInformation(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchiseApplicationOtherInformation::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchiseApplicationOtherInformation::create($input);
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

    public function getApplicationOtherInformation(Request $request)
    {
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $details = FranchiseApplicationOtherInformation::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->first();
        return $details;
    }

    public function removeApplicationOtherInformation(FranchiseApplicationOtherInformation $application_other_information)
    {
        try {
            return $application_other_information->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function loadApplicationPossesSites(Request $request)
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
            $franchiseInquiry = FranchiseApplicationPossessSite::where('franchise_application_id', $request->franchise_application_id)->get();
            // dd($franchiseInquiry->toArray());
            $data['data'] = $franchiseInquiry;
            return Datatables::of($data['data'])
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '
                <a href="javascript:void(0);" class="link-success fs-15 edit_possess_site" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" data-target="#editDesiredFranchiseInfoModal"><i class="ri-edit-2-line"></i></a>
                <a href="' . route('remove-application-possess-site', $row->id) . '" class="link-danger fs-15 remove_possess_site delete-record" data-table="possessSiteTable" data-id = "' . $row->id . '" data-franchise_application_id = "' . $row->franchise_application_id . '" ><i class="ri-delete-bin-line"></i></a>
                ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('franchises-inquiry.desired_franchise_details', $data);
    }

    public function saveApplicationPossesSite(Request $request)
    {
        $input = $request->all();
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $response = array();
        if ($id > 0 && $id != '') {
            $result = FranchiseApplicationPossessSite::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->update($input);
            if ($result) {
                $response['message'] = 'Record successfully updated';
                $response['class'] = 'link-success';
            } else {
                $response['message'] = 'Oops! Something went wrong.';
                $response['class'] = 'link-danger';
            }
        } else {
            $result = FranchiseApplicationPossessSite::create($input);
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

    public function getApplicationPossesSite(Request $request)
    {
        $id = $request->id;
        $franchise_application_id = $request->franchise_application_id;
        $details = FranchiseApplicationPossessSite::where(['id' => $id, 'franchise_application_id' => $franchise_application_id])->first();
        return $details;
    }

    public function removeApplicationPossesSite(FranchiseApplicationPossessSite $application_possess_site)
    {
        try {
            return $application_possess_site->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function franchiseApplicationInquiryAssessmentSurvey(Request $request)
    {
        //dd($request->id);
        $Application_detail = FranchiseApplication::where('id','=',$request->id)->with(['states', 'cities', 'source', 'other_informations','franchise_application_qa','franchise_application_bd','franchise_application_bd.school_type','franchise_application_tor','franchise_application_legal','franchise_application_dd','franchise_application_qa_remarks','franchise_application_bd_remarks','franchise_application_dd_remarks'])->get()->toArray();
        $applicant_name = '-';  $applicant_contact =  $bank_acc_detail = null;
        $id = $Application_detail[0]['id'];
        if(isset($Application_detail[0]['agreement_type']))
        {
            $agreement_type = $Application_detail[0]['agreement_type'];
        }
        else
        {
            $agreement_type = '-';
        }

        if($agreement_type == 'FA')
        {
            $attachments = FranchiseApplicationsAttachment::where(['franchise_application_id' => $request->id,'attachment_type_id' => 9])->get()->toArray();
            //dd($attachments[0]['uploaded_date']);
            $mou_fa_status =  isset($attachments[0]['uploaded_date']) ? 'Agreement updated on '.Carbon::parse($attachments[0]['uploaded_date'])->format('d-m-Y') : '';
        }
        elseif($agreement_type == 'MOU')
        {
            $attachments = FranchiseApplicationsAttachment::where(['franchise_application_id' => $request->id,'attachment_type_id' => 8])->get()->toArray();
            $mou_fa_status =  isset($attachments[0]['uploaded_date']) ? 'Agreement updated on '.Carbon::parse($attachments[0]['uploaded_date'])->format('d-m-Y') : '';
        }
        else
        {
            $mou_fa_status = '';
        }

            if(isset($Application_detail[0]['appl_name']))
            {
                $applicant_name = $Application_detail[0]['appl_name'];
            }
            if(isset($Application_detail[0]['appl_last_name']))
            {
                $applicant_name .= ' '.$Application_detail[0]['appl_last_name'];
            }
            // else if(isset($Application_detail[0]['appl_last_name']) && $applicant_name == '')
            // {
            //     $applicant_name = $Application_detail[0]['appl_last_name'];
            // }
            // else{
            //     $applicant_name = '-';
            // }
            if(isset($Application_detail[0]['contact_no_1']))
            {
                $applicant_contact = $Application_detail[0]['contact_no_1'];
            }
            else if(isset($Application_detail[0]['contact_no_2']))
            {
                $applicant_contact .= ', '.$Application_detail[0]['contact_no_2'];
            }
            else{
                $applicant_contact = '-';
            }
            if(isset($Application_detail[0]['CNIC']))
            {
                $cnic = $Application_detail[0]['CNIC'];
            }
            if(isset($Application_detail[0]['personal_address']))
            {
                $nwa_address = $Application_detail[0]['personal_address'];
            }
            if(isset($Application_detail[0]['email']))
            {
                $nwa_email = $Application_detail[0]['email'];
            }
            if(isset($Application_detail[0]['franchise_application_qa']['proposed_location']))
            {
                $proposed_location = $Application_detail[0]['franchise_application_qa']['proposed_location'];
            }
            else
            {
                $proposed_location = '-';
            }
            if(isset($Application_detail[0]['franchise_application_bd']['school_type']['name']))
            {
                $school_type = $Application_detail[0]['franchise_application_bd']['school_type']['name'];
            }
            else
            {
                $school_type = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['school_type']['description']))
            {
                $school_configuration = $Application_detail[0]['franchise_application_bd']['school_type']['description'];
            }
            else
            {
                $school_configuration = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['proposed_school_name']))
            {
                $proposed_school_name = $Application_detail[0]['franchise_application_bd']['proposed_school_name'];
            }
            else
            {
                $proposed_school_name = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['proposed_school_name']))
            {
                $proposed_school_name = $Application_detail[0]['franchise_application_bd']['proposed_school_name'];
            }
            else
            {
                $proposed_school_name = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['area_population_half_km_radius']))
            {
                $area_population_half_km_radius = $Application_detail[0]['franchise_application_bd']['area_population_half_km_radius'];
            }
            else
            {
                $area_population_half_km_radius = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['area_population_one_km_radius']))
            {
                $area_population_one_km_radius = $Application_detail[0]['franchise_application_bd']['area_population_one_km_radius'];
            }
            else
            {
                $area_population_one_km_radius = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['area_population_two_km_radius']))
            {
                $area_population_two_km_radius = $Application_detail[0]['franchise_application_bd']['area_population_two_km_radius'];
            }
            else
            {
                $area_population_two_km_radius = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd_remarks']))
            {
                $bd_remarks = $Application_detail[0]['franchise_application_bd_remarks']['observation'];
            }
            else
            {
                $bd_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['bd_status']))
            {
                $bd_status = $Application_detail[0]['franchise_application_bd']['bd_status'];
            }
            else
            {
                $bd_status = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['token_money']))
            {
                $token_money = ucfirst($Application_detail[0]['franchise_application_tor']['token_money']);
            }
            else
            {
                $token_money = 0;
            }

            if(isset($Application_detail[0]['franchise_application_tor']['agreement_date']))
            {
                $agreement_date = $Application_detail[0]['franchise_application_tor']['agreement_date'];
            }
            else
            {
                $agreement_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['operational_date']))
            {
                $operational_date = $Application_detail[0]['franchise_application_tor']['operational_date'];
            }
            else
            {
                $operational_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['actual_operational_date']))
            {
                $actual_operational_date = Carbon::parse($Application_detail[0]['franchise_application_tor']['actual_operational_date'])->format('d-m-Y');
            }
            else
            {
                $actual_operational_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['renewal_date']))
            {
                $renewal_date = $Application_detail[0]['franchise_application_tor']['renewal_date'];
            }
            else
            {
                $renewal_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['bank_name']))
            {
                $bank_name = $Application_detail[0]['franchise_application_tor']['bank_name'];
                $bank_acc_detail = $bank_name;
            }
            elseif(isset($Application_detail[0]['franchise_application_tor']['bank_account']))
            {
                $bank_account = ', Account #:'.$Application_detail[0]['franchise_application_tor']['bank_account'];
                $bank_acc_detail .= $bank_account;
            }
            elseif(isset($Application_detail[0]['franchise_application_tor']['bank_acc_opening_date']))
            {
                $bank_acc_opening_date = ', Account Opening Date:'.$Application_detail[0]['franchise_application_tor']['bank_acc_opening_date'];
                $bank_acc_detail .= $bank_acc_opening_date;
            }
            else
            {
                $bank_acc_detail = '-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['type_of_locality']))
            {
                $type_of_locality = $Application_detail[0]['franchise_application_qa']['type_of_locality'];
            }
            else
            {
                $type_of_locality ='-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['qa_status']))
            {
                $qa_status = $Application_detail[0]['franchise_application_qa']['qa_status'];
            }
            else
            {
                $qa_status ='-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['type_of_construction']))
            {
                $construction = explode('_',$Application_detail[0]['franchise_application_qa']['type_of_construction']);
                $type_of_construction = ucfirst($construction[0]).' '.ucfirst($construction[1]);
            }
            else
            {
                $type_of_construction = '-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['plot_size_actual']))
            {
                $plot_size_actual = $Application_detail[0]['franchise_application_qa']['plot_size_actual'].' '.$Application_detail[0]['franchise_application_qa']['actual_uom'];
            }
            else
            {
                $plot_size_actual = '-';
            }

            if(isset($Application_detail[0]['franchise_application_qa_remarks']))
            {
                $qa_remarks = $Application_detail[0]['franchise_application_qa_remarks']['observation'];
            }
            else
            {
                $qa_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['total_franchise_fee']))
            {
                $total_franchise_fee = $Application_detail[0]['franchise_application_tor']['total_franchise_fee'];
            }
            else
            {
                $total_franchise_fee = 0;
            }

            if(isset($Application_detail[0]['franchise_application_tor']['royalty_rate']))
            {
                $royalty_rate = $Application_detail[0]['franchise_application_tor']['royalty_rate'];
            }
            else
            {
                $royalty_rate = 0;
            }
            if(isset($Application_detail[0]['franchise_application_tor']['payment_on_agreement']))
            {
                $payment_on_agreement = $Application_detail[0]['franchise_application_tor']['payment_on_agreement'];
            }
            else
            {
                $payment_on_agreement = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['amount_received']))
            {
                $amount_received = $Application_detail[0]['franchise_application_tor']['amount_received'];
            }
            else
            {
                $amount_received = 0;
            }

            if(isset($Application_detail[0]['franchise_application_tor']['remarks']))
            {
                $tor_remarks = $Application_detail[0]['franchise_application_tor']['remarks'];
            }
            else
            {
                $tor_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['status']))
            {
                $tor_status = $Application_detail[0]['franchise_application_tor']['status'];
            }
            else
            {
                $tor_status = '-';
            }


            if(isset($Application_detail[0]['franchise_application_dd']['remarks']))
            {
                $dd_remarks = $Application_detail[0]['franchise_application_dd']['remarks'];
            }
            else
            {
                $dd_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_dd']['status']))
            {
                $dd_status = $Application_detail[0]['franchise_application_dd']['status'];
            }
            else
            {
                $dd_status = '-';
            }



            $data = [
                'id' => $id,
                'applicant_name' => $applicant_name,
                'applicant_contact' => $applicant_contact,
                'cnic' => $cnic,
                'nwa_address' => $nwa_address,
                'nwa_email' => $nwa_email,
                'proposed_location' => $proposed_location,
                'school_type' => $school_type,
                'school_configuration' => $school_configuration,
                'proposed_school_name' => $proposed_school_name,
                'area_population_half_km_radius' => $area_population_half_km_radius,
                'area_population_one_km_radius' => $area_population_one_km_radius,
                'area_population_two_km_radius' => $area_population_two_km_radius,
                'bd_remarks' => $bd_remarks,
                'bd_status' => $bd_status,
                'mou_fa_status' => $mou_fa_status,
                'agreement_date' => $agreement_date,
                'operational_date' => $operational_date,
                'actual_operational_date' => $actual_operational_date,
                'renewal_date' => $renewal_date,
                'bank_acc_detail' => $bank_acc_detail,
                'type_of_locality' => $type_of_locality,
                'qa_status' => $qa_status,
                'type_of_construction' => $type_of_construction,
                'plot_size_actual' => $plot_size_actual,
                'qa_remarks' => $qa_remarks,
                'total_franchise_fee' => $total_franchise_fee,
                'royalty_rate' => $royalty_rate,
                'agreement_type' => $agreement_type,
                'payment_on_agreement' => $payment_on_agreement,
                'amount_received' => $amount_received,
                'token_money' => $token_money,
                'tor_remarks' => $tor_remarks,
                'tor_status' => $tor_status,
                'dd_remarks' => $dd_remarks,
                'dd_status' => $dd_status,
            ];

            return view('franchise_application.franchise_application_iasf_form', $data);

    }

    public function create_iasf_pdf($franchise_application_id)
    {
        $Application_detail = FranchiseApplication::where('id','=',$franchise_application_id)->with(['states', 'cities', 'source', 'other_informations','franchise_application_qa','franchise_application_bd','franchise_application_bd.school_type','franchise_application_tor','franchise_application_legal','franchise_application_dd','franchise_application_qa_remarks','franchise_application_bd_remarks','franchise_application_dd_remarks'])->get()->toArray();
        //dd($Application_detail);
        $applicant_name = '-'; $applicant_contact =  $bank_acc_detail = null;
        $id = $Application_detail[0]['id'];
            if(isset($Application_detail[0]['agreement_type']))
            {
                $agreement_type = $Application_detail[0]['agreement_type'];
            }
            else
            {
                $agreement_type = '-';
            }

        if($agreement_type == 'FA')
        {
            $attachments = FranchiseApplicationsAttachment::where(['franchise_application_id' => $franchise_application_id,'attachment_type_id' => 9])->get()->toArray();
            //dd($attachments[0]['uploaded_date']);
            $mou_fa_status =  isset($attachments[0]['uploaded_date']) ? 'Completed on '.Carbon::parse($attachments[0]['uploaded_date'])->format('d-m-Y') : '';
        }
        elseif($agreement_type == 'MOU')
        {
            $attachments = FranchiseApplicationsAttachment::where(['franchise_application_id' => $franchise_application_id,'attachment_type_id' => 8])->get()->toArray();
            $mou_fa_status =  isset($attachments[0]['uploaded_date']) ? 'Completed on '.Carbon::parse($attachments[0]['uploaded_date'])->format('d-m-Y') : '';
        }
        else
        {
            $mou_fa_status = '';
        }
            if(isset($Application_detail[0]['appl_name']) && isset($Application_detail[0]['appl_last_name']))
            {
                $applicant_name = $Application_detail[0]['appl_name'];
            }
            if(isset($Application_detail[0]['appl_last_name']))
            {
                $applicant_name .= ' '.$Application_detail[0]['appl_last_name'];
            }
            // else if(isset($Application_detail[0]['appl_last_name']) && $applicant_name == '')
            // {
            //     $applicant_name = $Application_detail[0]['appl_last_name'];
            // }
            // else{
            //     $applicant_name = '-';
            // }
            if(isset($Application_detail[0]['contact_no_1']))
            {
                $applicant_contact = $Application_detail[0]['contact_no_1'];
            }
            else if(isset($Application_detail[0]['contact_no_2']))
            {
                $applicant_contact .= ', '.$Application_detail[0]['contact_no_2'];
            }
            else{
                $applicant_contact = '-';
            }
            if(isset($Application_detail[0]['CNIC']))
            {
                $cnic = $Application_detail[0]['CNIC'];
            }
            if(isset($Application_detail[0]['personal_address']))
            {
                $nwa_address = $Application_detail[0]['personal_address'];
            }
            if(isset($Application_detail[0]['email']))
            {
                $nwa_email = $Application_detail[0]['email'];
            }
            if(isset($Application_detail[0]['franchise_application_qa']['proposed_location']))
            {
                $proposed_location = $Application_detail[0]['franchise_application_qa']['proposed_location'];
            }
            else
            {
                $proposed_location = '-';
            }
            if(isset($Application_detail[0]['franchise_application_bd']['school_type']['name']))
            {
                $school_type = $Application_detail[0]['franchise_application_bd']['school_type']['name'];
            }
            else
            {
                $school_type = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['school_type']['description']))
            {
                $school_configuration = $Application_detail[0]['franchise_application_bd']['school_type']['description'];
            }
            else
            {
                $school_configuration = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['proposed_school_name']))
            {
                $proposed_school_name = $Application_detail[0]['franchise_application_bd']['proposed_school_name'];
            }
            else
            {
                $proposed_school_name = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['proposed_school_name']))
            {
                $proposed_school_name = $Application_detail[0]['franchise_application_bd']['proposed_school_name'];
            }
            else
            {
                $proposed_school_name = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['area_population_half_km_radius']))
            {
                $area_population_half_km_radius = $Application_detail[0]['franchise_application_bd']['area_population_half_km_radius'];
            }
            else
            {
                $area_population_half_km_radius = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['area_population_one_km_radius']))
            {
                $area_population_one_km_radius = $Application_detail[0]['franchise_application_bd']['area_population_one_km_radius'];
            }
            else
            {
                $area_population_one_km_radius = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['area_population_two_km_radius']))
            {
                $area_population_two_km_radius = $Application_detail[0]['franchise_application_bd']['area_population_two_km_radius'];
            }
            else
            {
                $area_population_two_km_radius = '-';
            }




            if(isset($Application_detail[0]['franchise_application_bd_remarks']))
            {
                $bd_remarks = $Application_detail[0]['franchise_application_bd_remarks']['observation'];
            }
            else
            {
                $bd_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_bd']['bd_status']))
            {
                $bd_status = $Application_detail[0]['franchise_application_bd']['bd_status'];
            }
            else
            {
                $bd_status = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['token_money']))
            {
                $token_money = ucfirst($Application_detail[0]['franchise_application_tor']['token_money']);
            }
            else
            {
                $token_money = 0;
            }

            if(isset($Application_detail[0]['franchise_application_tor']['agreement_date']))
            {
                $agreement_date = $Application_detail[0]['franchise_application_tor']['agreement_date'];
            }
            else
            {
                $agreement_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['operational_date']))
            {
                $operational_date = $Application_detail[0]['franchise_application_tor']['operational_date'];
            }
            else
            {
                $operational_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['actual_operational_date']))
            {
                $actual_operational_date = Carbon::parse($Application_detail[0]['franchise_application_tor']['actual_operational_date'])->format('d-m-Y');
            }
            else
            {
                $actual_operational_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['renewal_date']))
            {
                $renewal_date = $Application_detail[0]['franchise_application_tor']['renewal_date'];
            }
            else
            {
                $renewal_date = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['bank_name']))
            {
                $bank_name = $Application_detail[0]['franchise_application_tor']['bank_name'];
                $bank_acc_detail = $bank_name;
            }
            elseif(isset($Application_detail[0]['franchise_application_tor']['bank_account']))
            {
                $bank_account = ', Account #:'.$Application_detail[0]['franchise_application_tor']['bank_account'];
                $bank_acc_detail .= $bank_account;
            }
            elseif(isset($Application_detail[0]['franchise_application_tor']['bank_acc_opening_date']))
            {
                $bank_acc_opening_date = ', Account Opening Date:'.$Application_detail[0]['franchise_application_tor']['bank_acc_opening_date'];
                $bank_acc_detail .= $bank_acc_opening_date;
            }
            else
            {
                $bank_acc_detail = '-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['type_of_locality']))
            {
                $type_of_locality = $Application_detail[0]['franchise_application_qa']['type_of_locality'];
            }
            else
            {
                $type_of_locality ='-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['qa_status']))
            {
                $qa_status = $Application_detail[0]['franchise_application_qa']['qa_status'];
            }
            else
            {
                $qa_status ='-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['type_of_construction']))
            {
                $construction = explode('_',$Application_detail[0]['franchise_application_qa']['type_of_construction']);
                $type_of_construction = ucfirst($construction[0]).' '.ucfirst($construction[1]);
            }
            else
            {
                $type_of_construction = '-';
            }

            if(isset($Application_detail[0]['franchise_application_qa']['plot_size_actual']))
            {
                $plot_size_actual = $Application_detail[0]['franchise_application_qa']['plot_size_actual'].' '.$Application_detail[0]['franchise_application_qa']['actual_uom'];
            }
            else
            {
                $plot_size_actual = '-';
            }



            if(isset($Application_detail[0]['franchise_application_qa_remarks']))
            {
                $qa_remarks = $Application_detail[0]['franchise_application_qa_remarks']['observation'];
            }
            else
            {
                $qa_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['total_franchise_fee']))
            {
                $total_franchise_fee = $Application_detail[0]['franchise_application_tor']['total_franchise_fee'];
            }
            else
            {
                $total_franchise_fee = 0;
            }

            if(isset($Application_detail[0]['franchise_application_tor']['royalty_rate']))
            {
                $royalty_rate = $Application_detail[0]['franchise_application_tor']['royalty_rate'];
            }
            else
            {
                $royalty_rate = 0;
            }

            // if(isset($Application_detail[0]['franchise_application_tor']['agreement_type']))
            // {
            //     $agreement_type = $Application_detail[0]['franchise_application_tor']['agreement_type'];
            // }
            // else
            // {
            //     $agreement_type = 0;
            // }

            if(isset($Application_detail[0]['franchise_application_tor']['payment_on_agreement']))
            {
                $payment_on_agreement = $Application_detail[0]['franchise_application_tor']['payment_on_agreement'];
            }
            else
            {
                $payment_on_agreement = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['amount_received']))
            {
                $amount_received = $Application_detail[0]['franchise_application_tor']['amount_received'];
            }
            else
            {
                $amount_received = 0;
            }

            if(isset($Application_detail[0]['franchise_application_tor']['remarks']))
            {
                $tor_remarks = $Application_detail[0]['franchise_application_tor']['remarks'];
            }
            else
            {
                $tor_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_tor']['status']))
            {
                $tor_status = $Application_detail[0]['franchise_application_tor']['status'];
            }
            else
            {
                $tor_status = '-';
            }


            if(isset($Application_detail[0]['franchise_application_dd']['remarks']))
            {
                $dd_remarks = $Application_detail[0]['franchise_application_dd']['remarks'];
            }
            else
            {
                $dd_remarks = '-';
            }

            if(isset($Application_detail[0]['franchise_application_dd']['status']))
            {
                $dd_status = $Application_detail[0]['franchise_application_dd']['status'];
            }
            else
            {
                $dd_status = '-';
            }



            $data = [
                'id' => $id,
                'applicant_name' => $applicant_name,
                'applicant_contact' => $applicant_contact,
                'cnic' => $cnic,
                'nwa_address' => $nwa_address,
                'nwa_email' => $nwa_email,
                'proposed_location' => $proposed_location,
                'school_type' => $school_type,
                'school_configuration' => $school_configuration,
                'proposed_school_name' => $proposed_school_name,
                'area_population_half_km_radius' => $area_population_half_km_radius,
                'area_population_one_km_radius' => $area_population_one_km_radius,
                'area_population_two_km_radius' => $area_population_two_km_radius,
                'bd_remarks' => $bd_remarks,
                'bd_status' => $bd_status,
                'mou_fa_status' => $mou_fa_status,
                'agreement_date' => $agreement_date,
                'operational_date' => $operational_date,
                'actual_operational_date' => $actual_operational_date,
                'renewal_date' => $renewal_date,
                'bank_acc_detail' => $bank_acc_detail,
                'type_of_locality' => $type_of_locality,
                'qa_status' => $qa_status,
                'type_of_construction' => $type_of_construction,
                'plot_size_actual' => $plot_size_actual,
                'qa_remarks' => $qa_remarks,
                'total_franchise_fee' => $total_franchise_fee,
                'royalty_rate' => $royalty_rate,
                'agreement_type' => $agreement_type,
                'payment_on_agreement' => $payment_on_agreement,
                'amount_received' => $amount_received,
                'token_money' => $token_money,
                'tor_remarks' => $tor_remarks,
                'tor_status' => $tor_status,
                'dd_remarks' => $dd_remarks,
                'dd_status' => $dd_status,
            ];

        // return view('franchise_application.franchise_application_iasf_pdf', $data);
        $pdf = Pdf::loadView('franchise_application.franchise_application_iasf_pdf', $data);
        return $pdf->download('franchise_application_iasf.pdf');

    }

    /*public function inquiryEducationalOrganization(Request $request)
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
            if( $request->inquirer_id){
                $data->where('inquirer_id', $request->inquirer_id);
            }
            $data = $data->get();
            // dd($data->toArray());
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
            return view('franchises.registration-forms.franchise_Info_form' ,$data);
        }
        else{

            return view('franchises.registration-forms.franchise_Info_form');
        }
    }
    public function loadInquirySchoolBuilding(Request $request)
    {
        if ($request->ajax()) {

            $data = SchoolBuilding::with(['inquirer', 'cities']);
            if( $request->inquirer_id ){
                $data->where('inquirer_id', $request->inquirer_id);
            }
            $data = $data->get();
            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
            return view('franchises.registration-forms.school_building_options_form' ,$data);
        }
        else{

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
    }*/
}
