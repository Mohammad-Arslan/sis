<?php

namespace App\Http\Controllers;

use App\Models\WorkingShift;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;
class WorkingShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = WorkingShift::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if($row->status == '0'){
                        $Status =  "Off Day";
                    }else if($row->status == '1'){
                        $Status =  "Working Shift";
                    }else if($row->status == '2'){
                        $Status =  "Eid al-Fitar";
                    }else if($row->status == '3'){
                        $Status =  "Eid al-Adha";
                    }
                    else if($row->status == '4'){
                        $Status =  "Pakistan Day";
                    }else if($row->status == '5'){
                        $Status =  "Independence Day";
                    }
                    else if($row->status == '6'){
                        $Status =  "Quaid-e-Azam Day";
                    }else if($row->status == '7'){
                        $Status =  "Labour Day";
                    }
                    return $Status;
                })
                ->addColumn('action', function ($row) {
                    return view('settings.working_shifts.shifts_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //dd($parents->toArray());
        return view('settings.working_shifts.working_shifts');
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
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required',
        ]);

        WorkingShift::create($request->all());

        return redirect()->route('working-shift.index')
            ->with('success', 'Working shift has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkingShift  $workingShift
     * @return \Illuminate\Http\Response
     */
    public function show(WorkingShift $workingShift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkingShift  $workingShift
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkingShift $workingShift)
    {
        return view('settings.working_shifts.working_shifts', ['workingShift' => $workingShift]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkingShift  $workingShift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkingShift $workingShift)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required',
        ]);

        $workingShift->update($request->all());

        return redirect()->route('working-shift.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkingShift  $workingShift
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkingShift $workingShift)
    {
        try {
            return $workingShift->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
