<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AcademicYearWorkingDays;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\NetworkAssociate;
use App\Models\Region;
use App\Models\State;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class AcademicYearWorkingDaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->branch_id);
        $user = \Auth::user();
        $branch_id = 0;
        $branches = null;
        if ($user->hasRole('teacher')) {
            $employee = Employee::where('user_id', $user->id)->with(['branch', 'states'])->first();
            // dd();
            $branch_id = $employee->branch_id;
            $data['states'] = $employee->states->toArray();
            $data['branch'] = $employee->branch->toArray();
        } elseif ($user->hasRole('network_associate')) {
            $employee = NetworkAssociate::where('user_id', $user->id)->with('branches')->first();
            // $branch_id = $employee->branch_id;
            $branch_id = get_branch_id();
            $branches = $employee->branches->where('id', $branch_id)->pluck('id')->toArray();
            // dd($branches);
            $data['states'] = State::all();
            $data['branches'] = $employee->branches;
        } elseif ($user->hasRole('subject_coordinator')) {
            $employee = Employee::where('user_id', $user->id)->with(['branch', 'states'])->first();
            // dd();
            $branch_id = $employee->branch_id;
            $data['states'] = $employee->states->toArray();
            $data['branch'] = $employee->branch->toArray();
        } elseif ($user->hasRole('academic_head')) {
            $employee = Employee::where('user_id', $user->id)->with(['branch', 'states'])->first();
            // dd();
            $branch_id = $employee->branch_id;
            $data['states'] = $employee->states->toArray();
            $data['branch'] = $employee->branch->toArray();
        } elseif ($user->hasRole('school_head')) {
            $employee = Employee::where('user_id', $user->id)->with(['branch', 'states'])->first();
            // dd();
            $branch_id = $employee->branch_id;
            $data['states'] = $employee->states->toArray();
            $data['branch'] = $employee->branch->toArray();
        } else {
            $data['branches'] = Branch::all();
            $data['states'] = State::all();
        }
        // dd(count($branches));
        if ($request->ajax()) {
            if ($branch_id == 0 && $branches == null) {
                $data = AcademicYearWorkingDays::with(['state', 'branch', 'academic_year', 'term'])->get();
            } elseif ($branches != null && count($branches) > 0) {
                $data = AcademicYearWorkingDays::whereIn('branch_id', $branches)->with(['state', 'branch', 'academic_year', 'term'])->get();
            } else {
                $data = AcademicYearWorkingDays::where('branch_id', $branch_id)->with(['state', 'branch', 'academic_year', 'term'])->get();
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('branch_name', function ($row) {
                    return $row->branch ? $row->branch->br_name : 'N/A';
                })
                ->addColumn('academic_year_title', function ($row) {
                    return $row->academic_year ? $row->academic_year->title : 'N/A';
                })
                ->addColumn('term_name', function ($row) {
                    return $row->term ? $row->term->name : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.academic_year_working_days.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data['terms'] = Term::all();
        $data['academic_years'] = AcademicYear::all();


        return view('settings.academic_year_working_days.academic_year_working_days', $data);
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
        // dd($request->all());
        $request->validate([
            'branch_id',
            'academic_year_id',
            'term_id',
            'working_days',
        ]);

        $existing_working_days_entry = AcademicYearWorkingDays::where([
            ['academic_year_id', $request['academic_year_id']],
            ['term_id', $request['term_id']],
            ['branch_id', $request['branch_id']],
        ])->first();
        if (! empty($existing_working_days_entry)) {
            return redirect()->back()->with('error', 'Working days already exists');
            // return redirect()->route('academic-year-working-days.index')->with('error', 'Working days already exists');
        } else {
            AcademicYearWorkingDays::create($request->all());

            return redirect()->route('academic-year-working-days.index')
                ->with('success', 'Academic year has been added successfully.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AcademicYearWorkingDays  $academicYearWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function show(AcademicYearWorkingDays $academicYearWorkingDays)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AcademicYearWorkingDays  $academicYearWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $academicYearWorkingDays = AcademicYearWorkingDays::findOrFail($id);
        $user = \Auth::user();
        if ($user->hasRole('teacher')) {
            $employee = Employee::where('user_id', $user->id)->with(['branch', 'states'])->first();
            $data['states'] = $employee->states->toArray();
            $data['branch'] = $employee->branch->toArray();
        } elseif ($user->hasRole('network_associate')) {
            $employee = NetworkAssociate::where('user_id', $user->id)->with('branches')->first();
            $data['states'] = State::all();
            $data['branches'] = $employee->branches;
        } else {
            $data['branches'] = Branch::all();
            $data['states'] = State::all();
        }
        $data['terms'] = Term::all();
        $data['academic_years'] = AcademicYear::all();

        $data['academicYearWorkingDays'] = $academicYearWorkingDays;
        return view('settings.academic_year_working_days.academic_year_working_days', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AcademicYearWorkingDays  $academicYearWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcademicYearWorkingDays $academicYearWorkingDays)
    {
        $request->validate([
            'state_id',
            'branch_id',
            'academic_year_id',
            'term_id',
            'working_days',
        ]);
        // dd($request->all());
        $record = AcademicYearWorkingDays::findOrFail($request->academic_year_working_days_id);
        $record->update($request->all());

        return redirect()->route('academic-year-working-days.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AcademicYearWorkingDays  $academicYearWorkingDays
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcademicYearWorkingDays $academicYearWorkingDays)
    {
        try {
            return $academicYearWorkingDays->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
