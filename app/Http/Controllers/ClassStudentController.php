<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\BranchAcademicYear;
use App\Models\Student;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\ClassStudentSubject;
use App\Models\ClassSubject;
use App\Models\FeePackage;
use App\Models\StudentFeePackage;
use App\Models\StudentLedger;
use App\Models\StudentLedgerInvoice;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class ClassStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ClassStudent::where(
                'student_id',
                $request->student
            )->with([
                'students',
                'academic_years',
                'branch_class_sections.com_classes',
                'branch_class_sections.sections'
            ])->get();

            // dd($data->toArray());
            return DataTables::of($data)
                ->addColumn('is_valid', function ($row) {
                    $badge = $row->is_valid == 1 ? '<span class="badge bg-primary">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                    return $badge;
                })
                ->addColumn('action', function ($row) {
                    return view('students.student_fee_package_actions', ['row' => $row]);
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
        $request->validate([
            'academic_year_id' => 'required',
            'class_id' => 'required',
            'section_id' => 'required',
            'student_id' => 'required'
        ]);
        $student = Student::where('id', $request->student_id)->first();
        $academic_year = BranchAcademicYear::where([
            'branch_id' => $student->branch_id,
            'academic_year_id' => $request->academic_year_id
        ])->first();

        $branch_class_section = BranchClassSection::where([
            'branch_id' => $student->branch_id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id
        ])->first();

        // dd($branch_class_section->toArray());

        $class_student = ClassStudent::where([
            'student_id' => $request->student_id,
            'is_valid' => 1,
            'academic_year_id' => $request->academic_year_id,
            'branch_class_section_id' => $branch_class_section->id
        ])->exists();

        // if ($class_student) return redirect()->back(302)->with('error', 'Record already exists hey.');


        ClassStudent::where([
            'student_id' => $request->student_id,
            'is_valid' => 1
        ])->update([
            'is_valid' => 0,
            'active_till' => Carbon::now()
        ]);

        $input = [
            'academic_year_id' => $request->academic_year_id,
            'branch_class_section_id' => $branch_class_section->id,
            'student_id' => $request->student_id
        ];

        Validator::make($input, [
            "academic_year_id" => "required",
            "branch_class_section_id" => "required",
            "student_id" => "required"
        ]);

        $class_student = ClassStudent::create($input);

        // Class Subjects
        // $class_subjects = ClassSubject::where(['class_id' => $request->class_id, 'branch_id' => $student->branch_id])->orWhere(['class_id' => $request->class_id, 'branch_id' => null])->get();
        $class_subjects = ClassSubject::where('class_id', $request->class_id)->where(function ($query) use ($student) {
            $query->where('branch_id', $student->branch_id);
            $query->orWhere('branch_id', null);
        })->get()->pluck('subject_id');

        foreach ($class_subjects as $subject) {
            ClassStudentSubject::create([
                'class_student_id' => $class_student->id,
                'subject_id' => $subject,
                'is_valid' => 1
            ]);
        }

        // Class Subjects End


        $student_ledger =  StudentLedger::create([
            'student_id' => $request->student_id,
            'class_student_id' => $class_student->id
        ]);

        $academic_start_date = Carbon::parse($academic_year->start_date)->format('m');
        StudentLedgerInvoice::create_empty_record($student_ledger['id'], $academic_start_date);

        // StudentFeePackage::where(['student_id' => $request->student_id, 'is_valid' => 1])->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
        // StudentFeePackage::create($package_input);

        return redirect(route('students.edit', $request->student_id) . '?tab=academic')->with('success', 'Academic info added successfully.');
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get Branch Class Section Students.
     */
    public function class_section_students($branch_class_section_id)
    {
        $data['class_students'] = ClassStudent::with(['students:id,first_name,middle_name,last_name,registration_number'])
            ->where([['branch_class_section_id', $branch_class_section_id], ['is_valid', 1]])
            ->get();

        $data['subjects'] = Subject::all();

        return view('branches.classes.class_student_subjects', ['data' => $data]);
    }
}
