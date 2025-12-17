<?php

namespace App\Http\Controllers;

use App\Models\FeeTier;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class FeeTierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FeeTier::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.fee_tier.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('settings.fee_tier.fee_tiers');
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
            'tier_name' => 'required',
            // 'description' => 'required',
        ]);

        FeeTier::create($request->all());

        return redirect()->route('fee-tier.index')
            ->with('success', 'Fee Tier created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeeTier  $feeTier
     * @return \Illuminate\Http\Response
     */
    public function show(FeeTier $feeTier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeeTier  $feeTier
     * @return \Illuminate\Http\Response
     */
    public function edit(FeeTier $feeTier)
    {
        return view('settings.fee_tier.fee_tiers', ['feeTier' => $feeTier]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeeTier  $feeTier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeTier $feeTier)
    {
        $request->validate([
            'tier_name' => 'required',
            // 'description' => 'required',
        ]);

        $feeTier->update($request->all());

        return redirect()->route('fee-tier.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeeTier  $feeTier
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeTier $feeTier)
    {
        try {
            return $feeTier->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
