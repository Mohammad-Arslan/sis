<?php

namespace App\Http\Controllers;

use App\Models\SubjectGroup;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;

class SubjectGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SubjectGroup::get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('settings.subject_groups.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('settings.subject_groups.subject_groups');
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
            'subject_group_name' => 'required|unique:subject_groups,subject_group_name',
        ]);

        SubjectGroup::create($request->all());

        return redirect()->route('subject-groups.index')
            ->with('success', 'Subject Group created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubjectGroup  $subjectGroup
     * @return \Illuminate\Http\Response
     */
    public function show(SubjectGroup $subjectGroup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubjectGroup  $subjectGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(SubjectGroup $subjectGroup)
    {
        return view('settings.subject_groups.subject_groups', ['subjectGroup' => $subjectGroup]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubjectGroup  $subjectGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubjectGroup $subjectGroup)
    {
        $request->validate([
            'subject_group_name' => 'required|unique:subject_groups,subject_group_name,' . $subjectGroup->id,
        ]);

        $subjectGroup->update($request->all());

        return redirect()->route('subject-groups.index')
            ->with('success', 'Subject Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubjectGroup  $subjectGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubjectGroup $subjectGroup)
    {
        try {
            return $subjectGroup->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
