<?php

namespace App\Http\Controllers;

use App\Models\FeeConcessionType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use DataTables;

class FeeConcessionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FeeConcessionType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.fee_concessions_type.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('settings.fee_concessions_type.fee_concessions_type');
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
            'name' => 'required',
        ]);

        FeeConcessionType::create($request->all());

        return redirect()->route('fee-concessions-type.index')
            ->with('success', 'Fee Concessions created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeeConcessionType  $feeConcessionType
     * @return \Illuminate\Http\Response
     */
    public function show(FeeConcessionType $feeConcessionType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeeConcessionType  $feeConcessionType
     * @return \Illuminate\Http\Response
     */
    public function edit(FeeConcessionType $feeConcessionsType)
    {
        return view('settings.fee_concessions_type.fee_concessions_type', ['feeConcessionsType' => $feeConcessionsType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeeConcessionType  $feeConcessionType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeConcessionType $feeConcessionsType)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $feeConcessionsType->update($request->all());

        return redirect()->route('fee-concessions-type.index')
            ->with('success', 'Fee Concessions Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeeConcessionType  $feeConcessionType
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeConcessionType $feeConcessionsType)
    {
        try {
            return $feeConcessionsType->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
