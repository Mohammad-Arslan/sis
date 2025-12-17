<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\FranchiseInquiry;
use App\Models\Source;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;

class FranchiseInquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FranchiseInquiry::with(['source', 'city']);

            if (isset($request->source_id)) {
                $data = $data->where('source_id', $request->source_id);
            }

            if (isset($request->city_id)) {
                $data = $data->where('city_id', $request->city_id);
            }

            if (isset($request->led_franchise)) {
                $data = $data->where('led_franchise', $request->led_franchise);
            }

            if (isset($request->created_by)) {
                $data = $data->where('created_by', $request->created_by);
            }

            if (isset($request->searchName)) {
                $data = $data->where(function ($query) use ($request) {
                    $query->where('full_name', 'LIKE', '%' . $request->searchName . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->searchName . '%')
                    ->orWhere('contact_no_1', 'LIKE', '%' . $request->searchName . '%')
                    ->orWhere('current_occupation', 'LIKE', '%' . $request->searchName . '%');
                });
            }

            $data = $data->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('initiated_by', function ($row) {
                    return !empty($row->initiated_by) ? User::getUserNameByID($row->initiated_by) : '--';
                })
                ->addColumn('created_by', function ($row) {
                    return !empty($row->created_by) ? $row->created_by : '--';
                })
                ->addColumn('last_updated_by', function ($row) {
                    return !empty($row->last_updated_by) ? User::getUserNameByID($row->last_updated_by) : '--';
                })
                ->addColumn('led_franchise', function ($row) {
                    return ucwords($row->led_franchise);
                })
                ->addColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('Y-m-d H:m:s');
                })
                ->addColumn('action', function ($row) {
                    return view('franchise-inquiry.franchise_inquiries_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $sources = Source::all();
        $cities = City::all();

        return view('franchise-inquiry.franchise_inquiries', compact('sources', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['cities'] = City::all();
        $data['sources'] = Source::all();

        return view('franchise-inquiry.franchise_inquiry', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = "Franchise Inquiry Created Successfully.";
        $this->validation($request);
        $request->merge(['created_by' => isset($request->created_by) ? $request->created_by : 'system']);
        FranchiseInquiry::create($request->all());
        if ($request->has('created_by')) {
            $message = "Your application has been submitted successfully.";
        }
//        FranchiseInquiry::store_update_franchise_inquiry($request->all());
        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FranchiseInquiry  $franchisesInquiry
     * @return \Illuminate\Http\Response
     */
    public function show(FranchiseInquiry $franchisesInquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FranchiseInquiry  $franchisesInquiry
     * @return \Illuminate\Http\Response
     */
    public function edit(FranchiseInquiry $franchisesInquiry)
    {
        $data['cities'] = City::all();
        $data['sources'] = Source::all();
        $data['franchise_inquiry'] = $franchisesInquiry;

        return view('franchise-inquiry.franchise_inquiry',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FranchiseInquiry  $franchisesInquiry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FranchiseInquiry $franchisesInquiry)
    {
//        dd($request->all());
        $this->validation($request, $franchisesInquiry->id);
        $franchisesInquiry->update($request->all());
        $franchisesInquiry = $franchisesInquiry->refresh();

        if ($franchisesInquiry->wasChanged() === true) {
            if (empty($franchisesInquiry->initiated_by)) {
                $franchisesInquiry->initiated_by = auth()->user()->id;
            }
            $franchisesInquiry->last_updated_by = auth()->user()->id;
            $franchisesInquiry->save();
        }
//        FranchiseInquiry::store_update_franchise_inquiry($request->all(),$franchisesInquiry,'update');

        return redirect()->back()->with('success','Franchise Inquiry Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FranchiseInquiry  $franchisesInquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(FranchiseInquiry $franchisesInquiry)
    {
        try {
            return $franchisesInquiry->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    private function validation($request, $id = null)
    {
        $validations = [
            'full_name' => 'required|max:255',
            'CNIC' => 'required|max:255',
            'email' => 'nullable|max:255|email|unique:franchise_inquiries,email,' . $id,
            'address' => 'nullable',
            'city_id' => 'required|integer',
            'contact_no_1' => 'required|max:11',
            'contact_no_2' => 'nullable|max:11',
            'current_occupation' => 'nullable|max:255',
            'led_franchise' => 'required',
            'franchise_name' => isset($request->franchise_name) ? 'required|max:255' : 'nullable',
            'franchise_interest' => 'required|max:255',
            'area_location' => 'required|max:255',
            'source_id' => 'required|integer',
            'call_center_agent' => 'nullable|max:255',
            'inquiry_status' => 'nullable|max:255',
            'meeting_with_bd' => 'nullable',
            'call_back' => 'nullable',
            'launching_year' => 'nullable|max:255',
            'land_area' => 'nullable|max:255',
            'covered_area' => 'nullable|max:255',
            'general_remarks' => 'nullable',
            'inquiry_remarks' => 'nullable',
            'meeting_remarks' => 'nullable',
            'expected_franchise_address' => 'nullable',
            'experience' => 'nullable|max:255'
        ];

        $messages = [
            'full_name.required' => 'Full name is required!',
            'full_name.max' => 'Only 255 characters allowed',
            'CNIC.required' => 'CNIC is required!',
            'CNIC.max' => 'Only 255 characters allowed',
            'email.required' => 'Email is required!',
            'email.max' => 'Only 255 characters allowed',
            'email.email' => 'Enter correct email address',
            'email.unique' => 'Application already submitted with this email address',
            'address.required' => 'Address is required!',
            'address.max' => 'Only 255 characters allowed',
            'city.required' => 'City is required!',
            'contact_no_1.required' => 'Contact no 1 is required!',
            'contact_no_1.max' => 'Only 11 digits allowed',
            'contact_no_2.max' => 'Only 11 digits allowed',
            'led_franchise.required' => 'Led franchise is required!',
            'franchise_name.required' => 'Franchise name is required!',
            'franchise_name.max' => 'Only 255 characters allowed',
            'franchise_interest.required' => 'Franchise interested is required!',
            'area_location.required' => 'Area/Location is required!',
            'area_location.max' => 'Only 255 characters allowed',
            'source_id.required' => 'Source is required!'
        ];
        return $request->validate($validations, $messages);
    }
}
