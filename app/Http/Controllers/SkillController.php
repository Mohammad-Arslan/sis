<?php

namespace App\Http\Controllers;

use App\Models\AssessmentLevel;
use App\Models\ComClass;
use App\Models\Skill;
use App\Models\StudentBehaviourSkillMark;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = Skill::with(['term', 'subject', 'parent', 'com_class']);

            if (isset($request->class_id))
                $query = $query->where(function ($q) use ($request) {
                    $q->where('class_id', $request->class_id);
                    $q->orWhereNull('class_id');
                });

            if (isset($request->subject_id))
                $query = $query->where(function ($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                    $q->orWhereNull('subject_id');
                });

            if (isset($request->term_id))
                $query = $query->where(function ($q) use ($request) {
                    $q->where('term_id', $request->term_id);
                    $q->orWhereNull('term_id');
                });

            if (isset($request->type))
                $query = $query->where(function ($q) use ($request) {
                    $q->where('type', $request->type);
                });

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == 'active' ? '<span class="badge bg-success">Active</span>' : ($row->status == 'inactive' ? '<span class="badge bg-danger">In Active</span>' : $row->status);
                })
                ->addColumn('parent', function ($row) {
                    return $row['parent'] ? $row['parent']['title'] : '';
                })
                ->addColumn('action', function ($row) {
                    return view('settings.skills.action', ['row' => $row]);
                })
                ->rawColumns(['parent', 'status', 'action'])
                ->make(true);
        }

        $data['skills'] = Skill::where('status', 'active')->get();
        $data['terms'] = Term::all();
        $data['com_classes'] = ComClass::all();

        return view('settings.skills.index', $data);
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
                "min:2",
                "max:255",
                "regex:/^[a-zA-Z0-9\s\-_.,()]+$/",
                "not_regex:/^[^a-zA-Z0-9]*$/",
                "not_regex:/[!@#$%^&*+=<>?\/\\|`~\[\]{}:;\"']/"
            ],
            "parent_id" => "required_if:has_parent,1",
            //"subject_id" => "required",
            "term_id" => "required",
            "status" => "required|in:active,inactive",
        ], [
            'title.required' => 'The skill title is required.',
            'title.string' => 'The skill title must be a valid text.',
            'title.min' => 'The skill title must be at least 2 characters long.',
            'title.max' => 'The skill title must not exceed 255 characters.',
            'title.regex' => 'The skill title can only contain letters, numbers, spaces, hyphens, underscores, periods, commas, and parentheses.',
            'title.not_regex' => 'The skill title must contain at least one letter or number.',
            'status.in' => 'The status must be either active or inactive.',
        ]);

        if (empty($request->parent_id))
            $request['parent_id'] = 0;

        Skill::create($request->all());

        return redirect()->back()->with(['success', 'Skill created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function show(Skill $skill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function edit(Skill $skill)
    {
        $data['skill'] = $skill;
        $data['skills'] = Skill::where('status', 'active')->whereNotIn('id', [$skill->id])->get();
        $data['terms'] = Term::all();
        $data['com_classes'] = ComClass::all();
        $data['subjects'] = getClassSubjects($skill['class_id']);

        return view('settings.skills.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Skill $skill)
    {
        $request->validate([
            "title" => [
                "required",
                "string",
                "min:2",
                "max:255",
                "regex:/^[a-zA-Z0-9\s\-_.,()]+$/",
                "not_regex:/^[^a-zA-Z0-9]*$/",
                "not_regex:/[!@#$%^&*+=<>?\/\\|`~\[\]{}:;\"']/"
            ],
            "parent_id" => "required_if:has_parent,1",
            //"subject_id" => "required",
            "term_id" => "required",
            "status" => "required|in:active,inactive",
        ], [
            'title.required' => 'The skill title is required.',
            'title.string' => 'The skill title must be a valid text.',
            'title.min' => 'The skill title must be at least 2 characters long.',
            'title.max' => 'The skill title must not exceed 255 characters.',
            'title.regex' => 'The skill title can only contain letters, numbers, spaces, hyphens, underscores, periods, commas, and parentheses.',
            'title.not_regex' => 'The skill title must contain at least one letter or number.',
            'status.in' => 'The status must be either active or inactive.',
        ]);

        if (empty($request->parent_id))
            $request['parent_id'] = 0;

        $skill->update($request->all());

        return redirect()->route('skill.index')->with(['success', 'Skill updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Skill $skill)
    {
        try {
            StudentBehaviourSkillMark::where('skill_id', $skill->id)->delete();
            return $skill->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
