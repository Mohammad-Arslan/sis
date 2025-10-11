<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\DataTables;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AcademicYear::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.academic_years.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('settings.academic_years.academic_years');
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
            'title' => 'required',
        ]);

        if($request->active == 1){
            AcademicYear::where('active',1)->update(['active' => 0]);
        }

        AcademicYear::create($request->all());

        return redirect()->route('academic-year.index')
            ->with('success', 'Academic year has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function show(AcademicYear $academicYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function edit(AcademicYear $academicYear)
    {
        return view('settings.academic_years.academic_years', ['academicYear' => $academicYear]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AcademicYear $academicYear)
    {
        $request->validate([
            'title' => 'required',
        ]);

        if($request->active == 1){
            AcademicYear::where('active',1)->update(['active' => 0]);
        }

        $academicYear->update($request->all());

        return redirect()->route('academic-year.index')
            ->with('success', 'Record has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(AcademicYear $academicYear)
    {
        try {
            return $academicYear->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
