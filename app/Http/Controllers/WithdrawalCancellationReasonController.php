<?php

namespace App\Http\Controllers;

use App\Models\WithdrawalCancellationReason;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class WithdrawalCancellationReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = WithdrawalCancellationReason::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.withdrawal_cancellation_reason.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('settings.withdrawal_cancellation_reason.withdrawal_cancellation_reasons');
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
            'cancellation_reason' => 'required',
            'description' => 'required',
        ]);

        WithdrawalCancellationReason::create($request->all());

        return redirect()->route('withdrawal-cancellation-reason.index')
            ->with('success', 'Withdrawal cancellation reason has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WithdrawalCancellationReason  $withdrawalCancellationReason
     * @return \Illuminate\Http\Response
     */
    public function show(WithdrawalCancellationReason $withdrawalCancellationReason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WithdrawalCancellationReason  $withdrawalCancellationReason
     * @return \Illuminate\Http\Response
     */
    public function edit(WithdrawalCancellationReason $withdrawalCancellationReason)
    {
        return view('settings.withdrawal_cancellation_reason.withdrawal_cancellation_reasons', ['withdrawalCancellationReason' => $withdrawalCancellationReason]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WithdrawalCancellationReason  $withdrawalCancellationReason
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WithdrawalCancellationReason $withdrawalCancellationReason)
    {
        $request->validate([
            'cancellation_reason' => 'required',
            'description' => 'required',
        ]);

        $withdrawalCancellationReason->update($request->all());

        return redirect()->route('withdrawal-cancellation-reason.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WithdrawalCancellationReason  $withdrawalCancellationReason
     * @return \Illuminate\Http\Response
     */
    public function destroy(WithdrawalCancellationReason $withdrawalCancellationReason)
    {
        try {
            return $withdrawalCancellationReason->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
