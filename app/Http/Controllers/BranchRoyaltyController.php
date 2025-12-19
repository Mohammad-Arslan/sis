<?php

namespace App\Http\Controllers;

use App\Models\BranchRoyalty;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BranchRoyaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BranchRoyalty::with(['branch.nwa.user','employee.user'])->where('branch_id', $request->branch_id)->get();

            return DataTables::of($data)
                ->addColumn('nwa_name', function ($row) {
                    return isset($row['branch']['nwa']['user']) ? $row['branch']['nwa']['user']['name'] : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('branches.royalty.royalty_action', ['row' => $row]);
                })
                ->addColumn('updated_by', function ($row) {
                    return isset($row['employee']) ? ucwords($row['employee']['user']['name']) : '-';
                })
                ->rawColumns(['nwa_name','action'])
                ->make(true);
        }
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
            'royalty_rate' => 'required|numeric',
            // 'sales_tax' => 'required',
            //'fed' => 'required',
            'with_effect_from' => 'required',
            'closing_date' => 'required',
        ]);

        BranchRoyalty::create($request->all());

        return redirect()->back()->with('success', 'Royalty Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchRoyalty  $branchRoyalty
     * @return \Illuminate\Http\Response
     */
    public function show(BranchRoyalty $branchRoyalty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchRoyalty  $branchRoyalty
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchRoyalty $branchRoyalty)
    {
        return redirect()->back()->with(['branch_royalty' => $branchRoyalty]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchRoyalty  $branchRoyalty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchRoyalty $branchRoyalty)
    {
        $request->validate([
            'royalty_rate' => 'required|numeric',
            // 'sales_tax' => 'required',
            //'fed' => 'required',
            'with_effect_from' => 'required',
            'closing_date' => 'required',
        ]);

        $branchRoyalty->update($request->all());

        return redirect()->back()->with('success', 'Royalty Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchRoyalty  $branchRoyalty
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchRoyalty $branchRoyalty)
    {
        //
    }
}
