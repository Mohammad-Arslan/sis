<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\StudentBehaviourSkill;
use App\Models\Student;
use App\Models\GradingCriteria;
use App\Models\TeacherType;
use App\Models\BranchClassSection;
use App\Models\Term;
use App\Models\AssessmentEntry;
use App\Models\SubjectRemark;
use App\Models\GeneralBehaviour;
use App\Models\Skill;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GradeBookPdfService
{
    /**
     * Generate PDF for student assessment report
     *
     * @param int $studentId
     * @param int $studentBehaviourSkillId
     * @return array|null
     */
    public function generateAssessmentPdf(int $studentId, int $studentBehaviourSkillId): ?array
    {
        try {
            // Get report data with null safety
            $data = $this->getReportData($studentId, $studentBehaviourSkillId);

            if (! $data || ! isset($data['student_behaviour_skill'])) {
                Log::error('Failed to get report data', [
                    'student_id' => $studentId,
                    'student_behaviour_skill_id' => $studentBehaviourSkillId
                ]);
                return null;
            }

            // Determine class level
            $classLevel = get_class_level($data['student_behaviour_skill']['class_id'] ?? null);
            $data['class_level'] = $classLevel;

            // Prepare data based on class level
            if ($classLevel === 'EY') {
                $data = $this->prepareEarlyYearsData($data);
                $view = 'assessment.grade_book.pdf.mobile_early_years';
            } elseif (in_array($classLevel, ['LP', 'UP'])) {
                $data = $this->prepareLowerPrimaryData($data);
                $view = 'assessment.grade_book.pdf.mobile_lower_primary';
            } else {
                Log::error('Invalid class level', ['class_level' => $classLevel]);
                return null;
            }

            // Generate unique filename
            $filename = $this->generateFilename($studentId, $studentBehaviourSkillId);

            // Generate PDF with memory optimization
            $pdf = $this->generatePdfWithMemoryOptimization($view, $data);

            // Store PDF temporarily
            $path = $this->storePdf($pdf, $filename);

            if (! $path) {
                return null;
            }

            // Generate download URL
            $downloadUrl = $this->generateDownloadUrl($path);

            return [
                'filename' => $filename,
                'path' => $path,
                'download_url' => $downloadUrl,
                'generated_at' => Carbon::now()->toIso8601String(),
                'expires_at' => Carbon::now()->addHours(24)->toIso8601String()
            ];
        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'error' => $e->getMessage(),
                'student_id' => $studentId,
                'student_behaviour_skill_id' => $studentBehaviourSkillId
            ]);
            return null;
        }
    }

    /**
     * Get report data with null safety
     */
    protected function getReportData(int $studentId, int $studentBehaviourSkillId): ?array
    {
        try {
            $data = [];

            // Get student behaviour skill with relationships
            $data['student_behaviour_skill'] = StudentBehaviourSkill::with([
                'student_behaviour_skill_marks' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                },
                'student_behaviour_skill_remark' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                },
                'academic_year',
                'branch',
                'term',
                'com_class',
                'section',
            ])->find($studentBehaviourSkillId);

            if (! $data['student_behaviour_skill']) {
                return null;
            }

            // Get student data
            $data['student'] = Student::with('state')->find($studentId);
            if (! $data['student']) {
                return null;
            }

            $data['student_id'] = $studentId;
            $data['class_teacher_name'] = $this->getClassTeacherName($data);

            // Get grading criteria
            $data['grading_keys'] = GradingCriteria::whereHas('classes', function ($q) use ($data) {
                $q->where('com_classes.id', $data['student_behaviour_skill']['class_id'] ?? null);
            })->orderBy('grading_key')->get();

            $data['grading_criteria'] = $data['grading_keys'];

            return $data;
        } catch (\Exception $e) {
            Log::error('Failed to get report data', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get class teacher name with null safety
     */
    protected function getClassTeacherName(array $data): string
    {
        try {
            $classTeacherType = TeacherType::where('abbreviation', 'class')->first();

            if (! $classTeacherType) {
                return 'N/A';
            }

            $branchClassSection = BranchClassSection::with([
                'class_teacher' => function ($q) use ($data, $classTeacherType) {
                    $q->where('academic_year_id', $data['student_behaviour_skill']['academic_year_id'] ?? null)
                      ->where('teacher_type_id', $classTeacherType->id);
                },
            ])->where([
                ['branch_id', $data['student_behaviour_skill']['branch_id'] ?? null],
                ['class_id', $data['student_behaviour_skill']['class_id'] ?? null],
                ['section_id', $data['student_behaviour_skill']['section_id'] ?? null],
            ])->first();

            return $branchClassSection?->class_teacher?->employee?->preferred_name ?? 'N/A';
        } catch (\Exception $e) {
            Log::error('Failed to get class teacher name', ['error' => $e->getMessage()]);
            return 'N/A';
        }
    }

    /**
     * Prepare Early Years data
     */
    protected function prepareEarlyYearsData(array $data): array
    {
        try {
            // Get skills data
            $data['outer_skills'] = Skill::with(['children'])
                ->where([
                    ['class_id', $data['student_behaviour_skill']['class_id'] ?? null],
                    ['term_id', $data['student_behaviour_skill']['term_id'] ?? null],
                    ['status', 'active'],
                    ['parent_id', 0]
                ])
                ->whereNull('sort_no')
                ->get();

            $data['inner_skills'] = Skill::with(['children'])
                ->where([
                    ['class_id', $data['student_behaviour_skill']['class_id'] ?? null],
                    ['term_id', $data['student_behaviour_skill']['term_id'] ?? null],
                    ['status', 'active'],
                    ['parent_id', 0]
                ])
                ->whereNotNull('sort_no')
                ->orderBy('sort_no')
                ->get()
                ->groupBy('sort_no');

            // Get student skill data
            $data['student_skill'] = StudentBehaviourSkill::with([
                'student_behaviour_skill_marks' => function ($q) use ($data) {
                    $q->where('student_id', $data['student_id'])
                      ->whereNull('general_behaviour_id');
                },
                'student_behaviour_skill_remark' => function ($q) use ($data) {
                    $q->where('student_id', $data['student_id']);
                },
            ])->where([
                ['academic_year_id', $data['student_behaviour_skill']['academic_year_id'] ?? null],
                ['branch_id', $data['student_behaviour_skill']['branch_id'] ?? null],
                ['term_id', $data['student_behaviour_skill']['term_id'] ?? null],
                ['class_id', $data['student_behaviour_skill']['class_id'] ?? null],
                ['section_id', $data['student_behaviour_skill']['section_id'] ?? null],
            ])->first();

            if (! empty($data['student_skill']['student_behaviour_skill_marks'])) {
                $data['student_skill']['student_behaviour_skill_marks'] =
                    $data['student_skill']['student_behaviour_skill_marks']->keyBy('skill_id');
            }

            // Get attendance data
            $data = $this->getAttendanceData($data);
            $data['class_average_age'] = calculate_class_average_age(
                $data['student_behaviour_skill']['branch_id'] ?? null,
                $data['student_behaviour_skill']['class_id'] ?? null,
                $data['student_behaviour_skill']['section_id'] ?? null
            );

            return $data;
        } catch (\Exception $e) {
            Log::error('Failed to prepare Early Years data', ['error' => $e->getMessage()]);
            return $data;
        }
    }

    /**
     * Prepare Lower Primary data
     */
    protected function prepareLowerPrimaryData(array $data): array
    {
        try {
            $studentBehaviourSkill = $data['student_behaviour_skill'];

            $academicYearId = $studentBehaviourSkill['academic_year_id'] ?? null;
            $branchId = $studentBehaviourSkill['branch_id'] ?? null;
            $classId = $studentBehaviourSkill['class_id'] ?? null;
            $sectionId = $studentBehaviourSkill['section_id'] ?? null;
            $termId = $studentBehaviourSkill['term_id'] ?? null;
            $studentId = (int) $data['student_id'];

            // Get attendance data
            $data = $this->getAttendanceData($data);

            // Get assessment entries
            $assessmentEntriesMinorSubjectWise = AssessmentEntry::whereNull('assessment_level_one_id')
                ->where('academic_year_id', $academicYearId)
                ->where('branch_id', $branchId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('term_id', $termId)
                ->with(['student_assessment_marks' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                }])
                ->get();

            $minorSubjectGrades = [];
            $allMinorSubjectGrade = [];

            foreach ($assessmentEntriesMinorSubjectWise as $singleAssessmentEntry) {
                $minorSubjectGrades['subject_name'] = $singleAssessmentEntry?->subject?->subject_name ?? '';
                $minorSubjectGrades['grade'] = $singleAssessmentEntry?->student_assessment_marks?->first()?->overall_grade ?? '';
                $allMinorSubjectGrade[] = $minorSubjectGrades;
            }

            // Get assessment entries by subject
            $assessmentEntriesSubjectWise = AssessmentEntry::whereNotNull('assessment_level_two_id')
                ->where('academic_year_id', $academicYearId)
                ->where('branch_id', $branchId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('term_id', $termId)
                ->get()
                ->groupBy('subject_id');

            // Get subject remarks
            $subjectRemarks = SubjectRemark::where('academic_year_id', $academicYearId)
                ->where('branch_id', $branchId)
                ->where('class_id', $classId)
                ->where('section_id', $sectionId)
                ->where('term_id', $termId)
                ->get()
                ->groupBy('subject_id');

            // Process assessment data
            $assessmentWeightage = [];
            $assessmentData = [];
            $subjectAssessmentRemarks = [];

            foreach ($subjectRemarks as $key => $singleSubjectRemarks) {
                $subjectName = $singleSubjectRemarks?->first()?->subject?->subject_name ?? '';
                $remark = $singleSubjectRemarks?->first()?->student_subject_remarks?->where('student_id', $studentId)?->first()?->remarks ?? '';
                $subjectAssessmentRemarks[$subjectName] = $remark;
            }

            // Process assessment entries
            foreach ($assessmentEntriesSubjectWise as $singleSubjectAssessmentEntries) {
                $singleSubjectAssessmentsLevelsWise = $singleSubjectAssessmentEntries->groupBy('assessment_level_two_id');

                foreach ($singleSubjectAssessmentsLevelsWise as $assessment) {
                    $marksPercentageResult = $this->calculateMarksPercentage($studentId, $assessment);
                    if (! is_null($marksPercentageResult)) {
                        $assessmentData[] = $marksPercentageResult;
                    }
                }
            }

            $data['all_assessment_data'] = $this->removeDuplicateAssessmentNames($assessmentData);
            $data['all_minor_subject_grade'] = $allMinorSubjectGrade;
            $data['subject_assessment_remarks'] = $subjectAssessmentRemarks;
            $data['assessment_weightage'] = $assessmentWeightage;
            $data['class_average_age'] = calculate_class_average_age($branchId, $classId, $sectionId);

            // Get general behaviours
            $data['general_behaviours'] = GeneralBehaviour::with('children')->where('parent_id', 0)->get();

            // Get student behaviour
            $data['student_behaviour'] = StudentBehaviourSkill::with([
                'student_behaviour_skill_marks' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId)->whereNull('skill_id');
                },
                'student_behaviour_skill_remark' => function ($q) use ($studentId) {
                    $q->where('student_id', $studentId);
                }
            ])->where([
                ['academic_year_id', $academicYearId],
                ['branch_id', $branchId],
                ['term_id', $termId],
                ['class_id', $classId],
                ['section_id', $sectionId],
            ])->first();

            if (! empty($data['student_behaviour']['student_behaviour_skill_marks'])) {
                $data['student_behaviour']['student_behaviour_skill_marks'] =
                    $data['student_behaviour']['student_behaviour_skill_marks']->keyBy('general_behaviour_id');
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('Failed to prepare Lower Primary data', ['error' => $e->getMessage()]);
            return $data;
        }
    }

    /**
     * Get attendance data
     */
    protected function getAttendanceData(array $data): array
    {
        try {
            $branchId = $data['student_behaviour_skill']['branch_id'] ?? null;
            $sectionId = $data['student_behaviour_skill']['section_id'] ?? null;
            $classId = $data['student_behaviour_skill']['class_id'] ?? null;
            $termId = $data['student_behaviour_skill']['term_id'] ?? null;
            $academicYearId = $data['student_behaviour_skill']['academic_year_id'] ?? null;
            $studentId = (int) $data['student_id'];

            $branchClassSectionId = BranchClassSection::where([
                ['branch_id', $branchId],
                ['class_id', $classId],
                ['section_id', $sectionId]
            ])->first()?->id;

            $term = Term::find($termId);
            $termStartDate = $term?->start_date;
            $termEndDate = $term?->end_date;

            $data['total_no_of_working_days'] = getBranchWorkingDays($branchId, $termId, $academicYearId);

            $totalPresents = getPresentStudentAttendanceCount($academicYearId, $branchClassSectionId, $studentId, $termStartDate, $termEndDate);
            $exemptedAttendances = getStudentExemptedAttendanceCount($academicYearId, $branchClassSectionId, $studentId, $termStartDate, $termEndDate);
            $tardyDays = getStudentTardyAttendanceCount($academicYearId, $branchClassSectionId, $studentId, $termStartDate, $termEndDate);

            $sum = $totalPresents + $exemptedAttendances + $tardyDays;
            $totalWorkingDays = (int) $data['total_no_of_working_days'];

            $data['attendance_percentage'] = $totalWorkingDays > 0 ? round($sum / $totalWorkingDays * 100) : 0;
            $data['present_attendances'] = $totalPresents;
            $data['absent_attendances'] = getStudentAbsentAttendanceCount($academicYearId, $branchClassSectionId, $studentId, $termStartDate, $termEndDate);

            return $data;
        } catch (\Exception $e) {
            Log::error('Failed to get attendance data', ['error' => $e->getMessage()]);
            return $data;
        }
    }

    /**
     * Calculate marks percentage
     */
    protected function calculateMarksPercentage(int $studentId, $assessmentEntries): ?array
    {
        try {
            $assessmentObtainedMarks = 0;

            if (count($assessmentEntries) === 0) {
                return null;
            }

            foreach ($assessmentEntries as $assessment) {
                $assessmentObtainedMarks += (int) $assessment->student_assessment_marks
                    ->where('student_id', $studentId)
                    ->sum('obtained_marks_grades');
            }

            $assessmentTotalMarks = $assessmentEntries->sum('grade_marks');
            $assessmentWeightage = $assessmentEntries->first()?->assessment_weightage ?? 0;

            if ($assessmentTotalMarks > 0) {
                $data['subject_name'] = $assessmentEntries->first()?->subject?->subject_name ?? '';
                $assessmentLevel = is_null($assessmentEntries->first()->assessment_level_three_id)
                    ? 'assessment_level_two'
                    : 'assessment_level_three';

                $data[$assessmentEntries->first()[$assessmentLevel]?->name ?? ''] =
                    round(($assessmentObtainedMarks / $assessmentTotalMarks) * $assessmentWeightage);

                return $data;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to calculate marks percentage', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Remove duplicate assessment names
     */
    protected function removeDuplicateAssessmentNames(array $assessmentData): array
    {
        $singleSubjectAssessmentsMarks = [];
        $allSubjectAssessmentsData = [];
        $previousEle = null;
        $j = 0;

        foreach ($assessmentData as $key => $assessmentMarks) {
            if (! is_null($previousEle) && $previousEle['subject_name'] === $assessmentMarks['subject_name']) {
                unset($assessmentData[$key - 1]);
                ++$j;
                $singleSubjectAssessmentsMarks = $previousEle;

                foreach ($assessmentMarks as $assessment => $marks) {
                    $singleSubjectAssessmentsMarks[$assessment] = $marks;
                }

                $previousEle = $singleSubjectAssessmentsMarks;
                continue;
            } else if (! is_null($previousEle) && $j >= 1) {
                $allSubjectAssessmentsData[] = $singleSubjectAssessmentsMarks;
            }

            if (
                array_key_exists($key + 1, $assessmentData) &&
                $assessmentMarks['subject_name'] !== $assessmentData[$key + 1]['subject_name']
            ) {
                $allSubjectAssessmentsData[] = $assessmentMarks;
            }

            $previousEle = $assessmentMarks;
            $j = 0;
        }

        $allSubjectAssessmentsData[] = $previousEle;

        return $allSubjectAssessmentsData;
    }

    /**
     * Generate PDF with memory optimization
     */
    protected function generatePdfWithMemoryOptimization(string $view, array $data)
    {
        // Set memory limit temporarily for PDF generation
        $originalMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '256M');

        try {
            $pdf = PDF::loadView($view, $data);

            // Configure PDF for mobile
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'isFontSubsettingEnabled' => true,
                'defaultMediaType' => 'screen',
                'dpi' => 96,
                'enable_font_subsetting' => true,
            ]);

            return $pdf;
        } finally {
            // Restore original memory limit
            ini_set('memory_limit', $originalMemoryLimit);
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(int $studentId, int $studentBehaviourSkillId): string
    {
        $timestamp = Carbon::now()->format('YmdHis');
        $hash = substr(md5($studentId . $studentBehaviourSkillId . $timestamp), 0, 8);

        return "gradebook_report_{$studentId}_{$studentBehaviourSkillId}_{$timestamp}_{$hash}.pdf";
    }

    /**
     * Store PDF file
     */
    protected function storePdf($pdf, string $filename): ?string
    {
        try {
            $directory = 'temp/gradebook_reports/' . Carbon::now()->format('Y/m/d');

            // Ensure directory exists
            Storage::disk('public')->makeDirectory($directory);

            $path = $directory . '/' . $filename;

            // Store PDF
            Storage::disk('public')->put($path, $pdf->output());

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to store PDF', [
                'error' => $e->getMessage(),
                'filename' => $filename
            ]);
            return null;
        }
    }

    /**
     * Generate download URL
     */
    protected function generateDownloadUrl(string $path): string
    {
        return url('storage/' . $path);
    }

    /**
     * Clean up old PDF files (to be called by scheduled job)
     */
    public function cleanupOldPdfs(int $hoursOld = 24): int
    {
        try {
            $cutoffTime = Carbon::now()->subHours($hoursOld);
            $baseDirectory = 'temp/gradebook_reports';
            $deletedCount = 0;

            // Get all PDF files in the temp directory
            $files = Storage::disk('public')->allFiles($baseDirectory);

            foreach ($files as $file) {
                $fileTime = Storage::disk('public')->lastModified($file);
                $fileDateTime = Carbon::createFromTimestamp($fileTime);

                if ($fileDateTime->isBefore($cutoffTime)) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                }
            }

            // Clean up empty directories
            $this->cleanupEmptyDirectories($baseDirectory);

            Log::info('Cleaned up old PDF files', ['deleted_count' => $deletedCount]);

            return $deletedCount;
        } catch (\Exception $e) {
            Log::error('Failed to cleanup old PDFs', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Clean up empty directories
     */
    protected function cleanupEmptyDirectories(string $baseDirectory): void
    {
        try {
            $directories = Storage::disk('public')->directories($baseDirectory);

            foreach ($directories as $directory) {
                $files = Storage::disk('public')->files($directory);
                $subDirectories = Storage::disk('public')->directories($directory);

                if (empty($files) && empty($subDirectories)) {
                    Storage::disk('public')->deleteDirectory($directory);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to cleanup empty directories', ['error' => $e->getMessage()]);
        }
    }
}
