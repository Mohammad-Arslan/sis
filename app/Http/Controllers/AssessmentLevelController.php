<?php

namespace App\Http\Controllers;

use App\Models\AssessmentLevel;
use App\Models\PaperType;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AssessmentLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Use query builder instead of get() to allow DataTables server-side processing
            $data = AssessmentLevel::with('parent');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('parent', function ($row) {
                    // Use object property, not array access, since $row is a model
                    return $row->parent ? $row->parent->name : '';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.assessment_level.action', ['row' => $row]);
                })
                ->order(function ($query) {
                    $query->orderBy('id', 'desc');
                })
                ->rawColumns(['parent','status','action'])
                ->make(true);
        }

        $data['assessment_levels'] = AssessmentLevel::where('status', 'active')->get();
        $data['multi_levels'] = AssessmentLevel::where([['status','active'],['parent_id',0]])->get();

        return view('settings.assessment_level.index', $data);
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
            "name" => "required|unique:assessment_levels,name",
            "parent_id" => "required_if:has_parent,1|nullable|exists:assessment_levels,id",
            "status" => "required",
            "sort_no" => "nullable|integer|unique:assessment_levels,sort_no,NULL,id",
        ], [
            'name.unique' => 'The assessment level name already exists.',
            'sort_no.unique' => 'The sort number is already assigned to another assessment level.',
            'parent_id.required_if' => 'Assessment Level is required when Type Has Parent is Yes.',
            'parent_id.exists' => 'The selected Assessment Level does not exist.',
        ]);

        AssessmentLevel::create($request->all());

        return redirect()->back()->with('success', 'Assessment Level created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssessmentLevel  $assessmentLevel
     * @return \Illuminate\Http\Response
     */
    public function show(AssessmentLevel $assessmentLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssessmentLevel  $assessmentLevel
     * @return \Illuminate\Http\Response
     */
    public function edit(AssessmentLevel $assessmentLevel)
    {
        $data['assessmentLevel'] = $assessmentLevel;
        $data['assessment_levels'] = AssessmentLevel::where('status', 'active')->whereNotIn('id', [$assessmentLevel->id])->get();

        return view('settings.assessment_level.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssessmentLevel  $assessmentLevel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssessmentLevel $assessmentLevel)
    {
        $request->validate([
            "name" => "required|unique:assessment_levels,name," . $assessmentLevel->id,
            "parent_id" => "required_if:has_parent,1|nullable|exists:assessment_levels,id",
            "status" => "required",
            "sort_no" => "nullable|integer|unique:assessment_levels,sort_no," . $assessmentLevel->id . ",id",
        ], [
            'name.unique' => 'The assessment level name already exists.',
            'sort_no.unique' => 'The sort number is already assigned to another assessment level.',
            'parent_id.required_if' => 'Assessment Level is required when Type Has Parent is Yes.',
            'parent_id.exists' => 'The selected Assessment Level does not exist.',
        ]);

        $assessmentLevel->update($request->all());

        return redirect()->route('assessment-level.index')->with('success', 'Assessment Level updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssessmentLevel  $assessmentLevel
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentLevel $assessmentLevel)
    {
        //
    }
}
