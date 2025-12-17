<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalReason;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class WithdrawalReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = WithdrawalReason::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.withdrawal_reason.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('settings.withdrawal_reason.withdrawal_reasons');
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
            'withdrawal_reason' => 'required',
            'description' => 'required',
        ]);

        WithdrawalReason::create($request->all());

        return redirect()->route('withdrawal-reason.index')
            ->with('success', 'Withdrawal reason has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WithdrawalReason  $withdrawalReason
     * @return \Illuminate\Http\Response
     */
    public function show(WithdrawalReason $withdrawalReason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WithdrawalReason  $withdrawalReason
     * @return \Illuminate\Http\Response
     */
    public function edit(WithdrawalReason $withdrawalReason)
    {
        return view('settings.withdrawal_reason.withdrawal_reasons', ['withdrawalReason' => $withdrawalReason]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WithdrawalReason  $withdrawalReason
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WithdrawalReason $withdrawalReason)
    {
        $request->validate([
            'withdrawal_reason' => 'required',
            'description' => 'required',
        ]);

        $withdrawalReason->update($request->all());

        return redirect()->route('withdrawal-reason.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WithdrawalReason  $withdrawalReason
     * @return \Illuminate\Http\Response
     */
    public function destroy(WithdrawalReason $withdrawalReason)
    {
        try {
            return $withdrawalReason->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
