<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AssessmentEntry;
use App\Models\BranchClass;
use App\Models\BranchClassSection;
use App\Models\GeneralBehaviour;
use App\Models\GradingCriteria;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudentBehaviourSkill;
use App\Models\SubjectRemark;
use App\Models\TeacherType;
use App\Models\Term;
use Illuminate\Http\Request;
use App\Services\GradeBookPdfService;
use App\Jobs\GenerateGradeBookPdfJob;
use Illuminate\Support\Facades\Cache;

class StudentAssessmentController extends Controller
{
    //    public function studentTerms(Request $request)
//     {
//         $branch_class_section = BranchClassSection::find($request->branch_class_section_id);
//         $section_id = !empty($branch_class_section) ? $branch_class_section->section_id : 0;
//         $branch_id = !empty($branch_class_section) ? $branch_class_section->branch_id : 0;
//         $query = StudentBehaviourSkill::with([
//             'student_behaviour_skill_marks' => function ($q) use ($request) {
//                 $q->where('student_id', $request->student_id);
//             },
//             'student_behaviour_skill_remark' => function ($q) use ($request) {
//                 $q->where('student_id', $request->student_id);
//             },
//             'term'
//         ])
//             ->where(['academic_year_id' => $request->academic_year_id, 'section_id' => $section_id, 'branch_id' => $branch_id])
//             ->get();

    //         // $query = Student::with(['student_behaviour_skill_marks.student_behaviour_skill.term', 'student_behaviour_skill_remarks.student_behaviour_skill.term'])
//         //     ->where('id', $request->student_id)->first();

    //         return response($query, 200);
//     }
    public function studentTerms(Request $request)
    {
        $branch_class_section = BranchClassSection::find($request->branch_class_section_id);
        $section_id = ! empty($branch_class_section) ? $branch_class_section->section_id : 0;
        $branch_id = ! empty($branch_class_section) ? $branch_class_section->branch_id : 0;

        $query = StudentBehaviourSkill::where('academic_year_id', $request->academic_year_id)
            ->where('section_id', $section_id)
            ->where('branch_id', $branch_id)
            ->whereHas('student_behaviour_skill_marks', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            })
            ->whereHas('student_behaviour_skill_remark', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            })
            ->with([
                'student_behaviour_skill_marks' => function ($q) use ($request) {
                    $q->where('student_id', $request->student_id);
                },
                'student_behaviour_skill_remark' => function ($q) use ($request) {
                    $q->where('student_id', $request->student_id);
                },
                'term'

            ])
            ->get();
        // return response($query->first()->academic_year->title, 200);

        // return response($query->first()->com_class->class_name, 200);
        if ($query->first()) {
            $query[0]["class_name"] = $query->first()?->com_class?->class_name ?? '';
            $query[0]["academic_year_title"] = $query->first()?->academic_year?->title ?? '';
        }
        return response($query, 200);
    }


    public function studentAssessments($student_behaviour_skill_id, $student_id)
    {
        try {
            // Validate IDs
            $studentId = (int) $student_id;
            $studentBehaviourSkillId = (int) $student_behaviour_skill_id;

            if ($studentId <= 0 || $studentBehaviourSkillId <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid parameters',
                    'message' => 'Student ID and Student Behaviour Skill ID must be valid positive integers'
                ], 400);
            }

            // Check cache first for existing PDF
            $cacheKey = "gradebook_pdf_{$studentId}_{$studentBehaviourSkillId}";
            $cachedPdf = Cache::get($cacheKey);

            if ($cachedPdf) {
                \Log::info('Returning cached PDF', [
                    'student_id' => $studentId,
                    'student_behaviour_skill_id' => $studentBehaviourSkillId
                ]);

                return response()->json([
                    'success' => true,
                    'data' => $cachedPdf,
                    'cached' => true
                ]);
            }

            // Generate PDF using the service
            $pdfService = new GradeBookPdfService();
            $pdfData = $pdfService->generateAssessmentPdf($studentId, $studentBehaviourSkillId);

            if (! $pdfData) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to generate PDF',
                    'message' => 'Unable to generate grade book report. Please check if the assessment data exists.',
                    'student_id' => $studentId,
                    'student_behaviour_skill_id' => $studentBehaviourSkillId
                ], 404);
            }

            // Cache the PDF data for 24 hours
            Cache::put($cacheKey, $pdfData, now()->addHours(24));

            // Return successful response
            return response()->json([
                'success' => true,
                'data' => $pdfData,
                'cached' => false
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to generate grade book PDF', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'student_id' => $student_id,
                'student_behaviour_skill_id' => $student_behaviour_skill_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'message' => 'An unexpected error occurred while generating the report'
            ], 500);
        }
    }

    /**
     * Generate PDF asynchronously using queue
     */
    public function studentAssessmentsAsync($student_behaviour_skill_id, $student_id)
    {
        try {
            // Validate IDs
            $studentId = (int) $student_id;
            $studentBehaviourSkillId = (int) $student_behaviour_skill_id;

            if ($studentId <= 0 || $studentBehaviourSkillId <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid parameters',
                    'message' => 'Student ID and Student Behaviour Skill ID must be valid positive integers'
                ], 400);
            }

            $cacheKey = "gradebook_pdf_{$studentId}_{$studentBehaviourSkillId}";

            // Check if PDF already exists
            $cachedPdf = Cache::get($cacheKey);
            if ($cachedPdf) {
                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'data' => $cachedPdf,
                    'cached' => true
                ]);
            }

            // Check if job is already processing
            $status = Cache::get($cacheKey . '_status');
            if ($status === 'processing') {
                return response()->json([
                    'success' => true,
                    'status' => 'processing',
                    'message' => 'PDF generation is in progress. Please check back in a moment.',
                    'check_status_url' => route('api.student.assessment.status', [
                        'student_behaviour_skill_id' => $studentBehaviourSkillId,
                        'student_id' => $studentId
                    ])
                ], 202);
            }

            // Dispatch the job
            GenerateGradeBookPdfJob::dispatch($studentId, $studentBehaviourSkillId);

            // Set initial status
            Cache::put($cacheKey . '_status', 'queued', now()->addMinutes(10));

            return response()->json([
                'success' => true,
                'status' => 'queued',
                'message' => 'PDF generation has been queued. Please check back in a moment.',
                'check_status_url' => route('api.student.assessment.status', [
                    'student_behaviour_skill_id' => $studentBehaviourSkillId,
                    'student_id' => $studentId
                ])
            ], 202);
        } catch (\Exception $e) {
            \Log::error('Failed to queue grade book PDF generation', [
                'error' => $e->getMessage(),
                'student_id' => $student_id,
                'student_behaviour_skill_id' => $student_behaviour_skill_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Internal server error',
                'message' => 'Failed to queue PDF generation'
            ], 500);
        }
    }

    /**
     * Check PDF generation status
     */
    public function checkPdfStatus($student_behaviour_skill_id, $student_id)
    {
        try {
            $studentId = (int) $student_id;
            $studentBehaviourSkillId = (int) $student_behaviour_skill_id;

            if ($studentId <= 0 || $studentBehaviourSkillId <= 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid parameters'
                ], 400);
            }

            $cacheKey = "gradebook_pdf_{$studentId}_{$studentBehaviourSkillId}";

            // Check if PDF is ready
            $pdfData = Cache::get($cacheKey);
            if ($pdfData) {
                return response()->json([
                    'success' => true,
                    'status' => 'completed',
                    'data' => $pdfData
                ]);
            }

            // Check generation status
            $status = Cache::get($cacheKey . '_status', 'not_found');

            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => $this->getStatusMessage($status)
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to check PDF status', [
                'error' => $e->getMessage(),
                'student_id' => $student_id,
                'student_behaviour_skill_id' => $student_behaviour_skill_id
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to check status'
            ], 500);
        }
    }

    /**
     * Get status message
     */
    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            'queued' => 'PDF generation is queued and will start soon.',
            'processing' => 'PDF is being generated. Please wait.',
            'completed' => 'PDF generation completed successfully.',
            'failed' => 'PDF generation failed. Please try again.',
            default => 'No PDF generation request found.'
        };
    }

    public function get_class_teacher_name($data)
    {

        $class_teacher_type = TeacherType::where('abbreviation', 'class')->first();

        $branch_class_section = BranchClassSection::with([
            'class_teacher' => function ($q) use ($data, $class_teacher_type) {
                $q->where('academic_year_id', $data['student_behaviour_skill']['academic_year_id'] ?? null);
                $q->where('teacher_type_id', $class_teacher_type?->id);
            },
        ])->where([
                    ['branch_id', $data['student_behaviour_skill']['branch_id'] ?? null],
                    ['class_id', $data['student_behaviour_skill']['class_id'] ?? null],
                    ['section_id', $data['student_behaviour_skill']['section_id'] ?? null],
                ])->first();

        return $branch_class_section?->class_teacher?->employee?->preferred_name ?? '';
    }

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

        // Debug logging to help identify the issue
        if (is_null($data['student_behaviour_skill'])) {
            \Log::warning('StudentBehaviourSkill query returned null', [
                'student_behaviour_skill_id' => $student_behaviour_skill_id,
                'student_id' => $student_id,
                'query_conditions' => [
                    'id' => $student_behaviour_skill_id,
                    'student_id_in_marks' => $student_id,
                    'student_id_in_remarks' => $student_id
                ]
            ]);
        }
        $data['student'] = Student::where('id', $student_id)->with('state')->first();
        $data['student_id'] = $student_id;
        $data['class_teacher_name'] = $this->get_class_teacher_name($data);
        $data['grading_keys'] = GradingCriteria::whereHas('classes', function ($q) use ($data) {
            $q->where('com_classes.id', $data['student_behaviour_skill']['class_id'] ?? null);
        })->orderBy('grading_key')->get();
        $data['grading_criteria'] = $data['grading_keys'];
        return $data;
    }

    public function get_LP_data($data): array
    {
        $student_behaviour_skill = $data['student_behaviour_skill'];

        $academic_year_id = $student_behaviour_skill['academic_year_id'];
        $branch_id = $student_behaviour_skill['branch_id'];
        $class_id = $student_behaviour_skill['class_id'];
        $section_id = $student_behaviour_skill['section_id'];
        $term_id = $student_behaviour_skill['term_id'];
        $student_id = (int) $data['student_id'];
        $term = Term::find($term_id);
        $term_start_date = $term?->start_date;
        $term_end_date = $term?->end_date;

        $branch_class_section_id = BranchClassSection::where([['branch_id', $branch_id], ['class_id', $class_id], ['section_id', $section_id]])->first()?->id;

        if (! is_null($student_id)) {
            $data['total_no_of_working_days'] = getBranchWorkingDays($branch_id, $term_id, $academic_year_id);
            $data = $this->getStudentAttendanceData($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date, $data);

            $assessment_entries_minor_subject_wise = AssessmentEntry::whereNull('assessment_level_one_id')->where('academic_year_id', $academic_year_id)
                ->where('branch_id', $branch_id)
                ->where('class_id', $class_id)
                ->where('section_id', $section_id)
                ->where('term_id', $term_id)->with([
                        'student_assessment_marks' => function ($q) use ($student_id) {
                            $q->where('student_id', $student_id);
                        }
                    ])->get();

            $minor_subject_grades = array();
            $all_minor_subject_grade = array();
            foreach ($assessment_entries_minor_subject_wise as $assessment_entries_minor_subject_wise_key => $single_assessment_entry_subject) {
                $minor_subject_grades['subject_name'] = $single_assessment_entry_subject?->subject?->subject_name ?? '';
                $minor_subject_grades['grade'] = $single_assessment_entry_subject?->student_assessment_marks?->first()?->overall_grade ?? '';
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
                $subject_name = $single_subject_remarks?->first()?->subject?->subject_name ?? '';
                $remark = $single_subject_remarks?->first()?->student_subject_remarks?->where('student_id', $student_id)?->first()?->remarks ?? '';
                $subject_assessment_remarks[$subject_name] = $remark;
            }
            // dd($subject_assessment_remarks);

            foreach ($assessment_entries_subject_wise as $single_subject_assessment_entries) {
                $single_subject_assessments_levels_wise = $single_subject_assessment_entries->groupBy('assessment_level_two_id');

                foreach ($single_subject_assessments_levels_wise as $single_subject_single_level_all_assessment => $assessment) {
                    if (is_null($assessment->first()->assessment_level_three_id)) {
                        if (! is_null($assessment->first()?->student_assessment_marks?->where('student_id', $student_id)?->first())) {
                            $student_assessment_name = $assessment->first()?->student_assessment_marks?->where('student_id', $student_id)?->first()?->assessment_entry?->assessment_level_two?->name ?? '';
                            $student_assessment_weight = $assessment->first()?->student_assessment_marks?->where('student_id', $student_id)?->first()?->assessment_entry?->assessment_weightage ?? 0;
                            $assessment_weightage[$student_assessment_name] = $student_assessment_weight;
                        }
                        $assessment_level = 'assessment_level_two';
                    } else {
                        if (! is_null($assessment->first()?->student_assessment_marks?->where('student_id', $student_id)?->first())) {
                            $student_assessment_name = $assessment->first()?->student_assessment_marks?->where('student_id', $student_id)?->first()?->assessment_entry?->assessment_level_three?->name ?? '';
                            $student_assessment_weight = $assessment->first()?->student_assessment_marks?->where('student_id', $student_id)?->first()?->assessment_entry?->assessment_weightage ?? 0;
                            $assessment_weightage[$student_assessment_name] = $student_assessment_weight;
                        }
                        $assessment_level = 'assessment_level_three';
                    }
                    // dd($assessment->toArray());
                    $marks_percentage_result = $this->calculateMarksPercentage($student_id, $assessment, $assessment_level);
                    (! is_null($marks_percentage_result)) ? $assessment_data[] = $marks_percentage_result : '';
                }
            }


            $data['all_assessment_data'] = $this->removeDuplicateAssessmentNames($assessment_data);
            // if (count($assessment_data) > 0) {
            //     array_multisort(array_column($data['all_assessment_data'], 'subject_sort'), SORT_ASC, $data['all_assessment_data']);
            // }
            // dd($data['all_assessment_data']);
            $data['all_minor_subject_grade'] = $all_minor_subject_grade;
            $data['subject_assessment_remarks'] = $subject_assessment_remarks;
            $data['assessment_weightage'] = $assessment_weightage;
            $data['class_average_age'] = calculate_class_average_age($branch_id, $class_id, $section_id);
            // dd($data['assessment_weightage']);
            $data['general_behaviours'] = GeneralBehaviour::with('children')->where('parent_id', 0)->get();
            $data['student_behaviour'] = StudentBehaviourSkill::with([
                'student_behaviour_skill_marks' => function ($q) use ($data) {
                    $q->where('student_id', $data['student_id']);
                    $q->whereNull('skill_id');
                },
                'student_behaviour_skill_remark' => function ($q) use ($data) {
                    $q->where('student_id', $data['student_id']);
                }
            ])->where([
                        ['academic_year_id', $data['student_behaviour_skill']['academic_year_id']],
                        ['branch_id', $data['student_behaviour_skill']['branch_id']],
                        ['term_id', $data['student_behaviour_skill']['term_id']],
                        ['class_id', $data['student_behaviour_skill']['class_id']],
                        ['section_id', $data['student_behaviour_skill']['section_id']],
                    ])->first();

            if (! empty($data['student_behaviour']['student_behaviour_skill_marks'])) {
                $data['student_behaviour']['student_behaviour_skill_marks'] = $data['student_behaviour']['student_behaviour_skill_marks']->keyBy('general_behaviour_id');
            }

            return $data;
        }

        abort(404);
    }

    public function get_EY_data($data): array
    {
        //Reason for comment: The below commented code produce duplicate data in the view
        /*$data['outer_skills'] = Skill::with(['children'])
            ->where([['class_id', $data['student_behaviour_skill']['class_id']], ['status', 'active'], ['parent_id', 0]])
            ->whereNull('sort_no')->get();

        $data['inner_skills'] = Skill::with(['children'])
            ->where([['class_id', $data['student_behaviour_skill']['class_id']], ['status', 'active'], ['parent_id', 0]])
            ->whereNotNull('sort_no')
            ->orderBy('sort_no')
            ->get()->groupBy('sort_no');*/

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
            },
            'student_behaviour_skill_remark' => function ($q) use ($data) {
                $q->where('student_id', $data['student_id']);
            },
        ])->where([
                    ['academic_year_id', $data['student_behaviour_skill']['academic_year_id']],
                    ['branch_id', $data['student_behaviour_skill']['branch_id']],
                    ['term_id', $data['student_behaviour_skill']['term_id']],
                    ['class_id', $data['student_behaviour_skill']['class_id']],
                    ['section_id', $data['student_behaviour_skill']['section_id']],
                ])->first();

        if (! empty($data['student_skill']['student_behaviour_skill_marks'])) {
            $data['student_skill']['student_behaviour_skill_marks'] = $data['student_skill']['student_behaviour_skill_marks']->keyBy('skill_id');
        }

        $student_id = (int) $data['student_id'];
        $branch_id = $data['student_behaviour_skill']['branch_id'];
        $section_id = $data['student_behaviour_skill']['section_id'];
        $class_id = $data['student_behaviour_skill']['class_id'];
        $term_id = $data['student_behaviour_skill']['term_id'];
        $academic_year_id = $data['student_behaviour_skill']['academic_year_id'];
        $branch_class_section_id = BranchClassSection::where([['branch_id', $branch_id], ['class_id', $class_id], ['section_id', $section_id]])->first()?->id;
        $term = Term::find($term_id);
        $term_start_date = $term?->start_date;
        $term_end_date = $term?->end_date;

        $data['total_no_of_working_days'] = getBranchWorkingDays($branch_id, $term_id, $academic_year_id);
        $data = $this->getStudentAttendanceData($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date, $data);
        $data['class_average_age'] = calculate_class_average_age($branch_id, $class_id, $section_id);
        return $data;
    }

    protected function getStudentAttendanceData($academic_year_id, $branch_class_section_id, int $student_id, $term_start_date, $term_end_date, $data)
    {
        $total_presents = getPresentStudentAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);
        $exempted_attendances = getStudentExemptedAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);
        $tardy_daya = getStudentTardyAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);

        //percentage of attendance =  (total present + exempted days + tardy attendance / total no days in the term) * 100

        $sum = $total_presents + $exempted_attendances + $tardy_daya;
        $data['attendance_percentage'] = round($sum / (int) $data['total_no_of_working_days'] * 100);
        $data['present_attendances'] = $total_presents;
        $data['absent_attendances'] = getStudentAbsentAttendanceCount($academic_year_id, $branch_class_section_id, $student_id, $term_start_date, $term_end_date);
        return $data;
    }

    protected function calculateMarksPercentage($student_id, $assessment_entries, $assessment_level): ?array
    {
        $assessment_obtained_marks = 0;

        if (count($assessment_entries)) {
            foreach ($assessment_entries as $assessment) {
                $assessment_obtained_marks += (int) $assessment->student_assessment_marks->where('student_id', $student_id)->sum('obtained_marks_grades');
            }

            $assessment_total_marks = $assessment_entries->sum('grade_marks');
            $assessment_weightage = $assessment_entries->first()?->assessment_weightage ?? 0;
            $data['subject_name'] = $assessment_entries->first()?->subject?->subject_name ?? '';
            $data[$assessment_entries->first()[$assessment_level]?->name ?? ''] = round(($assessment_obtained_marks / $assessment_total_marks) * $assessment_weightage);

            return $data;
        }
        return null;
    }

    protected function removeDuplicateAssessmentNames($assessment_data): array
    {
        $single_subject_assessments_marks = array();
        $all_subject_assessments_data = array();
        $next_ele = $previous_ele = null;
        $j = 0;
        $i = 0;
        //        dd($assessment_data);
        foreach ($assessment_data as $key => $assessment_marks) {
            if (! is_null($previous_ele) && $previous_ele['subject_name'] === $assessment_marks['subject_name']) {
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
            } else if (! is_null($previous_ele) && $j >= 1) {
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
}
