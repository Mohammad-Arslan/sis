<?php

namespace App\Http\Controllers;

use App\Models\ComClass;
use App\Models\GradingCriteria;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GradingCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = GradingCriteria::with(['classes'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('classes', function ($row) {
                    return $row['classes'] ? implode(',', $row['classes']->pluck('class_name')->toArray()) : '';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.grading_criteria.action', ['row' => $row]);
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        $data['grading_criterias'] = GradingCriteria::where('status', 'active')->get();
        $data['classes'] = ComClass::all();

        return view('settings.grading_criteria.index', $data);
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
            "title" => [
                "required",
                "string",
                "min:3",
                "max:100",
                "regex:/^[a-zA-Z0-9\s\-\.]+$/",
                "not_regex:/^\s*$/"
            ],
            "grading_key" => [
                "required",
                "string",
                "min:1",
                "max:10",
                "regex:/^[a-zA-Z0-9\s\-\.]+$/",
                "not_regex:/^\s*$/"
            ],
            "starting_percentage" => [
                "required",
                "numeric",
                "min:0",
                "max:100"
            ],
            "ending_percentage" => [
                "required",
                "numeric",
                "min:0",
                "max:100"
            ],
            "status" => "required",
        ], [
            'title.required' => 'The title field is required.',
            'title.min' => 'The title must be at least 3 characters.',
            'title.max' => 'The title may not be greater than 100 characters.',
            'title.regex' => 'The title may only contain letters, numbers, spaces, hyphens, and periods.',
            'title.not_regex' => 'The title cannot be empty or contain only spaces.',
            'grading_key.required' => 'The grading key field is required.',
            'grading_key.min' => 'The grading key must be at least 1 character.',
            'grading_key.max' => 'The grading key may not be greater than 10 characters.',
            'grading_key.regex' => 'The grading key may only contain letters, numbers, spaces, hyphens, and periods.',
            'grading_key.not_regex' => 'The grading key cannot be empty or contain only spaces.',
            'starting_percentage.required' => 'The starting percentage field is required.',
            'starting_percentage.numeric' => 'The starting percentage must be a number.',
            'starting_percentage.min' => 'The starting percentage must be at least 0.',
            'starting_percentage.max' => 'The starting percentage may not be greater than 100.',
            'ending_percentage.required' => 'The ending percentage field is required.',
            'ending_percentage.numeric' => 'The ending percentage must be a number.',
            'ending_percentage.min' => 'The ending percentage must be at least 0.',
            'ending_percentage.max' => 'The ending percentage may not be greater than 100.',
        ]);

        $grading_criteria = GradingCriteria::create($request->all());
        $grading_criteria->classes()->attach($request->class_ids);

        return redirect()->back()->with(['success', 'Grading Criteria created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GradingCriteria  $gradingCriteria
     * @return \Illuminate\Http\Response
     */
    public function show(GradingCriteria $gradingCriteria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GradingCriteria  $gradingCriteria
     * @return \Illuminate\Http\Response
     */
    public function edit(GradingCriteria $gradingCriterion)
    {
        $data['gradingCriteria'] = $gradingCriterion;
        $data['grading_criterias'] = GradingCriteria::with(['classes'])->where('status', 'active')->get();
        $data['classes'] = ComClass::all();

        return view('settings.grading_criteria.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GradingCriteria  $gradingCriteria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradingCriteria $gradingCriterion)
    {
        $request->validate([
            "title" => [
                "required",
                "string",
                "min:3",
                "max:100",
                "regex:/^[a-zA-Z0-9\s\-\.]+$/",
                "not_regex:/^\s*$/"
            ],
            "grading_key" => [
                "required",
                "string",
                "min:1",
                "max:10",
                "regex:/^[a-zA-Z0-9\s\-\.]+$/",
                "not_regex:/^\s*$/"
            ],
            "starting_percentage" => [
                "required",
                "numeric",
                "min:0",
                "max:100"
            ],
            "ending_percentage" => [
                "required",
                "numeric",
                "min:0",
                "max:100"
            ],
            "status" => "required",
        ], [
            'title.required' => 'The title field is required.',
            'title.min' => 'The title must be at least 3 characters.',
            'title.max' => 'The title may not be greater than 100 characters.',
            'title.regex' => 'The title may only contain letters, numbers, spaces, hyphens, and periods.',
            'title.not_regex' => 'The title cannot be empty or contain only spaces.',
            'grading_key.required' => 'The grading key field is required.',
            'grading_key.min' => 'The grading key must be at least 1 character.',
            'grading_key.max' => 'The grading key may not be greater than 10 characters.',
            'grading_key.regex' => 'The grading key may only contain letters, numbers, spaces, hyphens, and periods.',
            'grading_key.not_regex' => 'The grading key cannot be empty or contain only spaces.',
            'starting_percentage.required' => 'The starting percentage field is required.',
            'starting_percentage.numeric' => 'The starting percentage must be a number.',
            'starting_percentage.min' => 'The starting percentage must be at least 0.',
            'starting_percentage.max' => 'The starting percentage may not be greater than 100.',
            'ending_percentage.required' => 'The ending percentage field is required.',
            'ending_percentage.numeric' => 'The ending percentage must be a number.',
            'ending_percentage.min' => 'The ending percentage must be at least 0.',
            'ending_percentage.max' => 'The ending percentage may not be greater than 100.',
        ]);

        $gradingCriterion->update($request->all());
        $gradingCriterion->classes()->sync($request->class_ids);

        return redirect()->route('grading-criteria.index')->with(['success', 'Grading Criteria updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GradingCriteria  $gradingCriteria
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradingCriteria $gradingCriteria)
    {
        //
    }
}
