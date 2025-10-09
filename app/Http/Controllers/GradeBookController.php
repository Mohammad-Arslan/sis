<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\AssessmentEntry;
use App\Models\Branch;
use App\Models\BranchAcademicYear;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\ClassStudent;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\GeneralBehaviour;
use App\Models\GradingCriteria;
use App\Models\NetworkAssociate;
use App\Models\Role;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentBehaviourSkill;
use App\Models\SubjectRemark;
use App\Models\TeacherType;
use App\Models\Term;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class GradeBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
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
                    return view('assessment.grade_book.action', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(TRUE);
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

        return view('assessment.grade_book.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function generateBulkProgressReports(Request $request)
    {
        $student_skill_data = $this->getStudentSkillsData($request);

        $students = $student_skill_data['students'];
        $student_behaviour_skill_id = $student_skill_data['student_behaiour_skill_id'];
        $bulk_data['html_body'] = '';
        $report_data = null;

        foreach ($students as $student) {
            $report_data = $this->getReportData($student->id, $student_behaviour_skill_id);
            $class_level = get_class_level($report_data['student_behaviour_skill']['class_id']);
            $report_data['class_level'] = $class_level;

            if ($class_level == 'EY') {
                $early_year_data = $this->get_EY_data($report_data);
                $bulk_data['html_body'] .= view('assessment.grade_book.bulk_progress_reports.bulk_progress_report_early_year_body', $early_year_data)->render();
            } else if (in_array($class_level, ['LP', 'UP'])) {
                $primary_data = $this->get_LP_data($report_data);
                $bulk_data['html_body'] .= view('assessment.grade_book.bulk_progress_reports.bulk_progress_report_lower_primary_body', $primary_data)->render();
            }
        }

        if ($report_data['class_level'] == 'EY') {
            $bulk_data['html'] = view('assessment.grade_book.bulk_progress_reports.bulk_progress_report_early_year', ['html' => $bulk_data['html_body']])->render();
        } else if (in_array($report_data['class_level'], ['LP', 'UP'])) {
            $bulk_data['html'] = view('assessment.grade_book.bulk_progress_reports.bulk_progress_report_lower_primary', ['html' => $bulk_data['html_body']])->render();
        }

        $bulk_data['class_level'] = $report_data['class_level'];
        $bulk_data['class_name'] = $report_data['student_behaviour_skill']['com_class']['class_name'];

        $progress_report_modal['modal'] = view('assessment.grade_book.bulk_progress_reports.bulk_progress_report_modal', ['data' => $bulk_data])->render();
        //    return $bulk_data['html'];
        return response()->json($progress_report_modal);
    }

    public function list_students(Request $request)
    {
        $data = $this->getStudentSkillsData($request);

        $student_list_data['html'] = view('assessment.grade_book.student_list_tr', $data)->render();
        return response()->json($student_list_data);
    }


    public function get_report($student_id, $student_behaviour_skill_id)
    {
        $data = $this->getReportData($student_id, $student_behaviour_skill_id);

        $class_level = get_class_level($data['student_behaviour_skill']['class_id']);
        $data['class_level'] = $class_level;
        if ($class_level == 'EY') {
            $data = $this->get_EY_data($data);
            // dd($data);
            return view('assessment.grade_book.reports.progress_report_modal', ['data' => $data]);
        } else if (in_array($class_level, ['LP', 'UP'])) {
            $data = $this->get_LP_data($data);
            return view('assessment.grade_book.reports.progress_report_modal', ['data' => $data]);
        }

        abort(404);
    }

    public function get_EY_data($data)
    {

        $data['outer_skills'] = Skill::with(['children'])
            ->where([['class_id', $data['student_behaviour_skill']['class_id']], ['term_id', $data['student_behaviour_skill']['term_id']], ['status', 'active'], ['parent_id', 0]])
            ->whereNull('sort_no')->get();

        $data['inner_skills'] = Skill::with(['children'])
            ->where([['class_id', $data['student_behaviour_skill']['class_id']], ['term_id', $data['student_behaviour_skill']['term_id']], ['status', 'active'], ['parent_id', 0]])
            ->whereNotNull('sort_no')
            ->orderBy('sort_no')
            ->get()->groupBy('sort_no');
        //        dd($data);

        $data['student_skill'] = StudentBehaviourSkill::with([
            'student_behaviour_skill_marks' => function ($q) use ($data) {
                $q->where('student_id', $data['student_id']);
                $q->whereNull('general_behaviour_id');
            }, 'student_behaviour_skill_remark' => function ($q) use ($data) {
                $q->where('student_id', $data['student_id']);
            },
        ])->where([
            ['academic_year_id', $data['student_behaviour_skill']['academic_year_id']],
            ['branch_id', $data['student_behaviour_skill']['branch_id']],
            ['term_id', $data['student_behaviour_skill']['term_id']],
            ['class_id', $data['student_behaviour_skill']['class_id']],
            ['section_id', $data['student_behaviour_skill']['section_id']],
        ])->first();

        if (!empty($data['student_skill']['student_behaviour_skill_marks']))
            $data['student_skill']['student_behaviour_skill_marks'] = $data['student_skill']['student_behaviour_skill_marks']->keyBy('skill_id');

        $student_id = (int)$data['student_id'];
        $branch_id = $data['student_behaviour_skill']['branch_id'];
        $section_id = $data['student_behaviour_skill']['section_id'];
        $class_id = $data['student_behaviour_skill']['class_id'];
        $term_id = $data['student_behaviour_skill']['term_id'];
        $academic_year_id = $data['student_behaviour_skill']['academic_year_id'];
        $branch_class_section_id = BranchClassSection::where([['branch_id', $branch_id], ['class_id', $class_id], ['section_id', $section_id]])->first()->id;
        $term = Term::find($term_id);
        $term_start_date = $term->start_date;
        $term_end_date = $term->end_date;

        $data['total_no_of_working_days'] = getBranchWorkingDays($branch_id, $term_id, $academic_year_id);
        $data = $this->getStudentAttendanceData($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date, $data);
        $data['class_average_age'] = calculate_class_average_age($branch_id, $class_id, $section_id);
        return $data;
    }

    /**
     * @param $data
     * @return array|void
     */
    public function get_LP_data($data)
    {
        $student_behaviour_skill = $data['student_behaviour_skill'];

        $academic_year_id = $student_behaviour_skill['academic_year_id'];
        $branch_id = $student_behaviour_skill['branch_id'];
        $class_id = $student_behaviour_skill['class_id'];
        $section_id = $student_behaviour_skill['section_id'];
        $term_id = $student_behaviour_skill['term_id'];
        $student_id = (int)$data['student_id'];
        $term = Term::find($term_id);
        $term_start_date = $term->start_date;
        $term_end_date = $term->end_date;

        $branch_class_section_id = BranchClassSection::where([['branch_id', $branch_id], ['class_id', $class_id], ['section_id', $section_id]])->first()->id;

        if (!is_null($student_id)) {
            $data['total_no_of_working_days'] = getBranchWorkingDays($branch_id, $term_id, $academic_year_id);
            $data = $this->getStudentAttendanceData($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date, $data);

            $assessment_entries_minor_subject_wise = AssessmentEntry::whereNull('assessment_level_one_id')->where('academic_year_id', $academic_year_id)
                ->where('branch_id', $branch_id)
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('term_id', $term_id)->with(['student_assessment_marks' => function ($q) use ($student_id) {
                    $q->where('student_id', $student_id);
                }])->get();

            $minor_subject_grades = array();
            $all_minor_subject_grade = array();
            foreach ($assessment_entries_minor_subject_wise as $assessment_entries_minor_subject_wise_key => $single_assessment_entry_subject) {
                $minor_subject_grades['subject_name'] = $single_assessment_entry_subject->subject->subject_name;
                $minor_subject_grades['grade'] = $single_assessment_entry_subject->student_assessment_marks->first()->overall_grade;
                $all_minor_subject_grade[] = $minor_subject_grades;
            }

            $assessment_entries_subject_wise = AssessmentEntry::whereNotNull('assessment_level_two_id')->where('academic_year_id', $academic_year_id)
                ->where('branch_id', $branch_id)
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('term_id', $term_id)->get()->groupBy('subject_id');

            $subject_remarks = SubjectRemark::where('academic_year_id', $academic_year_id)
                ->where('branch_id', $branch_id)
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('term_id', $term_id)->get()->groupBy('subject_id');
            // dd($subject_remarks->toArray());
            $assessment_weightage = array();
            $assessment_data = array();
            $subject_assessment_remarks = array();


            foreach ($subject_remarks as $key => $single_subject_remarks) {
                $subject_name = $single_subject_remarks->first()->subject->subject_name;
                $remark = $single_subject_remarks->first()->student_subject_remarks->where('student_id', $student_id)->first()->remarks;
                $subject_assessment_remarks[$subject_name] = $remark;
            }
             //dd($subject_assessment_remarks);

            foreach ($assessment_entries_subject_wise as $single_subject_assessment_entries) {

                $single_subject_assessments_levels_wise = $single_subject_assessment_entries->groupBy('assessment_level_two_id');

                foreach ($single_subject_assessments_levels_wise as $single_subject_single_level_all_assessment => $assessment) {
                    if (is_null($assessment->first()->assessment_level_three_id)) {
                        if (!is_null($assessment->first()->student_assessment_marks->where('student_id', $student_id)->first())) {
                            $student_assessment_name = $assessment->first()->student_assessment_marks->where('student_id', $student_id)->first()->assessment_entry->assessment_level_two->name;
                            $student_assessment_weight = $assessment->first()->student_assessment_marks->where('student_id', $student_id)->first()->assessment_entry->assessment_weightage;
                            $assessment_weightage[$student_assessment_name] = $student_assessment_weight;
                        }
                        $assessment_level = 'assessment_level_two';
                    } else {
                        if (!is_null($assessment->first()->student_assessment_marks->where('student_id', $student_id)->first())) {
                            $student_assessment_name = $assessment->first()->student_assessment_marks->where('student_id', $student_id)->first()->assessment_entry->assessment_level_three->name;
                            $student_assessment_weight = $assessment->first()->student_assessment_marks->where('student_id', $student_id)->first()->assessment_entry->assessment_weightage;
                            $assessment_weightage[$student_assessment_name] = $student_assessment_weight;
                        }
                        $assessment_level = 'assessment_level_three';
                    }
                    // dd($assessment->toArray());
                    $marks_percentage_result = $this->calculateMarksPercentage($student_id, $assessment, $assessment_level);
                    (!is_null($marks_percentage_result)) ? $assessment_data[] = $marks_percentage_result : '';
                }
            }


            $data['all_assessment_data'] = $this->removeDuplicateAssessmentNames($assessment_data);
            if (count($assessment_data) > 0) {
                array_multisort(array_column($data['all_assessment_data'], 'subject_sort'), SORT_ASC, $data['all_assessment_data']);
            }
            // dd($data['all_assessment_data']);
            $data['all_minor_subject_grade'] = $all_minor_subject_grade;
            $data['subject_assessment_remarks'] = $subject_assessment_remarks;
            $data['assessment_weightage'] = $assessment_weightage;
            $data['class_average_age'] = calculate_class_average_age($branch_id, $class_id, $section_id);
            // dd($data['assessment_weightage']);
            $data['general_behaviours'] = GeneralBehaviour::with('children')->where('parent_id', 0)->get();
            $data['student_behaviour'] = StudentBehaviourSkill::with(['student_behaviour_skill_marks' => function ($q) use ($data) {
                $q->where('student_id', $data['student_id']);
                $q->whereNull('skill_id');
            }, 'student_behaviour_skill_remark' => function ($q) use ($data) {
                $q->where('student_id', $data['student_id']);
            }])->where([
                ['academic_year_id', $data['student_behaviour_skill']['academic_year_id']],
                ['branch_id', $data['student_behaviour_skill']['branch_id']],
                ['term_id', $data['student_behaviour_skill']['term_id']],
                ['class_id', $data['student_behaviour_skill']['class_id']],
                ['section_id', $data['student_behaviour_skill']['section_id']],
            ])->first();

            if (!empty($data['student_behaviour']['student_behaviour_skill_marks']))
                $data['student_behaviour']['student_behaviour_skill_marks'] = $data['student_behaviour']['student_behaviour_skill_marks']->keyBy('general_behaviour_id');

            return $data;
        }

        abort(404);
    }

    public function get_UP_data($data)
    {
    }

    public function get_class_teacher_name($data)
    {

        $class_teacher_type = TeacherType::where('abbreviation', 'class')->first();

        $branch_class_section = BranchClassSection::with([
            'class_teacher' => function ($q) use ($data, $class_teacher_type) {
                $q->where('academic_year_id', $data['student_behaviour_skill']['academic_year_id']);
                $q->where('teacher_type_id', $class_teacher_type->id);
            },
        ])->where([
            ['branch_id', $data['student_behaviour_skill']['branch_id']],
            ['class_id', $data['student_behaviour_skill']['class_id']],
            ['section_id', $data['student_behaviour_skill']['section_id']],
        ])->first();
        return isset($branch_class_section['class_teacher']['employee']) ? $branch_class_section['class_teacher']['employee']['preferred_name'] : '';
    }
    public function get_school_head_name($data)
    {

        $role = Role :: where('name' , 'school_head')->first()->toArray();
        $designation = Designation::where('designation_name', $role['display_name'])->first()->toArray();
        // dd($designation);
        $school_head = Employee:: where('designation_id' , $designation['id'])->where('branch_id', $data['student_behaviour_skill']['branch_id'])->get()->toArray();
        $school_head_name = !empty($school_head) ? $school_head[0]['preferred_name'] : '';
        // dd($school_head_name);
        return isset($school_head_name) ? $school_head_name : '';
    }

    /**
     * @param $student_id
     * @param $assessment_entries
     * @return array
     */
    protected function calculateMarksPercentage($student_id, $assessment_entries, $assessment_level): ?array
    {
        $assessment_obtained_marks = 0;

        if (count($assessment_entries)) {
            foreach ($assessment_entries as $assessment) {

                $assessment_obtained_marks += (float)$assessment->student_assessment_marks->where('student_id', $student_id)->sum('obtained_marks_grades');
            }
            $assessment_total_marks = $assessment_entries->sum('grade_marks');
            $assessment_weightage = $assessment_entries->first()->assessment_weightage;
            $data['subject_name'] = $assessment_entries->first()->subject->subject_name;
            $data['subject_sort'] = $assessment_entries->first()->subject->sort_no;
            $data[$assessment_entries->first()[$assessment_level]->name] = number_format(($assessment_obtained_marks / $assessment_total_marks) * $assessment_weightage, 2);

            return $data;
        }
        return NULL;
    }

    public function getSubjectAssessmentRemark($assessment_entries, $student_id)
    {
    }

    /**
     * @param $assessment_data
     * @return array
     */
    protected function removeDuplicateAssessmentNames($assessment_data): array
    {
        $single_subject_assessments_marks = array();
        $all_subject_assessments_data = array();
        $next_ele = $previous_ele = NULL;
        $j = 0;
        $i = 0;
        //        dd($assessment_data);
        foreach ($assessment_data as $key => $assessment_marks) {

            if (!is_null($previous_ele) && $previous_ele['subject_name'] === $assessment_marks['subject_name']) {

                unset($assessment_data[$key - 1]);
                ++$j;
                $single_subject_assessments_marks = $previous_ele;

                /*It will override previous same array index with current array index and append new array index if exists, e.g.
                *  previous_ele = array("subject_name" => "Urdu"
                                        "subject_sort" => 2
                                        "Oral/Project Work" => "0.00")

                   $assessment_marks = array("subject_name" => "Urdu"
                                             "subject_sort" => 2
                                             "1st Per.Assmt." => "0.00")

                * after loop through the below loop it will look something like :
                                   array("subject_name" => "Urdu"
                                         "subject_sort" => 2
                                         "Oral/Project Work" => "0.00"
                                          "1st Per.Assmt." => "0.00")
                */

                foreach ($assessment_marks as $assessment => $marks) {
                    $single_subject_assessments_marks[$assessment] = $marks;
                }


                $previous_ele = $single_subject_assessments_marks;
                /*$all_subject_assessments_data[] = $single_subject_assessments_marks;
                 if ($j >= 2) {
                     array_splice($all_subject_assessments_data, 0, 1);
                 }
                unset($assessment_data[$key]);*/
                continue;
            } else if (!is_null($previous_ele) && $j >= 1) {
                $all_subject_assessments_data[] = $single_subject_assessments_marks;
            }
            if (array_key_exists($key + 1, $assessment_data) && $assessment_marks['subject_name'] !== $assessment_data[$key + 1]['subject_name']) {
                $all_subject_assessments_data[] = $assessment_marks;
            }
            $previous_ele = $assessment_marks;
            $j = 0;
        }
        $all_subject_assessments_data[] = $previous_ele;

        return $all_subject_assessments_data;
    }

    // protected function removeDuplicateAssessmentNames($assessment_data): array
    // {
    //     $single_subject_assessments_marks = array();
    //     $all_subject_assessments_data = array();
    //     $next_ele = $previous_ele = NULL;
    //     $j = 0;
    //     foreach ($assessment_data as $key => $assessment_marks) {

    //         if (!is_null($previous_ele) && $previous_ele['subject_name'] == $assessment_marks['subject_name']) {
    //             unset($assessment_data[$key - 1]);
    //             ++$j;
    //             $single_subject_assessments_marks = $previous_ele;

    //             foreach ($assessment_marks as $assessment => $marks) {
    //                 $single_subject_assessments_marks[$assessment] = $marks;
    //             }
    //             $previous_ele = $single_subject_assessments_marks;
    //              $all_subject_assessments_data[] = $single_subject_assessments_marks;
    //             if ($j >= 2) {
    //                 array_splice($all_subject_assessments_data, 0, 1);
    //             }

    //             unset($assessment_data[$key]);
    //             continue;
    //         }
    //         $previous_ele = $assessment_marks;
    //         $j = 0;
    //     }
    //     foreach ($assessment_data as $assessment) {
    //         $all_subject_assessments_data[] = $assessment;
    //     }
    //     return $all_subject_assessments_data;
    // }

    /**
     * @param $academic_year_id
     * @param $branch_class_section_id
     * @param int $student_id
     * @param $term_start_date
     * @param $term_end_date
     * @param $data
     * @return mixed
     */
    protected function getStudentAttendanceData($academic_year_id, $branch_class_section_id, int $student_id, $term_start_date, $term_end_date, $data)
    {
        $total_presents = getPresentStudentAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);
        $exempted_attendances = getStudentExemptedAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);
        $tardy_daya = getStudentTardyAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);

        //percentage of attendance =  (total present + exempted days + tardy attendance / total no days in the term) * 100

        $sum = $total_presents + $exempted_attendances + $tardy_daya;
        $data['attendance_percentage'] = round($sum / (int)$data['total_no_of_working_days'] * 100);
        $data['present_attendances'] = $total_presents;
        $data['absent_attendances'] = getStudentAbsentAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);
        return $data;
    }

    /**
     * @param $request
     * @return array
     */
    protected function getStudentSkillsData($request): array
    {
        $student_behaviour_skills = StudentBehaviourSkill::with([
            'student_behaviour_skill_marks',
            'student_behaviour_skill_remarks',
        ])->where('id', $request->student_behaiour_skill_id)->first();

        $marks_student_ids = $student_behaviour_skills['student_behaviour_skill_marks']->pluck('student_id')->toArray();
        $remarks_student_ids = $student_behaviour_skills['student_behaviour_skill_remarks']->pluck('student_id')->toArray();

        $student_ids = array_unique(array_merge($marks_student_ids, $remarks_student_ids));

        $data['students'] = Student::whereIn('id', $student_ids)->get();
        $data['student_behaiour_skill_id'] = $request->student_behaiour_skill_id;

        return $data;
    }

    /**
     * @param $student_id
     * @param $student_behaviour_skill_id
     * @return mixed
     */
    protected function getReportData($student_id, $student_behaviour_skill_id): array
    {
        // EY: Early Year || LP: Lower Primary || UP: Upper Primary

        $data['student_behaviour_skill'] = StudentBehaviourSkill::with([
            'student_behaviour_skill_marks' => function ($q) use ($student_id) {
                $q->where('student_id', $student_id);
            },
            'student_behaviour_skill_remark' => function ($q) use ($student_id) {
                $q->where('student_id', $student_id);
            },
            'academic_year',
            'branch',
            'term',
            'com_class',
            'section',
        ])->where('id', $student_behaviour_skill_id)->first();
        $data['student'] = Student::where('id', $student_id)->with('state')->first();
        $data['student_id'] = $student_id;
        $data['class_teacher_name'] = $this->get_class_teacher_name($data);
        $data['school_head_name'] = $this->get_school_head_name($data);
        $data['grading_keys'] = GradingCriteria::whereHas('classes', function ($q) use ($data) {
            $q->where('com_classes.id', $data['student_behaviour_skill']['class_id']);
        })->orderBy('grading_key')->get();
        $data['grading_criteria'] = $data['grading_keys'];
        return $data;
    }
}
