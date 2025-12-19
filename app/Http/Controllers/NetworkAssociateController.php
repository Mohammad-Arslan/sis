<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\City;
use App\Models\Company;
use App\Models\Country;
use App\Models\NetworkAssociate;
use App\Models\Region;
use App\Models\State;
use App\Models\Town;
use App\Models\User;
use App\Models\Branch;
use App\Models\NetworkAssociateBranch;
use Illuminate\Http\Request;
use DataTables;

class NetworkAssociateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = NetworkAssociate::with(['user', 'company']);

            if ($request->company_id && $request->company_id > 0) {
                $data->where('company_id', $request->company_id);
            }

            if ($request->searchTerm && strlen($request->searchTerm) > 3) {
                $data->orWhere('NTN', 'like', '%' . $request->searchTerm . '%');
                $data->orWhere('STRN', 'like', '%' . $request->searchTerm . '%');

                $data->orWhereHas('user', function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->searchTerm . '%');
                    $query->orWhere('email', 'like', '%' . $request->searchTerm . '%');
                });
            }

            $data = $data->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    $name = $row->user ? $row->user->name : 'N/A';
                    return '<a href="' . route('network-associates.edit', $row->id) . '?tab=nwa' . '">' . $name . '</a>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('network-associates.edit', $row->id) . '?tab=nwa' . '"  class="btn btn-sm btn-success btn-icon waves-effect waves-light">
                    <i class="mdi mdi-lead-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['user_name','action'])
                ->make(true);
        }
        $companies = Company::all();
        $regions = Region::all();

        return view('network_associates.network_associates', ['companies' => $companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $companies = Company::all();
        $countries = Country::all();
        $branches = Branch::all();
        $states = State::all();
        $cities = City::all();
        $towns = Town::all();
        return view(
            'network_associates.add_network_associate',
            compact('companies', 'countries', 'states', 'cities', 'towns', 'branches')
        );
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
            'nwa_name' => 'required',
            'company' => 'required',
            'email' => 'required | email | unique:users',
            'password' => 'required | min:8',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'CNIC' => 'required | unique:users',
            'NTN' => 'required | unique:network_associates',
            'STRN' => 'required | unique:network_associates'
        ]);

        DB::beginTransaction();
        $user = User::create([
            'name' => $request->nwa_name,
            'email' => $request->email,
            'CNIC' => $request->CNIC,
            'password' => bcrypt($request->password),
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender
        ]);

        $user->addRole('network_associate'); // Updated for Laratrust v8

        $nwa  = $user->networkAssociates()->create([
            'NTN' => $request->NTN,
            'STRN' => $request->STRN,
            'company_id' => $request->company
        ]);
        DB::commit();

        return redirect(route('network-associates.edit', $nwa->id) . '?tab=nwa')->with('success', 'Network Associate Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NetworkAssociate  $networkAssociate
     * @return \Illuminate\Http\Response
     */
    public function show(NetworkAssociate $networkAssociate)
    {
        $data['nwa_branches'] = $networkAssociate->branches ? $networkAssociate->branches->pluck('id')->toArray() : array();
        $data['branches'] = Branch::where(['company_id' => $networkAssociate->company_id])->doesntHave('nwa')->orWhereIn('id', $data['nwa_branches'])->get();

        return view('network_associates.assign_branch_to_nwa', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NetworkAssociate  $networkAssociate
     * @return \Illuminate\Http\Response
     */
    public function edit(NetworkAssociate $networkAssociate)
    {
        $companies = Company::all();
        $countries = Country::all();

        $contactInformation = $networkAssociate->contact_information;
        //        dd($networkAssociate);

        $returnData = [
            'network_associate' => $networkAssociate->load('user', 'company'),
            'contact_information' => $contactInformation,
            'companies' => $companies,
            'countries' => $countries,
        ];

        if (! empty($contactInformation)) {
            $states = State::where('country_id', $contactInformation->country_id)->get();
            $cities = City::where('state_id', $contactInformation->state_id)->get();
            $towns = Town::where('city_id', $contactInformation->city_id)->get();
            $returnData['states'] = $states;
            $returnData['cities'] = $cities;
            $returnData['towns'] = $towns;
        }


        return view('network_associates.add_network_associate', $returnData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NetworkAssociate  $networkAssociate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NetworkAssociate $networkAssociate)
    {
        $validation = [
            'nwa_name' => 'required',
            'company' => 'required',
            'email' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
            'CNIC' => 'required',
            'NTN' => 'required',
            'STRN' => 'required'
        ];

        if (isset($request->password) && ! empty($request->password)) {
            $validation['password'] = 'min:8';
        }

        request()->validate($validation);

        $networkAssociate->update([
            'NTN' => $request->NTN,
            'STRN' => $request->STRN,
            'company_id' => $request->company
        ]);

        if (isset($request->password)) {
            $networkAssociate->user()->update([
                'name' => $request->nwa_name,
                'email' => $request->email,
                'CNIC' => $request->CNIC,
                'password' => bcrypt($request->password),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender
            ]);
        } else {
            $networkAssociate->user()->update([
                'name' => $request->nwa_name,
                'email' => $request->email,
                'CNIC' => $request->CNIC,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender
            ]);
        }

        return redirect()->back()->with('success', 'Network Associate Updated.');
        /*return view('network_associates.add_network_associate', [
            'network_associate' => $networkAssociate->load('user', 'company'),
            'companies' => $companies,
            'countries' => $countries
        ]);*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NetworkAssociate  $networkAssociate
     * @return \Illuminate\Http\Response
     */
    public function destroy(NetworkAssociate $networkAssociate)
    {
        //
    }

    public function profile(NetworkAssociate $networkAssociate)
    {
        // dd('Hello');
        return view('network_associates.profile');
    }
}
