<?php

namespace App\Http\Controllers;

use App\Models\StudentPreviousSchool;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StudentPreviousSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        if ( $request -> ajax() ) {
            $data = StudentPreviousSchool ::all();
            // dd($data->toArray());
            return Datatables ::of($data)
                -> addIndexColumn()
                -> addColumn('action', function( $row ) {
                    return view('settings.student_previous_school.actions', ['row' => $row]);
                })
                -> rawColumns(['action'])
                -> make(TRUE);
        }

        return view('settings.student_previous_school.student_previous_school');
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        $request -> validate([
            'school_name',
        ]);

        StudentPreviousSchool ::create($request -> all());

        return redirect() -> route('student-previous-school.index')
            -> with('success', 'School name has been added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\StudentPreviousSchool $studentPreviousSchool
     * @return \Illuminate\Http\Response
     */
    public function show( StudentPreviousSchool $studentPreviousSchool )
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\StudentPreviousSchool $studentPreviousSchool
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit( StudentPreviousSchool $studentPreviousSchool )
    {
//        dd($studentPreviousSchool);
        return view('settings.student_previous_school.student_previous_school', ['studentPreviousSchool' => $studentPreviousSchool]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\StudentPreviousSchool $studentPreviousSchool
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update( Request $request, StudentPreviousSchool $studentPreviousSchool )
    {
        $request -> validate([
            'school_name',
        ]);
        $studentPreviousSchool -> update($request -> all());

        return redirect() -> route('student-previous-school.index')
            -> with('success', 'Record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\StudentPreviousSchool $studentPreviousSchool
     * @return bool|\Illuminate\Http\Response
     */
    public function destroy( StudentPreviousSchool $studentPreviousSchool )
    {
        try {
            return $studentPreviousSchool->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function getSchoolDescription(Request $request)
    {
        if($request->ajax()){
            return StudentPreviousSchool::where('id', $request->previous_school_id)->first()->description;
        }
    }
}
