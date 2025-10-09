<?php

namespace App\Http\Controllers;

use App\Models\StudentTransferReason;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class StudentTransferReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentTransferReason::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.student_transfer_reason.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('settings.student_transfer_reason.student_transfer_reasons');
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
            'transfer_reason' => 'required',
            'description' => 'required',
        ]);

        StudentTransferReason::create($request->all());

        return redirect()->route('student-transfer-reason.index')
            ->with('success', 'Transfer reason has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentTransferReason  $studentTransferReason
     * @return \Illuminate\Http\Response
     */
    public function show(StudentTransferReason $studentTransferReason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentTransferReason  $studentTransferReason
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentTransferReason $studentTransferReason)
    {
        return view('settings.student_transfer_reason.student_transfer_reasons', ['studentTransferReason' => $studentTransferReason]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentTransferReason  $studentTransferReason
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentTransferReason $studentTransferReason)
    {
        $request->validate([
            'transfer_reason' => 'required',
            'description' => 'required',
        ]);

        $studentTransferReason->update($request->all());

        return redirect()->route('student-transfer-reason.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentTransferReason  $studentTransferReason
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentTransferReason $studentTransferReason)
    {
        try {
            return $studentTransferReason->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
