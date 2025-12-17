<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssessmentLevel;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\Employee;
use App\Models\GeneralBehaviour;
use App\Models\GradingCriteria;
use App\Models\NetworkAssociate;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudentBehaviourSkill;
use App\Models\StudentBehaviourSkillMark;
use App\Models\StudentBehaviourSkillRemark;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StudentBehaviourSkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = array();

            if (isset($request->branch_id)) {
                $query = StudentBehaviourSkill::select('student_behaviour_skills.*', 'student_behaviour_skills.id as student_behaviour_skill_id')->with([
                    'academic_year',
                    'branch',
                    'term',
                    'section',
                    'com_class',
                ]);

                if (isset($request->academic_year_id))
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                        $q->orWhereNull('academic_year_id');
                    });

                if (isset($request->branch_id))
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('student_behaviour_skills.branch_id', $request->branch_id);
                        $q->orWhereNull('student_behaviour_skills.branch_id');
                    });

                if (isset($request->term_id))
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('student_behaviour_skills.term_id', $request->term_id);
                        $q->orWhereNull('student_behaviour_skills.term_id');
                    });

                if (isset($request->class_id)) {
                    $branch_class = BranchClass::find($request->class_id);
                    $class_id = !empty($branch_class) ? $branch_class->class_id : 0;
                    $query = $query->where(function ($q) use ($class_id) {
                        $q->where('class_id', $class_id);
                        $q->orWhereNull('class_id');
                    });
                }

                if (isset($request->section_id)) {
                    $branch_class_section = BranchClassSection::find($request->section_id);
                    $section_id = !empty($branch_class_section) ? $branch_class_section->section_id : 0;
                    $query = $query->where(function ($q) use ($section_id) {
                        $q->where('section_id', $section_id);
                        $q->orWhereNull('section_id');
                    });
                }
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('assessment.skill_behaviour.action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $user = \Auth::user();
        $data['academic_years'] = AcademicYear::all();
        if ($user->hasRole('teacher')) {
            $employee = Employee::where('user_id', $user->id)->with('branch')->first();
            // dd();
            $data['branch'] = $employee->branch->toArray();
        } elseif ($user->hasRole('network_associate')) {
            $employee = NetworkAssociate::where('user_id', $user->id)->with('branches')->first();
            // dd($employee->branches->toArray());
            $data['branches'] = $employee->branches;
        } else
            $data['branches'] = Branch::all();
        $data['terms'] = Term::all();

        return view('assessment.skill_behaviour.index', $data);
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
    // DB::beginTransaction();

    // $branch_class = BranchClass::find($request->class_id);
    // $branch_class_section = BranchClassSection::find($request->section_id);
    // $input['class_id'] = !empty($branch_class) ? $branch_class->class_id : 0;
    // $input['section_id'] = !empty($branch_class_section) ? $branch_class_section->section_id : 0;

    // $skill_behaviour = StudentBehaviourSkill::where([
    //     ['academic_year_id', $request->academic_year_id],
    //     ['branch_id', $request->branch_id],
    //     ['term_id', $request->term_id],
    //     ['class_id', $input['class_id']],
    //     ['section_id', $input['section_id']],
    // ])->with('student_behaviour_skill_remark')->get();
    // if (empty($skill_behaviour)) {
    //     $skill_behaviour = new StudentBehaviourSkill();
    //     $skill_behaviour->academic_year_id = $request->academic_year_id;
    //     $skill_behaviour->branch_id = $request->branch_id;
    //     $skill_behaviour->term_id = $request->term_id;
    //     $skill_behaviour->class_id = $input['class_id'];
    //     $skill_behaviour->section_id = $input['section_id'];
    //     $skill_behaviour->save();
    // } else {
    //     // dd($skill_behaviour[0]->student_behaviour_skill_remark->id);
    //     $skill_behaviour_record = StudentBehaviourSkill::find($skill_behaviour[0]->id);
    //     $input_skill_behaviour['academic_year_id'] = $request->academic_year_id;
    //     $input_skill_behaviour['branch_id'] = $request->branch_id;
    //     $input_skill_behaviour['term_id'] = $request->term_id;
    //     $input_skill_behaviour['class_id'] = $input['class_id'];
    //     $input_skill_behaviour['section_id'] = $input['section_id'];
    //     $skill_behaviour_record->update($input_skill_behaviour);
    // }

    // $skill_behaviour_remarks = null;
    // if (!empty($skill_behaviour[0]->student_behaviour_skill_remark)) {
    //     $skill_behaviour_remarks = StudentBehaviourSkillRemark::where('student_behaviour_skill_id', $skill_behaviour[0]->student_behaviour_skill_remark->id)->get();
    //     $skill_behaviour_remarks_record = StudentBehaviourSkillRemark::find($skill_behaviour_remarks[0]->id);
    //     $input_behaviour['student_behaviour_skill_id'] = $skill_behaviour[0]->id;
    //     $input_behaviour['student_id'] = $request->student_id;
    //     $input_behaviour['teacher_comments'] = $request->teacher_comments;
    //     $input_behaviour['schoolhead_comments'] = $request->schoolhead_comments;
    //     $input_behaviour['is_promoted'] = $request->is_promoted;
    //     $input_behaviour['parent_meeting_attended'] = $request->parent_meeting_attended;
    //     $skill_behaviour_remarks_record->update($input_behaviour);
    // } else {
    //     $skill_behaviour_remarks = $skill_behaviour->student_behaviour_skill_remark->where('student_id', $request->student_id)->get();
    //     $skill_behaviour_remarks = !empty($skill_behaviour_remarks) ? $skill_behaviour_remarks : new StudentBehaviourSkillRemark();
    //     $skill_behaviour_remarks->student_behaviour_skill_id = $skill_behaviour->id;
    //     $skill_behaviour_remarks->student_id = $request->student_id;
    //     $skill_behaviour_remarks->teacher_comments = $request->teacher_comments;
    //     $skill_behaviour_remarks->schoolhead_comments = $request->schoolhead_comments;
    //     $skill_behaviour_remarks->is_promoted = $request->is_promoted;
    //     $skill_behaviour_remarks->parent_meeting_attended = $request->parent_meeting_attended;
    //     $skill_behaviour_remarks->save();
    // }

    // if (!empty($skill_behaviour[0])) {
    //     $skill_behaviour_deletion = $skill_behaviour[0]->student_behaviour_skill_marks();
    //     $skill_b_id = $skill_behaviour[0]->id;
    // } else {
    //     $skill_behaviour_deletion = $skill_behaviour->student_behaviour_skill_marks();
    //     $skill_b_id = $skill_behaviour->id;
    // }

    //     if ($request->submission_type == 'behaviour')
    //         $skill_behaviour_deletion->where('student_id', $request->student_id)->whereNull('skill_id')->delete();
    //     elseif ($request->submission_type == 'skill')
    //         $skill_behaviour_deletion->where('student_id', $request->student_id)->whereNull('general_behaviour_id')->delete();

    //         foreach ($request->skill_behaviours as $skill_behaviour_id => $grade) {
    //             if ($grade) {
    //                     $student_behaviour_mark = new StudentBehaviourSkillMark();
    //                     $student_behaviour_mark->student_behaviour_skill_id = $skill_b_id;

    //                     if ($request->submission_type == 'behaviour') {
    //                         $student_behaviour_mark->general_behaviour_id = $skill_behaviour_id;
    //                     } else {
    //                         $student_behaviour_mark->skill_id = $skill_behaviour_id;
    //                     }

    //                     $student_behaviour_mark->student_id = $request->student_id;
    //                     $student_behaviour_mark->grade = $grade;
    //                     $student_behaviour_mark->save();
    //             }
    //         }



    //     DB::commit();

    //     return response()->json(['success' => 'Data Saved Successfully.']);
    DB::beginTransaction();

    $branch_class = BranchClass::find($request->class_id);
    $branch_class_section = BranchClassSection::find($request->section_id);
    $input['class_id'] = !empty($branch_class) ? $branch_class->class_id : 0;
    $input['section_id'] = !empty($branch_class_section) ? $branch_class_section->section_id : 0;

    $skill_behaviour = StudentBehaviourSkill::where([
        ['academic_year_id', $request->academic_year_id],
        ['branch_id', $request->branch_id],
        ['term_id', $request->term_id],
        ['class_id', $input['class_id']],
        ['section_id', $input['section_id']],
    ])->first();

    if (empty($skill_behaviour))
        $skill_behaviour = new StudentBehaviourSkill();

    $skill_behaviour->academic_year_id = $request->academic_year_id;
    $skill_behaviour->branch_id = $request->branch_id;
    $skill_behaviour->term_id = $request->term_id;
    $skill_behaviour->class_id = $input['class_id'];
    $skill_behaviour->section_id = $input['section_id'];
    $skill_behaviour->save();

    $skill_behaviour_remarks = null;
    if (!empty($skill_behaviour->student_behaviour_skill_remark))
        $skill_behaviour_remarks = $skill_behaviour->student_behaviour_skill_remark->where('student_id', $request->student_id)->first();
    $skill_behaviour_remarks = !empty($skill_behaviour_remarks) ? $skill_behaviour_remarks : new StudentBehaviourSkillRemark();
    $skill_behaviour_remarks->student_behaviour_skill_id = $skill_behaviour->id;
    $skill_behaviour_remarks->student_id = $request->student_id;
    $skill_behaviour_remarks->teacher_comments = $request->teacher_comments;
    $skill_behaviour_remarks->schoolhead_comments = $request->schoolhead_comments;
    $skill_behaviour_remarks->is_promoted = $request->is_promoted;
    $skill_behaviour_remarks->parent_meeting_attended = $request->parent_meeting_attended;
    $skill_behaviour_remarks->save();

    $skill_behaviour_deletion = $skill_behaviour->student_behaviour_skill_marks();
    if ($request->submission_type == 'behaviour')
        $skill_behaviour_deletion->where('student_id', $request->student_id)->whereNull('skill_id')->delete();
    elseif ($request->submission_type == 'skill')
        $skill_behaviour_deletion->where('student_id', $request->student_id)->whereNull('general_behaviour_id')->delete();

    foreach ($request->skill_behaviours as $skill_behaviour_id => $grade) {
        if ($grade) {
            $student_behaviour_mark = new StudentBehaviourSkillMark();
            $student_behaviour_mark->student_behaviour_skill_id = $skill_behaviour->id;

            if ($request->submission_type == 'behaviour')
                $student_behaviour_mark->general_behaviour_id = $skill_behaviour_id;
            else
                $student_behaviour_mark->skill_id = $skill_behaviour_id;

            $student_behaviour_mark->student_id = $request->student_id;
            $student_behaviour_mark->grade = $grade;
            $student_behaviour_mark->save();
        }
    }

    DB::commit();

    return response()->json(['success' => 'Data Saved Successfully.']);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentBehaviourSkill  $studentBehaviourSkill
     * @return \Illuminate\Http\Response
     */
    public function show(StudentBehaviourSkill $studentBehaviourSkill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentBehaviourSkill  $studentBehaviourSkill
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentBehaviourSkill $studentBehaviourSkill)
    {
        $studentBehaviourSkill->load('com_class');
        // dd($studentBehaviourSkill->toArray());
        $data['academic_years'] = AcademicYear::all();
        $data['branches'] = Branch::all();
        $data['terms'] = Term::all();
        $data['branch_classes'] = BranchClass::with('com_classes')->where('branch_id', $studentBehaviourSkill['branch_id'])->with(['com_classes'])->get();
        $data['branch_class_sections'] = BranchClassSection::with('sections')->where(['branch_id' => $studentBehaviourSkill['branch_id'], 'class_id' => $studentBehaviourSkill['class_id']])->with(['sections'])->get();
        $data['studentBehaviourSkill'] = $studentBehaviourSkill;
        $data['students'] = Student::whereHas('student_behaviour_skill_marks', function ($q) use ($studentBehaviourSkill) {
            $q->where('student_behaviour_skill_id', $studentBehaviourSkill['id']);
        })->orWhereHas('student_behaviour_skill_remarks', function ($q) use ($studentBehaviourSkill) {
            $q->where('student_behaviour_skill_id', $studentBehaviourSkill['id']);
        })->get();
        // dd($data['students']->toArray());
        return view('assessment.skill_behaviour.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentBehaviourSkill  $studentBehaviourSkill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentBehaviourSkill $studentBehaviourSkill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentBehaviourSkill  $studentBehaviourSkill
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentBehaviourSkill $studentBehaviourSkill)
    {
        //
    }

    public function skill_modal(Request $request, $student_id)
    {
        // dd($student_id);
        $branch_class = BranchClass::find($request->class_id);
        $branch_class_section = BranchClassSection::find($request->section_id);
        $input['class_id'] = !empty($branch_class) ? $branch_class->class_id : 0;
        $input['section_id'] = !empty($branch_class_section) ? $branch_class_section->section_id : 0;

        $data['grading_keys'] = GradingCriteria::whereHas('classes', function ($q) use ($input) {
            $q->where('com_classes.id', $input['class_id']);
        })->get();

        // dd($input['section_id']);
        $data['skills'] = Skill::with(['subject'])->where([['status', 'active'], ['class_id', $input['class_id']], ['term_id', $request->term_id], ['parent_id', 0]])->orderBy('sort_no')->get();
        $data['student_id'] = $student_id;

        $data['student_skill'] = StudentBehaviourSkill::with([
            'student_behaviour_skill_marks' => function ($q) use ($student_id) {
                $q->where('student_id', $student_id);
                $q->whereNull('general_behaviour_id');
            }, 'student_behaviour_skill_remark' => function ($q) use ($student_id) {
                $q->where('student_id', $student_id);
            },
        ])->where([
            ['academic_year_id', $request->academic_year_id],
            ['branch_id', $request->branch_id],
            ['term_id', $request->term_id],
            ['class_id', $input['class_id']],
            ['section_id', $input['section_id']],
        ])->first();

        if (!empty($data['student_skill']['student_behaviour_skill_marks']))
            $data['student_skill']['student_behaviour_skill_marks'] = $data['student_skill']['student_behaviour_skill_marks']->keyBy('skill_id');

        $student_skills['html'] = view('assessment.skill_behaviour.skills', $data)->render();
        return response()->json($student_skills);
    }

    public function behaviour_modal(Request $request, $student_id)
    {

        $branch_class = BranchClass::find($request->class_id);
        $branch_class_section = BranchClassSection::find($request->section_id);
        $input['class_id'] = !empty($branch_class) ? $branch_class->class_id : 0;
        $input['section_id'] = !empty($branch_class_section) ? $branch_class_section->section_id : 0;

        $data['grading_keys'] = GradingCriteria::whereHas('classes', function ($q) use ($input) {
            $q->where('com_classes.id', $input['class_id']);
        })->get();
        $data['general_behaviours'] = GeneralBehaviour::with('children')->where('parent_id', 0)->get();
        $data['student_id'] = $student_id;
        $data['student_behaviour'] = StudentBehaviourSkill::with(['student_behaviour_skill_marks' => function ($q) use ($student_id) {
            $q->where('student_id', $student_id);
            $q->whereNull('skill_id');
        }, 'student_behaviour_skill_remark' => function ($q) use ($student_id) {
            $q->where('student_id', $student_id);
        }])->where([
            ['academic_year_id', $request->academic_year_id],
            ['branch_id', $request->branch_id],
            ['term_id', $request->term_id],
            ['class_id', $input['class_id']],
            ['section_id', $input['section_id']],
        ])->first();

        if (!empty($data['student_behaviour']['student_behaviour_skill_marks']))
            $data['student_behaviour']['student_behaviour_skill_marks'] = $data['student_behaviour']['student_behaviour_skill_marks']->keyBy('general_behaviour_id');

        $student_behaviours['html'] = view('assessment.skill_behaviour.behaviours', $data)->render();
        return response()->json($student_behaviours);
    }
}
