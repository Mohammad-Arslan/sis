<?php

namespace App\Http\Controllers;

use App\Models\ClassStudent;
use App\Models\FeePackage;
use App\Models\Student;
use App\Models\StudentConcession;
use App\Models\StudentFeePackage;
use App\Models\StudentInvoice;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class StudentFeePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = StudentFeePackage::where('student_id', $request->student)->with(['students', 'fee_package.branch', 'fee_concession.fee_concession_type', 'academic_year', 'com_class', 'section'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($row) {
                    $fullName = $row->students->first_name . ' ' . $row->students->middle_name . ' ' . $row->students->last_name;
                    return $fullName;
                })
                ->addColumn('fee_concession', function ($row) {
                    $data = isset($row->fee_concession) ? $row->fee_concession->fee_concession_type->name : '-';
                    return $data;
                })
                ->addColumn('fee_concession_percentage', function ($row) {
                    $data = isset($row->fee_concession) ? $row->fee_concession->concession_percentage : '-';
                    return $data;
                })
                ->addColumn('is_valid', function ($row) {
                    $badge = $row->is_valid == 1 ? '<span class="badge bg-primary">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                    return $badge;
                })
                ->addColumn('action', function ($row) {
                    return view('students.student_fee_package_actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'is_valid', 'fee_concession'])
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
        // dd($request->all());

        $academic_info = ClassStudent::where([
            'student_id' => $request->student_id,
            'is_valid' => 1
        ])->with([
            'students',
            'academic_years',
            'branch_class_sections.com_classes',
            'branch_class_sections.sections'
        ])->first();

        if (!isset($academic_info)) {
            return redirect()->back(302)->with('error', 'Student\'s academic info not found.');
        }

        $request->validate([
            "fee_package_id" => "required",
            // "fee_concession_id" => "required",
        ]);
        $fee_concession_id = StudentConcession::where(['student_id' => $request->student_id, 'is_valid' => 1, 'deleted_at' => null])->first();
        
        $input = [
            "fee_package_id" => $request->fee_package_id,
            "fee_concession_id" => $fee_concession_id->fee_concession_id ?? null,
            "academic_year_id" => $academic_info->academic_years->id,
            "com_class_id" => $academic_info->branch_class_sections->com_classes->id,
            "section_id" => $academic_info->branch_class_sections->sections->id,
            "student_id" => $request->student_id
        ];

        StudentFeePackage::where(['student_id' => $request->student_id, 'is_valid' => 1])->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
        StudentFeePackage::create($input);

        return redirect(route('students.edit', $request->student_id) . '?tab=package')->with('success', 'Student fee package created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentFeePackage  $studentFeePackage
     * @return \Illuminate\Http\Response
     */
    public function show(StudentFeePackage $studentFeePackage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentFeePackage  $studentFeePackage
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentFeePackage $studentFeePackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentFeePackage  $studentFeePackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentFeePackage $studentFeePackage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentFeePackage  $studentFeePackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentFeePackage $studentFeePackage)
    {
        //
    }


    public function attachMonthlyPackageToStudent(Request $request, Student $student)
    {
        $student_current_class = ClassStudent::where(['student_id' => $student->id, 'is_valid' => 1])->with('branch_class_sections')->first();
        $check_student_admission_status = StudentInvoice::where(['student_id' => $student->id, 'invoice_frequency' => 'Admission', 'is_paid' => 1])->whereHas('student_fee_package', function ($query) use ($student_current_class) {
            $query->where(['academic_year_id' => $student_current_class->academic_year_id, 'com_class_id' => $student_current_class->branch_class_sections->class_id, 'section_id' => $student_current_class->branch_class_sections->section_id]);
        })->exists();


        if (!$check_student_admission_status) return redirect()->back(302)->with('error', 'Student\'s admission fees is not paid.');

        $fee_package = FeePackage::where([
            'branch_id' => $student->branch_id,
            'academic_year_id' => $student_current_class->academic_year_id
        ])->whereHas('fee_package_type', function ($query) {
            $query->where(['name' => 'Monthly']);
        })->latest('created_at');

        if (!$fee_package->exists()) return redirect()->back(302)->with('error', 'Cannot enroll the student in this academic year.');

        // dd($student->with('student_fee_package')->get());

        $package_input = [
            "fee_package_id" => $fee_package->first()->id,
            "fee_concession_id" => $request->fee_concession_id,
            "academic_year_id" => $student_current_class->academic_year_id,
            "com_class_id" => $student_current_class->branch_class_sections->class_id,
            "section_id" => $student_current_class->branch_class_sections->section_id,
            "student_id" => $student->id
        ];

        StudentFeePackage::where(['student_id' => $student->id, 'is_valid' => 1])->update(['is_valid' => 0, 'active_till' => Carbon::now()]);
        StudentFeePackage::create($package_input);

        return redirect()->back()->with('success', 'Monthly package applied successfully.');
    }
}
