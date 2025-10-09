<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Branch;
use App\Models\BranchAcademicYear;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class BranchAcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BranchAcademicYear::with(['branch','academic_year'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.branch_academic_years.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $branches = Branch::all();
        $academicyears = AcademicYear::all();
        return view('settings.branch_academic_years.branch_academic_years',
            [
                'branches' => $branches,
                'academicyears' => $academicyears
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
            'start_date' => 'required',
            'end_date' => 'required',
            'branch_id' => 'required',
            'academic_year_id' => 'required',
        ]);

        BranchAcademicYear::create($request->all());

        return redirect()->route('branch-academic-year.index')
            ->with('success', 'Branch academic year has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BranchAcademicYear  $branchAcademicYear
     * @return \Illuminate\Http\Response
     */
    public function show(BranchAcademicYear $branchAcademicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BranchAcademicYear  $branchAcademicYear
     * @return \Illuminate\Http\Response
     */
    public function edit(BranchAcademicYear $branchAcademicYear)
    {
        $branches = Branch::all();
        $academicyears = AcademicYear::all();
        return view('settings.branch_academic_years.branch_academic_years', [
            'branchAcademicYear' => $branchAcademicYear,
            'branches' => $branches,
            'academicyears' => $academicyears
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BranchAcademicYear  $branchAcademicYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BranchAcademicYear $branchAcademicYear)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
            'branch_id' => 'required',
            'academic_year_id' => 'required',
        ]);

        $branchAcademicYear->update($request->all());

        return redirect()->route('branch-academic-year.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BranchAcademicYear  $branchAcademicYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(BranchAcademicYear $branchAcademicYear)
    {
        try {
            return $branchAcademicYear->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
