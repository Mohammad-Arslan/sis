<?php

namespace App\Http\Controllers;

use App\Models\WorkingDay;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class WorkingDayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = WorkingDay::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.working_shifts.days_actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        //dd($parents->toArray());
        return view('settings.working_shifts.working_days');
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
            'name' => 'required|unique:working_days,name,except,id',
            'abbreviation' => 'required|unique:working_days,abbreviation,except,id',
            'sort_order' => 'required'
        ]);

        WorkingDay::create($request->all());

        return redirect()->route('working-day.index')
            ->with('success', 'Working day has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkingDay  $workingDay
     * @return \Illuminate\Http\Response
     */
    public function show(WorkingDay $workingDay)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkingDay  $workingDay
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkingDay $workingDay)
    {
        return view('settings.working_shifts.working_days', ['workingDay' => $workingDay]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkingDay  $workingDay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkingDay $workingDay)
    {
        $request->validate([
            'name' => 'required',
            'abbreviation' => 'required',
            'sort_order' => 'required'
        ]);

        $workingDay->update($request->all());

        return redirect()->route('working-day.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkingDay  $workingDay
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkingDay $workingDay)
    {
        try {
            return $workingDay->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
