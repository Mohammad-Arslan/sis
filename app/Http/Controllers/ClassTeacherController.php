<?php

namespace App\Http\Controllers;

use App\Models\BranchAcademicYear;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\ClassTeacher;
use App\Models\Employee;
use App\Models\Subject;
use App\Models\TeacherType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassTeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ClassTeacher::where('employee_id', $request->employee_id)->with([
                'academic_year',
                'branch_class_section.com_classes',
                'branch_class_section.sections',
                'employee',
                'teacher_type',
                'subject'
            ])->get();
            // dd($data->toArray());
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_valid', function ($row) {
                    $badge = $row->is_valid == 1 ? '<span class="badge bg-primary">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                    return $badge;
                })
                ->addColumn('action', function ($row) {
                    return view('employees.teacher_classes_actions', ['row' => $row]);
                })
                ->rawColumns(['is_valid', 'action'])
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
        if (empty($request->employee_id))
            return redirect()->back()->with('error', 'No Teacher has been selected.');

        else if (!isset($request->subject_id))
            return redirect()->back()->with('error', 'No Subjects has been selected.');

        if (isset($request->is_class_incharge)) {
            $teacher_type = TeacherType::where('abbreviation', 'class')->first();
            $class_has_incharge = ClassTeacher::where([['branch_class_section_id', $request->branch_class_section_id], ['teacher_type_id', $teacher_type->id], ['is_valid', 1]])->first();

            //if class already has an incharge then teacher type would be of subject.
            //first remove precious class incharge then new class incharge would be assigned.
            if (!empty($class_has_incharge)) {
                $teacher_type = TeacherType::where('abbreviation', 'subject')->first();
            } else if (!isset($request->subject_id) && !empty($request->employee_id)) //if class has no incharge and no subjects has been selected then make this employee as class incharge
            {
                ClassTeacher::create([
                    'academic_year_id' => $request->academic_year_id,
                    'branch_class_section_id' => $request->branch_class_section_id,
                    'employee_id' => $request->employee_id,
                    'teacher_type_id' => $teacher_type->id,
                ]);
            }
        } else {
            $teacher_type = TeacherType::where('abbreviation', 'subject')->first();
        }

        if (isset($request->subject_id) && isset($request->employee_id)) {
            foreach ($request->subject_id as $subject_id) {
                $subject = ClassTeacher::where([['employee_id',$request->employee_id],['branch_class_section_id', $request->branch_class_section_id], ['subject_id', $subject_id], ['is_valid', 1]])->count();
                if ($subject < 1)
                    ClassTeacher::create([
                        'academic_year_id' => $request->academic_year_id,
                        'branch_class_section_id' => $request->branch_class_section_id,
                        'employee_id' => $request->employee_id,
                        'teacher_type_id' => $teacher_type->id,
                        'subject_id' => $subject_id,
                    ]);
            }
        }

        return redirect()->back()->with('success', 'Subjects added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassTeacher $classTeacher)
    {
        try {
            return $classTeacher->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function editTeacherType(Request $request)
    {
        $class_teacher = ClassTeacher::where('id', $request->class_teacher_id)->first();

        $teacher_types = TeacherType::all();
        return view('employees.teacher_classes_type_modal', compact('teacher_types', 'class_teacher'));
    }

    public function updateTeacherType(Request $request)
    {
        $class_teacher = ClassTeacher::where('id', $request->class_teacher_id)->first();

        $alreadyExists = ClassTeacher::where(['id' => $request->class_teacher_id, 'teacher_type_id' => $request->teacher_type_id])->exists();
        if ($alreadyExists) {
            return redirect()->back(302)->with('error', 'Please update the type.');
        }

        $inchargeExist = ClassTeacher::where(['branch_class_section_id' => $class_teacher->branch_class_section_id, 'teacher_type_id' => 1, 'is_valid' => 1])->exists();
        if ($inchargeExist && $request->teacher_type_id == 1) {
            return redirect()->back(302)->with('error', 'Class teacher already exists for this class.');
        }

        $class_teacher->update(['teacher_type_id' => $request->teacher_type_id]);

        return redirect()->back()->with('success', 'Teacher type updated successfully.');
    }
    /**
     * Get All Teachers and Subjects.
     */
    public function class_teacher_subjects($branch_class_section_id)
    {
        $data['teacher_type'] = TeacherType::where('abbreviation', 'class')->first();
        $data['branch_class_section_id'] = BranchClassSection::find($branch_class_section_id);
        $data['subjects'] = Subject::all();
        $data['employees'] = Employee::with(['user'])
            ->whereHas('designation', function ($query) {
                $query->whereIn('designation_name', ['Teacher','Subject Coordinator']);
            })
            ->where('branch_id', $data['branch_class_section_id']['branch_id'])
            ->get();
        $data['branch_academic_years'] = BranchAcademicYear::where('branch_id', $data['branch_class_section_id']['branch_id'])->with('academic_year')->get();
        $data['class_has_incharge'] = ClassTeacher::where([['branch_class_section_id', $branch_class_section_id], ['teacher_type_id', $data['teacher_type']['id'], ['is_valid', 1]]])->first();

        return view('branches.classes.class_teacher_subjects', ['data' => $data]);
    }

    /**
     * Get All Teachers of Section.
     */
    public function class_section_teachers(Request $request)
    {
        if ($request->ajax()) {

            $data = ClassTeacher::where('branch_class_section_id', $request->branch_class_section_id)
                ->with([
                    'subject',
                    'teacher_type',
                    'employee.user',
                    'academic_year',
                    'branch_class_section.com_classes',
                    'branch_class_section.sections'
                ])->get();
            //dd($data->toArray());
            return DataTables::of($data)
                ->addColumn('employee_full_name', function ($data) {
                    if(isset($data['employee']['user']['first_name'])) {
                        return $data['employee']['user']['first_name'] . ' ' . $data['employee']['user']['middle_name'] . ' ' . $data['employee']['user']['last_name'];
                    }
                    else{
                        return '';
                    }
                })
                ->addColumn('subject_name', function ($data) {
                    return $data['subject'] ? $data['subject']['subject_name'] : '';
                })
                ->addColumn('is_valid', function ($row) {
                    $badge = $row->is_valid == 1 ? '<span class="badge badge-outline-success">Active</span>' : '<span class="badge badge-outline-danger">Inactive</span>';
                    return $badge;
                })
                ->addColumn('action', function ($row) {
                    return view('employees.teacher_classes_actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'is_valid'])
                ->make(true);
        }
    }
}
