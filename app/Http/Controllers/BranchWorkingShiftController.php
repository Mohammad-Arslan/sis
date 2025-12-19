<?php

namespace App\Http\Controllers;

use App\Models\WorkingDay;
use App\Models\WorkingShift;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\BranchWorkingShift;
use App\Models\StaffType;
use Illuminate\Database\QueryException;

class BranchWorkingShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BranchWorkingShift::with([
               'staff',
               'day',
               'working_shift'
            ])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('shift_timing', function ($row) {
                    $ShiftTiming = $row->working_shift->start_time . ' - ' . $row->working_shift->end_time;
                    return $ShiftTiming;
                })
                ->addColumn('status', function ($row) {
                    if ($row->working_shift->status == '0') {
                        $Status =  "Off Day";
                    } else {
                        $Status =  "Working Shift";
                    }
                    return $Status;
                })
                ->addColumn('action', function ($row) {
                    return view('settings.branch_schedule.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        //dd($data->toArray());
        }
        $staff_types = StaffType::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::orderBy('start_time')->orderBy('end_time')->get();

        return view('settings.branch_schedule.branch_schedules', [
            'staff_types' => $staff_types,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts
        ]);
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
            'staff_id' => 'required',
            'working_day_id' => 'required',
            'working_shift_id' => 'required',
        ]);
        $inputs = [];
        foreach ($request->working_day_id as $working_day_id) {
            $inputs[] = ['name' => $request->name,'staff_id' => $request->staff_id,'working_day_id' => $working_day_id, 'working_shift_id' => $request->working_shift_id];
        }
        //dd($inputs);
        collect($inputs)->each(function ($input) {
            BranchWorkingShift::create($input);
        });


        return redirect()->route('branch-working-shift.index')
            ->with('success', 'Selected Cadre schedule has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchWorkingShift  $branchWorkingShift
     * @return \Illuminate\Http\Response
     */
    public function show(BranchWorkingShift $branchWorkingShift)
    {
        //
    }

    public function viewBranchSchedule()
    {
        $staff_schedules = $schedules = [];
        //$staff_types = StaffType::get();
        $staff_schedules = BranchWorkingShift::select('branch_working_shifts.staff_id', 'branch_working_shifts.name as term', 'wd.abbreviation', 'wd.name as day', 'ws.start_time', 'ws.end_time', 'st.type_name', 'st.description')
        ->join('staff_types as st', 'branch_working_shifts.staff_id', '=', 'st.id')
        ->join('working_days as wd', 'branch_working_shifts.working_day_id', '=', 'wd.id')
        ->join('working_shifts as ws', 'branch_working_shifts.working_shift_id', '=', 'ws.id')
        ->orderBy('branch_working_shifts.id', 'ASC')
        ->orderBy('branch_working_shifts.staff_id', 'ASC')
        ->orderBy('branch_working_shifts.working_day_id', 'ASC')
        ->orderBy('branch_working_shifts.name', 'ASC')
        ->groupBy('branch_working_shifts.id', 'branch_working_shifts.staff_id', 'branch_working_shifts.name', 'wd.abbreviation', 'wd.name', 'ws.start_time', 'ws.end_time', 'st.type_name', 'st.description')
        ->get();
        //dd($staff_schedules->toArray());
        if ($staff_schedules) {
            $term = $ramadan = $break = 1;
            $staff_id = $staff_schedules[0]->staff_id;

            // dd($schedules);
            //$schedules['description'] = $staff_schedules[0]->description;
            //$schedules['type_name'] = $staff_schedules[0]->type_name;
            foreach ($staff_schedules as $staff_schedule) {
                if ($staff_schedule->staff_id == $staff_id) {
                    if ($staff_schedule->term == "During Term") {
                        if ($staff_schedule->day == "Monday") {
                            array_push($schedules, [
                                "order" => $term,
                                "term" => '1',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Tuesday") {
                            array_push($schedules, [
                                "order" => $term,
                                "term" => '1',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Wednesday") {
                            array_push($schedules, [
                                "order" => $term,
                                "term" => '1',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Thursday") {
                            array_push($schedules, [
                                "order" => $term,
                                "term" => '1',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Friday") {
                            array_push($schedules, [
                                "order" => $term,
                                "term" => '1',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                            $term++;
                        }
                    }
                    if ($staff_schedule->term == "During Ramadan") {
                        if ($staff_schedule->day == "Monday") {
                            array_push($schedules, [
                                "order" => $ramadan,
                                "term" => '3',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Tuesday") {
                            array_push($schedules, [
                                "order" => $ramadan,
                                "term" => '3',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Wednesday") {
                            array_push($schedules, [
                                "order" => $ramadan,
                                "term" => '3',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Thursday") {
                            array_push($schedules, [
                                "order" => $ramadan,
                                "term" => '3',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Friday") {
                            array_push($schedules, [
                                "order" => $ramadan,
                                "term" => '3',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                            $ramadan++;
                        }
                    }
                    if ($staff_schedule->term == "During Winter/Summer/Spring Break") {
                        if ($staff_schedule->day == "Monday") {
                            array_push($schedules, [
                               "order" => $break,
                               "term" => '2',
                               "term_name" => $staff_schedule->term,
                               "staff" => $staff_schedule->staff_id,
                               "day" => $staff_schedule->day,
                               "start_time" => $staff_schedule->start_time,
                               "end_time" => $staff_schedule->end_time,
                               "description" => $staff_schedule->description,
                               "type_name" => $staff_schedule->type_name,
                               "description" => $staff_schedule->description,
                               "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Tuesday") {
                            array_push($schedules, [
                                "order" => $break,
                                "term" => '2',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Wednesday") {
                            array_push($schedules, [
                                "order" => $break,
                                "term" => '2',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Thursday") {
                            array_push($schedules, [
                                "order" => $break,
                                "term" => '2',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                        }
                        if ($staff_schedule->day == "Friday") {
                            array_push($schedules, [
                                "order" => $break,
                                "term" => '2',
                                "term_name" => $staff_schedule->term,
                                "staff" => $staff_schedule->staff_id,
                                "day" => $staff_schedule->day,
                                "start_time" => $staff_schedule->start_time,
                                "end_time" => $staff_schedule->end_time,
                                "description" => $staff_schedule->description,
                                "type_name" => $staff_schedule->type_name
                            ]);
                             $break++;
                        }
                    }
                } else {
                    //$staff_id = $staff_schedules[0]->staff_id;
                    //$schedules['description'] = $staff_schedules[0]->description;
                    //$schedules['type_name'] = $staff_schedules[0]->type_name;
                }

                $staff_id = $staff_schedule->staff_id;
            }
        }

        //dd($schedules);
        return view('settings.branch_schedule.branch_schedule_modal', ['schedules' => $schedules]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchWorkingShift  $branchWorkingShift
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchWorkingShift $branchWorkingShift)
    {
        $staff_types = StaffType::all();
        $working_days = WorkingDay::all();
        $working_shifts = WorkingShift::orderBy('start_time')->orderBy('end_time')->get();
        return view('settings.branch_schedule.branch_schedules', [
            'branchWorkingShift' => $branchWorkingShift,
            'staff_types' => $staff_types,
            'working_days' => $working_days,
            'working_shifts' => $working_shifts
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchWorkingShift  $branchWorkingShift
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchWorkingShift $branchWorkingShift)
    {
        $request->validate([
            'name' => 'required',
            'staff_id' => 'required',
            'working_day_id' => 'required',
            'working_shift_id' => 'required',
        ]);
        $inputs = [];
        $branchWorkingShift->update($request->all());
        //foreach($request->working_day_id as $working_day_id)
        //{
            //$inputs[] = ['name' => $request->name,'staff_id' => $request->staff_id,'working_day_id' => $working_day_id, 'working_shift_id' => $request->working_shift_id];
        //}
        //dd($inputs);
        //collect($inputs)->each(function ($input) use($branchWorkingShift){ $branchWorkingShift->update($input); } );
        return redirect()->route('branch-working-shift.index')
            ->with('success', 'Selected Cadre schedule has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchWorkingShift  $branchWorkingShift
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchWorkingShift $branchWorkingShift)
    {
        //
    }
}
