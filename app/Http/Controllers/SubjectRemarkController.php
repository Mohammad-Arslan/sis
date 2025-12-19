<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssessmentLevel;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\Employee;
use App\Models\NetworkAssociate;
use App\Models\StudentSubjectRemark;
use App\Models\Subject;
use App\Models\SubjectRemark;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\QueryException;

class SubjectRemarkController extends Controller
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
                $query = SubjectRemark::with([
                    'academic_year',
                    'branch',
                    'term',
                    'section',
                    'com_class',
                    'subject',
                    'created_by'
                ])->select('subject_remarks.*', 'subject_remarks.id as subject_remark_id');

                if (isset($request->academic_year_id)) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('academic_year_id', $request->academic_year_id);
                        $q->orWhereNull('academic_year_id');
                    });
                }

                if (isset($request->branch_id)) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('subject_remarks.branch_id', $request->branch_id);
                        $q->orWhereNull('subject_remarks.branch_id');
                    });
                }

                if (isset($request->class_id)) {
                    $branch_class = BranchClass::find($request->class_id);
                    $class_id = ! empty($branch_class) ? $branch_class->class_id : 0;
                    $query = $query->where(function ($q) use ($class_id) {
                        $q->where('class_id', $class_id);
                        $q->orWhereNull('class_id');
                    });
                }

                if (isset($request->section_id)) {
                    $branch_class_section = BranchClassSection::find($request->section_id);
                    $section_id = ! empty($branch_class_section) ? $branch_class_section->section_id : 0;
                    $query = $query->where(function ($q) use ($section_id) {
                        $q->where('section_id', $section_id);
                        $q->orWhereNull('section_id');
                    });
                }

                if (isset($request->subject_id)) {
                    $query = $query->where(function ($q) use ($request) {
                        $q->where('subject_id', $request->subject_id);
                        $q->orWhereNull('subject_id');
                    });
                }

                /*if (isset($request->term_id))
                    $query = $query->where(function ($q) use ($request){
                        $q->where('term_id' , $request->term_id);
                        $q->orWhereNull('term_id');
                    });*/
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('assessment.subject_remarks.action', ['row' => $row]);
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
        } else {
            $data['branches'] = Branch::all();
        }
        $data['terms'] = Term::all();
        $data['assessment_level_one'] = AssessmentLevel::where('parent_id', 0)->get();


        return view('assessment.subject_remarks.index', $data);
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
        $input['class_id'] = ! empty($branch_class) ? $branch_class->class_id : 0;
        $input['section_id'] = ! empty($branch_class_section) ? $branch_class_section->section_id : 0;
        $input['remarks_date'] = parse_date($request->remarks_date, 'Y-m-d');
        $input['created_by_id'] = Auth::user()->id;
        $subject_marks_setup = SubjectRemark::where([
            ['academic_year_id', $input['academic_year_id']],
            ['term_id', $input['term_id']],
            ['branch_id', $input['branch_id']],
            ['class_id', $input['class_id']],
            ['subject_id', $input['subject_id']],
            ['section_id', $input['section_id']],
        ])->first();
        // dd($subject_marks_setup->toArray());
        if (! empty($subject_marks_setup)) {
            return redirect()->back()->with('error', 'Record already exists');
        }
        DB::beginTransaction();
        $subjectRemark = SubjectRemark::create($input);
        foreach ($request->student_remarks as $student_id => $obtained_marks) {
            $student_subject_remarks['subject_remark_id'] = $subjectRemark->id;
            $student_subject_remarks['student_id'] = $student_id;
            $student_subject_remarks['remarks'] = $request->student_remarks[$student_id];
            StudentSubjectRemark::create($student_subject_remarks);
        }
        DB::commit();

        return redirect()->back()->with('success', 'Subject Remarks created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubjectRemark  $subjectRemark
     * @return \Illuminate\Http\Response
     */
    public function show(SubjectRemark $subjectRemark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubjectRemark  $subjectRemark
     * @return \Illuminate\Http\Response
     */
    public function edit(SubjectRemark $subjectRemark)
    {
        $subjectRemark->load([
            'student_subject_remarks.student',
            'student_subject_remarks.subject_remark',
        ]);
        // dd($subjectRemark->toArray());
        $data['subjectRemark'] = $subjectRemark;
        $data['academic_years'] = AcademicYear::all();
        $data['branches'] = Branch::all();
        $data['subjects'] = getClassSubjects($subjectRemark['class_id'], $subjectRemark['branch_id']);
        $data['branch_classes'] = BranchClass::with('com_classes')->where('branch_id', $subjectRemark['branch_id'])->with(['com_classes'])->get();
        $data['branch_class_sections'] = BranchClassSection::with('sections')->where(['branch_id' => $subjectRemark['branch_id'], 'class_id' => $subjectRemark['class_id']])->with(['sections'])->get();
        $data['terms'] = Term::all();
        // dd($subjectRemark->toArray());
        return view('assessment.subject_remarks.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubjectRemark  $subjectRemark
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubjectRemark $subjectRemark)
    {
        $input = $request->all();

        DB::beginTransaction();

        $input['remarks_date'] = parse_date($request->remarks_date, 'Y-m-d');
        $subjectRemark->update($input);

        foreach ($request->student_remarks as $student_id => $obtained_marks) {
            $student_assessment_marks['remarks'] = $request->student_remarks[$student_id];
            StudentSubjectRemark::where([['subject_remark_id', $subjectRemark->id], ['student_id', $student_id]])->update($student_assessment_marks);
        }
        DB::commit();

        return redirect()->route('subject-remarks.index')->with(['success', 'Subject Remarks updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubjectRemark  $subjectRemark
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubjectRemark $subjectRemark)
    {
        try {
            $subjectRemark->student_subject_remarks()->delete();
            $subjectRemark->delete();

            return true;
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
