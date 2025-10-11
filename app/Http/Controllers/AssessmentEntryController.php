<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssessmentEntry;
use App\Models\AssessmentLevel;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\Employee;
use App\Models\NetworkAssociate;
use App\Models\StudentAssessmentMark;
use App\Models\Subject;
use App\Models\SubjectMarksSetup;
use App\Models\Term;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AssessmentEntryController extends Controller
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

            if (isset($request->section_id)) {
                $query = AssessmentEntry::with([
                    'assessment_level_one',
                    'assessment_level_two',
                    'assessment_level_three',
                    'academic_year',
                    'branch',
                    'term',
                    'section',
                    'com_class',
                    'subject',
                ])->select('assessment_entries.*', 'assessment_entries.id as assessment_entry_id');

                if (isset($request->academic_year_id))
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                        $q->orWhereNull('academic_year_id');
                    });

                if (isset($request->branch_id))
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('assessment_entries.branch_id', $request->branch_id);
                        $q->orWhereNull('assessment_entries.branch_id');
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

                if (isset($request->subject_id))
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('subject_id', $request->subject_id);
                        $q->orWhereNull('subject_id');
                    });

                if (isset($request->term_id))
                    $query = $query->where(function ($q) use ($request){
                        $q->where('term_id' , $request->term_id);
                        $q->orWhereNull('term_id');
                    });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('level_three_name', function ($row) {
                    return isset($row['assessment_level_three']) ? $row['assessment_level_three']['name'] : '';
                })
                ->addColumn('action', function ($row) {
                    return view('assessment.assessment_entry.action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data['academic_years'] = AcademicYear::all();
        $user = \Auth::user();
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
        $data['assessment_level_one'] = AssessmentLevel::where('parent_id', 0)->get();


        return view('assessment.assessment_entry.index', $data);
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
        $input = $request->all();
        // dd($request->all());

        $branch_class = BranchClass::find($request->class_id);
        $branch_class_section = BranchClassSection::find($request->section_id);
        $input['class_id'] = !empty($branch_class) ? $branch_class->class_id : 0;
        $input['section_id'] = !empty($branch_class_section) ? $branch_class_section->section_id : 0;
        $input['assessment_date'] = parse_date($request->assessment_date, 'Y-m-d');

        $subject = Subject::findOrFail($input['subject_id']);
        if ($subject->subject_type == 'Major') {
            $subject_marks_setup = SubjectMarksSetup::where([
                ['academic_year_id', $input['academic_year_id']],
                ['term_id', $input['term_id']],
                ['branch_id', $input['branch_id']],
                ['class_id', $input['class_id']],
                ['subject_id', $input['subject_id']],
                ['assessment_level_one_id', $input['assessment_level_one_id']],
                ['assessment_level_two_id', $input['assessment_level_two_id']],
                ['assessment_level_three_id', $input['assessment_level_three_id']],
            ])->first();
            if (empty($subject_marks_setup)) {
                return redirect()->back()->with('error', 'Marks weightage not defined against these assessment levels of subject.');
            }
            $input['assessment_weightage'] = $subject_marks_setup->marks;
            // $existing_asmt_entry = AssessmentEntry::where([
            //     ['academic_year_id', $input['academic_year_id']],
            //     ['term_id', $input['term_id']],
            //     ['branch_id', $input['branch_id']],
            //     ['class_id', $input['class_id']],
            //     ['section_id', $input['section_id']],
            //     ['subject_id', $input['subject_id']],
            //     ['assessment_level_one_id', $input['assessment_level_one_id']],
            //     ['assessment_level_two_id', $input['assessment_level_two_id']],
            //     ['assessment_level_three_id', $input['assessment_level_three_id']],
            // ])->first();
            // if (!empty($existing_asmt_entry)) {
            //     return redirect()->back()->with('error', 'Assessment Entry already exists');
            // }
        } else {
            $input['assessment_weightage'] = 0;
        }

        DB::beginTransaction();
        $assessment_entry = AssessmentEntry::create($input);
        if ($subject->subject_type == 'Major') {
            foreach ($request->student_marks as $student_id => $obtained_marks) {
                $student_assessment_marks['assessment_entry_id'] = $assessment_entry->id;
                $student_assessment_marks['student_id'] = $student_id;
                $student_assessment_marks['obtained_marks_grades'] = $obtained_marks;
                $student_assessment_marks['out_of'] = $assessment_entry->grade_marks;
                $student_assessment_marks['remarks'] = $request->student_remarks[$student_id];
                StudentAssessmentMark::create($student_assessment_marks);
            }
        } else {
            foreach ($request->student_marks as $student_id => $obtained_marks) {
                $student_assessment_marks['assessment_entry_id'] = $assessment_entry->id;
                $student_assessment_marks['student_id'] = $student_id;
                $student_assessment_marks['overall_grade'] = $obtained_marks;
                // $student_assessment_marks['out_of'] = $assessment_entry->grade_marks;
                $student_assessment_marks['remarks'] = $request->student_remarks[$student_id];
                StudentAssessmentMark::create($student_assessment_marks);
            }
        }

        DB::commit();

        return redirect()->back()->with('success', 'Assessment Entry created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AssessmentEntry  $assessmentEntry
     * @return \Illuminate\Http\Response
     */
    public function show(AssessmentEntry $assessmentEntry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AssessmentEntry  $assessmentEntry
     * @return \Illuminate\Http\Response
     */
    public function edit(AssessmentEntry $assessmentEntry)
    {
        $assessmentEntry->load([
            'student_assessment_marks.student',
            'student_assessment_marks.assessment_entry',
        ]);
        $data['assessmentEntry'] = $assessmentEntry;
        $data['subjectType'] = Subject::where('id', $assessmentEntry->subject_id)->pluck('subject_type')->first();

        $data['academic_years'] = AcademicYear::all();
        $data['branches'] = Branch::all();
        $data['subjects'] = getClassSubjects($assessmentEntry['class_id'], $assessmentEntry['branch_id']);
        $data['branch_classes'] = BranchClass::with('com_classes')->where('branch_id', $assessmentEntry['branch_id'])->with(['com_classes'])->get();
        $data['branch_class_sections'] = BranchClassSection::with('sections')->where(['branch_id' => $assessmentEntry['branch_id'], 'class_id' => $assessmentEntry['class_id']])->with(['sections'])->get();
        $data['terms'] = Term::all();
        $data['assessment_level_one'] = AssessmentLevel::where('parent_id', 0)->get();
        $data['assessment_level_two'] = AssessmentLevel::where('parent_id', $assessmentEntry->assessment_level_one_id)->get();
        $data['assessment_level_three'] = AssessmentLevel::where('parent_id', $assessmentEntry->assessment_level_two_id)->get();

        return view('assessment.assessment_entry.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AssessmentEntry  $assessmentEntry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssessmentEntry $assessmentEntry)
    {
        $input = $request->all();

        DB::beginTransaction();

        $input['assessment_date'] = parse_date($request->assessment_date, 'Y-m-d');
        $assessmentEntry->update($input);

        foreach ($request->student_marks as $student_id => $obtained_marks) {
            if ($request->overall_grade || $request->overall_grade == 1) {
                $student_assessment_marks['overall_grade'] = $obtained_marks;
            } else {
                $student_assessment_marks['obtained_marks_grades'] = $obtained_marks;
            }
            $student_assessment_marks['out_of'] = $assessmentEntry->grade_marks;
            $student_assessment_marks['remarks'] = $request->student_remarks[$student_id];
            StudentAssessmentMark::where([['assessment_entry_id', $assessmentEntry->id], ['student_id', $student_id]])->update($student_assessment_marks);
        }
        DB::commit();

        return redirect()->route('assessment-entry.index')->with(['success', 'Assessment Entry updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AssessmentEntry  $assessmentEntry
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentEntry $assessmentEntry)
    {
        try {

            $assessmentEntry->student_assessment_marks()->delete();
            $assessmentEntry->delete();

            return true;
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
