<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AttendanceStatus;
use App\Models\Branch;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\ComClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StudentAttendanceController extends Controller
{
    /**
     * get Students of a section course of an academic year.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStudents($branch_class_section_id, $subject_id = 0, $date = 0)
    {
        // $class_students = ClassStudent::with(['students:id,first_name,middle_name,last_name,registration_number'])
        // ->where([['branch_class_section_id',$branch_class_section_id],['is_valid',1]])
        // ->whereHas('class_student_subjects',function ($query) use ($subject_id){
        //     $query->where('subject_id',$subject_id);
        // })
        // ->get();

        $date = $date ? Carbon::parse($date)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $data['date'] = $date;

        $class_students = ClassStudent::with([
            'students',
            'student_attendance' => function ($query) use ($branch_class_section_id, $subject_id, $date) {
                $query->where([
                    'branch_class_section_id' => $branch_class_section_id,
                    'subject_id' => $subject_id
                ]);
                $query->whereDate('attendance_date', $date);
            }
        ])->where([['branch_class_section_id', $branch_class_section_id], ['is_valid', 1]])
            ->whereHas('students', function ($query) use ($subject_id) {
                $query->whereNull('deleted_at');
                $query->where('status', 'on_roll');
            });
        if ($subject_id) {
            $class_students->whereHas('class_student_subjects', function ($query) use ($subject_id) {
                $query->where('subject_id', $subject_id);
            });
        }
        $class_students = $class_students->get();

        // dd($class_students->toArray());

        $data['class_students'] = $class_students;
        $data['attendance_statuses'] = AttendanceStatus::all();
        $data['subject_id'] = $subject_id;

        $data['student_attendances'] = StudentAttendance::where([
            'academic_year_id' => isset($class_students[0]) ? $class_students[0]->academic_year_id : 0,
            'branch_class_section_id' => isset($class_students[0]) ? $class_students[0]->branch_class_section_id : 0,
            'subject_id' => $subject_id,
        ])->whereDate('attendance_date', $date)->with('attendance_status')->get();

        $data['branch_class_section'] = BranchClassSection::with(['sections', 'com_classes'])->where('id', $branch_class_section_id)->first();
        $data['subject'] = Subject::find($subject_id);

        $subject_name = isset($data['subject']['subject_name']) ? $data['subject']['subject_name'] : '';
        $data['title'] = $data['branch_class_section']['com_classes']['class_name'] . ' ' . $data['branch_class_section']['sections']['section_name'] . ' ' . $subject_name;

        return view('employees.teachers.student_attendance', compact('data'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        \DB::beginTransaction();

        foreach ($request->attendance as $item) {
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $item['student_id'],
                    'attendance_date' => $request->attendance_date,
                    'branch_class_section_id' => $request->branch_class_section_id,
                    'subject_id' => $request->subject_id,
                ],
                [
                    'academic_year_id' => $request->academic_year_id,
                    'employee_id' => auth()->user()->employee->id,
                    'attendance_status_id' => $item['attendance_status_id'],
                    'online_attendance' => isset($request->online_attendance),
                ]
            );
        }

        \DB::commit();
        return redirect()->back()->with('success', 'Attendance saved successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function show(StudentAttendance $studentAttendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentAttendance $studentAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentAttendance $studentAttendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StudentAttendance  $studentAttendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentAttendance $studentAttendance)
    {
        //
    }

    public function viewAttendanceCalendar($branch_class_section_id, $subject_id)
    {

        return view('employees.teachers.view_attendance_calendar');
    }

    public function attendanceReport(Request $request)
    {
        $branches = Branch::all();
        $classes = ComClass::all();
        $sections = Section::all();
        $academic_years = AcademicYear::all();

        if (! $request->ajax()) {
            return view('reports.attendance_report', compact(['academic_years', 'branches', 'classes', 'sections']));
        }

        $filters = $request['filters'];
        $date_range_array = explode(" to ", $filters['date_range'] ?? '');
        $branch = $filters['branch'] ?? null;
        $academic_year_id = $filters['academic_year_id'] ?? null;
        $branch_class_section = BranchClassSection::with(['branches', 'com_classes', 'sections'])
            ->find($filters['section'] ?? null);
        $month = isset($filters['selected_month']) ? date_parse(explode(" ", $filters['selected_month'])[0])['month'] : null;

        $data = [];

        if ($branch && $filters['class'] && $filters['section'] && $branch_class_section) {
            $class = $branch_class_section->class_id;
            $section = $branch_class_section->section_id;

            $studentCondition = ['branch_id' => $branch, 'status' => 'on_roll'];
            if (! empty($filters['student_id'])) {
                $studentCondition['roll_no'] = $filters['student_id'];
            }

            $students = Student::where($studentCondition)
                ->whereHas('active_class', function ($query) use ($class, $section, $academic_year_id) {
                    $query->where(['academic_year_id' => $academic_year_id, 'is_valid' => 1])
                        ->whereHas('branch_class_sections', function ($subQuery) use ($class, $section) {
                            $subQuery->where(['class_id' => $class, 'section_id' => $section]);
                        });
                });

            $students->with(['active_class']);

            $students->with([
                'student_attendance' => function ($query) use ($filters, $date_range_array, $month, $academic_year_id) {
                    $query->orderBy('attendance_date', 'asc');

                    if (! empty($filters['selected_month'])) {
                        $query->whereMonth('attendance_date', $month);
                    }

                    if (! empty($date_range_array[0])) {
                        $query->whereDate('attendance_date', '>=', $date_range_array[0]);
                    }

                    if (! empty($date_range_array[1])) {
                        $query->whereDate('attendance_date', '<=', $date_range_array[1]);
                    }

                    if (! empty($academic_year_id)) {
                        $query->where('academic_year_id', $academic_year_id);
                    }
                }
            ]);

            $data = $students->get()->toArray();
        }

        return response()->json($data);
    }
}
