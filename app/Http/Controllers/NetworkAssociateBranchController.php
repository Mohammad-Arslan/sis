<?php

namespace App\Http\Controllers;

use App\Models\NetworkAssociateBranch;
use Illuminate\Http\Request;

class NetworkAssociateBranchController extends Controller
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
        $nwa_branch_association = NetworkAssociateBranch::where(['nwa_id' => $request->nwa_id,'branch_id' => $request->branch_id]);

        if (empty($nwa_branch_association->first())) {
            NetworkAssociateBranch::create($request->all());
        } else {
            $nwa_branch_association->delete();
        }

        return redirect()->back()->with('success', 'Branch uccessfully Assigned to Network Associate.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NetworkAssociateBranch  $networkAssociateBranch
     * @return \Illuminate\Http\Response
     */
    public function show(NetworkAssociateBranch $networkAssociateBranch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NetworkAssociateBranch  $networkAssociateBranch
     * @return \Illuminate\Http\Response
     */
    public function edit(NetworkAssociateBranch $networkAssociateBranch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NetworkAssociateBranch  $networkAssociateBranch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NetworkAssociateBranch $networkAssociateBranch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NetworkAssociateBranch  $networkAssociateBranch
     * @return \Illuminate\Http\Response
     */
    public function destroy(NetworkAssociateBranch $networkAssociateBranch)
    {
        //
    }

    public function set_branch(Request $request)
    {

        try {
            get_set_NWABranchId($request->branch_id);

            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'Data Set Successfully.','data' => new \stdClass()]);
        } catch (\Exception $exception) {
            return response()->json(['code' => 422, 'status' => 'false', 'message' => $exception->getMessage(), 'data' => new \stdClass()]);
        }
    }
}
