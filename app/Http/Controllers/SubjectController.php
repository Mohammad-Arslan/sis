<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Subject;
use App\Models\SubjectGroup;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Subject::with(['subject_group', 'language'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.subjects.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $subjectGroups = SubjectGroup::get();
        $languages = Language::get();
        return view('settings.subjects.subjects', ['subjectGroups' => $subjectGroups, 'languages' => $languages]);
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
            'subject_name' => 'required',
            'language_id' => 'required',
            'is_academic' => 'required',
            'subject_type' => 'required'
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        $subjectGroups = SubjectGroup::get();
        $languages = Language::get();
        return view('settings.subjects.subjects', ['subject' => $subject, 'subjectGroups' => $subjectGroups, 'languages' => $languages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'subject_name' => 'required',
            'language_id' => 'required',
            'is_academic' => 'required',
            'subject_type' => 'required'
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        try {
            return $subject->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
