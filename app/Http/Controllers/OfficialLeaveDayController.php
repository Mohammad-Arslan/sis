<?php

namespace App\Http\Controllers;

use App\Models\OfficialLeaveDay;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class OfficialLeaveDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = OfficialLeaveDay::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status == '1') {
                        $Status =  "Eid al-Fitar";
                    } else if ($row->status == '2') {
                        $Status =  "Eid al-Adha";
                    } else if ($row->status == '3') {
                        $Status =  "Pakistan Day";
                    } else if ($row->status == '4') {
                        $Status =  "Independence Day";
                    } else if ($row->status == '5') {
                        $Status =  "Quaid-e-Azam Day";
                    } else if ($row->status == '6') {
                        $Status =  "Labour Day";
                    } else if ($row->status == '7') {
                        $Status =  "Muharram";
                    }
                    return $Status;
                })
                ->addColumn('action', function ($row) {
                    return view('settings.working_shifts.official_leave_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //dd($parents->toArray());
        return view('settings.working_shifts.official_leaves');
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
            'status' => 'required|unique:official_leave_days,status',
        ]);
        //dd($request->all());
        OfficialLeaveDay::create($request->all());

        return redirect()->route('official-leave-day.index')
            ->with('success', 'Official leave has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OfficialLeaveDay  $officialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function show(OfficialLeaveDay $officialLeaveDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OfficialLeaveDay  $officialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function edit(OfficialLeaveDay $officialLeaveDay)
    {
        return view('settings.working_shifts.official_leaves', ['officialLeaveDay' => $officialLeaveDay]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OfficialLeaveDay  $officialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OfficialLeaveDay $officialLeaveDay)
    {
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required',
        ]);

        $officialLeaveDay->update($request->all());

        return redirect()->route('official-leave-day.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OfficialLeaveDay  $officialLeaveDay
     * @return \Illuminate\Http\Response
     */
    public function destroy(OfficialLeaveDay $officialLeaveDay)
    {
        try {
            return $officialLeaveDay->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
