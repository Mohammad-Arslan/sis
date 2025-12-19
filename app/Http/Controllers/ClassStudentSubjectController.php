<?php

namespace App\Http\Controllers;

use App\Models\ClassStudent;
use App\Models\ClassStudentSubject;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassStudentSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ClassStudentSubject::whereHas('class_student', function ($query) use ($request) {
                $query->where('student_id', $request->student);
            })
            ->with([
                'subject',
                'class_student.students',
                'class_student.academic_years',
                'class_student.branch_class_sections.com_classes',
                'class_student.branch_class_sections.sections'
            ])->get();

            return DataTables::of($data)
                ->addColumn('is_valid', function ($row) {
                    $badge = $row->is_valid == 1 ? '<span class="badge badge-outline-success">Active</span>' : '<span class="badge badge-outline-danger">Inactive</span>';
                    return $badge;
                })
                ->addColumn('action', function ($row) {
                    return view('students.subject_actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'is_valid'])
                ->make(true);
        }
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
        $request_data = $request->only(['student_id','subject_id']);

        if (! isset($request_data['student_id'])) {
            return redirect()->back()->with('error', 'No Students has been selected.');
        } else if (! isset($request_data['subject_id'])) {
            return redirect()->back()->with('error', 'No Subjects has been selected.');
        }

        $student_ids = ! empty($request_data['student_id']) ? $request_data['student_id'] : array();
        $class_students = ClassStudent::whereIn('student_id', $student_ids)->where([['branch_class_section_id',$request->branch_class_section_id],['is_valid',1]])->get();

        foreach ($class_students as $class_student) {
            if (isset($request_data['subject_id'])) {
                foreach ($request_data['subject_id'] as $subject_id) {
                    $subject = $class_student->class_student_subjects()->where([['subject_id',$subject_id],['is_valid',1]])->count();
                    if ($subject < 1) {
                        $class_student->class_student_subjects()->create(['subject_id' => $subject_id]);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Subjects added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassStudentSubject  $classStudentSubject
     * @return \Illuminate\Http\Response
     */
    public function show(ClassStudentSubject $classStudentSubject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassStudentSubject  $classStudentSubject
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassStudentSubject $classStudentSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassStudentSubject  $classStudentSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassStudentSubject $classStudentSubject)
    {
        try {
            $classStudentSubject->update([
                'is_valid' => 0,
                'active_till' => Carbon::now()
            ]);

            return true;
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassStudentSubject  $classStudentSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassStudentSubject $classStudentSubject)
    {
        //
    }
}
