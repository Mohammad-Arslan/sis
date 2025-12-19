<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssessmentLevel;
use App\Models\Branch;
use App\Models\BranchClass;
use App\Models\SubjectMarksSetup;
use App\Models\Term;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubjectMarksSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = SubjectMarksSetup::with([
                'assessment_level_one',
                'assessment_level_two',
                'assessment_level_three',
                'academic_year',
                'branch',
                'term',
                'com_class',
                'subject',
            ]);

            if (isset($request->academic_year_id)) {
                $query = $query->where(function ($q) use ($request) {
                    $q->where('academic_year_id', $request->academic_year_id);
                    $q->orWhereNull('academic_year_id');
                });
            }

            if (isset($request->branch_id)) {
                $query = $query->where(function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id);
                    $q->orWhereNull('branch_id');
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

            if (isset($request->subject_id)) {
                $query = $query->where(function ($q) use ($request) {
                    $q->where('subject_id', $request->subject_id);
                    $q->orWhereNull('subject_id');
                });
            }

            if (isset($request->term_id)) {
                $query = $query->where(function ($q) use ($request) {
                    $q->where('term_id', $request->term_id);
                    $q->orWhereNull('term_id');
                });
            }
            $data = $query;

            $limit = $request->input('length');
            $start = $request->input('start');
            $totalData = $data->count();
            $totalFiltered = $totalData;

            $rows = $data->offset($start)->limit($limit)->get();
            $return_data = [];
            // $totalData = $rows->count();
            // To create data
            foreach ($rows as $row) {
                $row['level_three_name'] = isset($row['assessment_level_three']) ? $row['assessment_level_three']['name'] : '';
                $row['action'] = view('settings.subject_marks_setup.action', ['row' => $row])->render();
                $return_data[] = $row;
            }

            // To return data
            $data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $return_data
            );

            return $data;
            // return DataTables::of($query)
            //     ->addIndexColumn()
            //     ->addColumn('level_three_name', function ($row) {
            //         return isset($row['assessment_level_three']) ? $row['assessment_level_three']['name'] : '';
            //     })
            //     ->addColumn('action', function ($row) {
            //         return view('settings.subject_marks_setup.action', ['row' => $row]);
            //     })
            //     ->rawColumns(['action'])
            //     ->make(true);
        }

        $data['academic_years'] = AcademicYear::all();
        $data['branches'] = Branch::all();
        $data['terms'] = Term::all();
        $data['assessment_level_one'] = AssessmentLevel::where('parent_id', 0)->get();


        return view('settings.subject_marks_setup.index', $data);
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

        $branch_class = BranchClass::find($request->class_id);
        $input['class_id'] = ! empty($branch_class) ? $branch_class->class_id : 0;

        SubjectMarksSetup::create($input);

        return redirect()->back()->with(['success', 'Subject Mark Setup created successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubjectMarksSetup  $subjectMarksSetup
     * @return \Illuminate\Http\Response
     */
    public function show(SubjectMarksSetup $subjectMarksSetup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SubjectMarksSetup  $subjectMarksSetup
     * @return \Illuminate\Http\Response
     */
    public function edit(SubjectMarksSetup $subjectMarksSetup)
    {
        $data['subjectMarksSetup'] = $subjectMarksSetup;
        $data['academic_years'] = AcademicYear::all();
        $data['branches'] = Branch::all();
        $data['subjects'] = getClassSubjects($subjectMarksSetup['class_id'], $subjectMarksSetup['branch_id']);
        $data['branch_classes'] = BranchClass::with('com_classes')->where('branch_id', $subjectMarksSetup['branch_id'])->with(['com_classes'])->get();
        $data['terms'] = Term::all();
        $data['assessment_level_one'] = AssessmentLevel::where('parent_id', 0)->get();
        $data['assessment_level_two'] = AssessmentLevel::where('parent_id', $subjectMarksSetup->assessment_level_one_id)->get();
        $data['assessment_level_three'] = AssessmentLevel::where('parent_id', $subjectMarksSetup->assessment_level_two_id)->get();

        return view('settings.subject_marks_setup.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubjectMarksSetup  $subjectMarksSetup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubjectMarksSetup $subjectMarksSetup)
    {
        $input = $request->all();

        $branch_class = BranchClass::find($request->class_id);
        $input['class_id'] = ! empty($branch_class) ? $branch_class->class_id : 0;

        $subjectMarksSetup->update($input);

        return redirect()->route('subject-marks-setup.index')->with(['success', 'Subject Mark Setup updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubjectMarksSetup  $subjectMarksSetup
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubjectMarksSetup $subjectMarksSetup)
    {
        //
    }
}
