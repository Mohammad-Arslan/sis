<?php

namespace App\Http\Controllers;

use App\Models\FeeChargesType;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;

class FeeChargesTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FeeChargesType::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.fee_charges_type.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('settings.fee_charges_type.fee_charges_type');
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
            'frequency' => 'required',
        ]);

        FeeChargesType::create($request->all());

        return redirect()->route('fee-charges-type.index')
            ->with('success', 'Fee Charges created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeeChargesType  $feeChargesType
     * @return \Illuminate\Http\Response
     */
    public function show(FeeChargesType $feeChargesType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FeeChargesType  $feeChargesType
     * @return \Illuminate\Http\Response
     */
    public function edit(FeeChargesType $feeChargesType)
    {
        return view('settings.fee_charges_type.fee_charges_type', ['feeChargesType' => $feeChargesType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeeChargesType  $feeChargesType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeeChargesType $feeChargesType)
    {
        $request->validate([
            'name' => 'required',
            'frequency' => 'required',
        ]);

        $feeChargesType->update($request->all());

        return redirect()->route('fee-charges-type.index')
            ->with('success', 'Fee Charges Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeeChargesType  $feeChargesType
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeeChargesType $feeChargesType)
    {
        try {
            return $feeChargesType->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
