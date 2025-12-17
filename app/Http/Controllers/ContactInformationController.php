<?php

namespace App\Http\Controllers;

use App\Models\ContactInformation;
use App\Models\Branch;
use App\Models\NetworkAssociate;
use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;



class ContactInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //         dd($request->all());
        $request->validate([
            'address' => 'required',
            // 'phone' => 'required | unique:contact_information',
            'mobile' => 'required | unique:contact_information',
            // 'email' => 'required | email | unique:contact_information',
            // 'fax' => 'required | unique:contact_information',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            // 'town_id' => 'required'
        ]);

        if (isset($request->branch_id)) {
            $branchAssociate = Branch::find($request->branch_id);
            $branchAssociate->contact_information()->create(request()->all());
            return redirect(route('branches.edit', $request->branch_id) . '?tab=staff')->with('success', 'Branch Contact Info Created Successfully');
        } else if (isset($request->nwa_id)) {
            //            return "called";
            $networkAssociate = NetworkAssociate::find($request->nwa_id);
            $networkAssociate->contact_information()->create(request()->all());

            return redirect(route('network-associates.edit', $request->nwa_id) . '?tab=contact');
        } else {
            return redirect(route('network-associates.edit', $request->nwa_id) . '?tab=contact');
        }

        // dd('Saved');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //        dd($request->all());

        $request->validate([
            'address' => 'required',
            // 'phone' => 'required',
            'mobile' => 'required',
            // 'email' => 'required | email',
            // 'fax' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            // 'town_id' => 'required'
        ]);

        $updateInput = [
            'address' => $request->address,
            'phone' => $request->phone,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'fax' => $request->fax,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'town_id' => $request->town_id,
        ];


        if (isset($request->branch_id)) {
            $br = Branch::find($request->branch_id);
            $br->contact_information()->update($updateInput);
            return redirect(route('branches.edit', $request->branch_id) . '?tab=contact');
        } else if (isset($request->nwa_id)) {
            $nwa = NetworkAssociate::find($request->nwa_id);
            $nwa->contact_information()->update($updateInput);
            return redirect(route('network-associates.edit', $request->nwa_id) . '?tab=contact');
        } else {
            dd($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
