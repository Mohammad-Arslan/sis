<?php

namespace App\Services;

use App\Models\Language;
use App\Models\AcademicYear;
use App\Models\BranchAcademicYear;
use App\Models\StudentPreviousSchool;
use App\Models\Branch;
use App\Traits\GeneratesActionButtons;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AcademicSettingsService
{
    use GeneratesActionButtons;

    /**
     * Get all languages with caching
     */
    public function getLanguages(): Collection
    {
        return Cache::remember('academic.languages', 3600, fn() => Language::all());
    }

    /**
     * Get all academic years with caching
     */
    public function getAcademicYears(): Collection
    {
        return Cache::remember('academic.academic-years', 3600, fn() => AcademicYear::all());
    }

    /**
     * Get all branches with caching
     */
    public function getBranches(): Collection
    {
        return Cache::remember('academic.branches', 3600, fn() => Branch::all());
    }

    /**
     * Create a language
     */
    public function createLanguage(array $data): Language
    {
        return DB::transaction(function () use ($data) {
            $language = Language::create($data);
            Cache::forget('academic.languages');
            return $language;
        });
    }

    /**
     * Update a language
     */
    public function updateLanguage(Language $language, array $data): Language
    {
        return DB::transaction(function () use ($language, $data) {
            $language->update($data);
            Cache::forget('academic.languages');
            return $language->fresh();
        });
    }

    /**
     * Delete a language
     */
    #[\NoDiscard]
    public function deleteLanguage(Language $language): bool
    {
        try {
            return DB::transaction(function () use ($language) {
                $deleted = $language->delete();
                Cache::forget('academic.languages');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete language', [
                'language_id' => $language->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create an academic year
     */
    public function createAcademicYear(array $data): AcademicYear
    {
        return DB::transaction(function () use ($data) {
            // If setting as active, deactivate all others
            if (isset($data['active']) && $data['active'] == 1) {
                AcademicYear::where('active', 1)->update(['active' => 0]);
            }

            $academicYear = AcademicYear::create($data);
            Cache::forget('academic.academic-years');
            return $academicYear;
        });
    }

    /**
     * Update an academic year
     */
    public function updateAcademicYear(AcademicYear $academicYear, array $data): AcademicYear
    {
        return DB::transaction(function () use ($academicYear, $data) {
            // If setting as active, deactivate all others
            if (isset($data['active']) && $data['active'] == 1) {
                AcademicYear::where('active', 1)->where('id', '!=', $academicYear->id)->update(['active' => 0]);
            }

            $academicYear->update($data);
            Cache::forget('academic.academic-years');
            return $academicYear->fresh();
        });
    }

    /**
     * Delete an academic year
     */
    #[\NoDiscard]
    public function deleteAcademicYear(AcademicYear $academicYear): bool
    {
        try {
            return DB::transaction(function () use ($academicYear) {
                $deleted = $academicYear->delete();
                Cache::forget('academic.academic-years');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete academic year', [
                'academic_year_id' => $academicYear->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a branch academic year
     */
    public function createBranchAcademicYear(array $data): BranchAcademicYear
    {
        return DB::transaction(function () use ($data) {
            $branchAcademicYear = BranchAcademicYear::create($data);
            Cache::forget('academic.branch-academic-years');
            return $branchAcademicYear->load(['branch', 'academic_year']);
        });
    }

    /**
     * Update a branch academic year
     */
    public function updateBranchAcademicYear(BranchAcademicYear $branchAcademicYear, array $data): BranchAcademicYear
    {
        return DB::transaction(function () use ($branchAcademicYear, $data) {
            $branchAcademicYear->update($data);
            Cache::forget('academic.branch-academic-years');
            return $branchAcademicYear->fresh()->load(['branch', 'academic_year']);
        });
    }

    /**
     * Delete a branch academic year
     */
    #[\NoDiscard]
    public function deleteBranchAcademicYear(BranchAcademicYear $branchAcademicYear): bool
    {
        try {
            return DB::transaction(function () use ($branchAcademicYear) {
                $deleted = $branchAcademicYear->delete();
                Cache::forget('academic.branch-academic-years');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete branch academic year', [
                'branch_academic_year_id' => $branchAcademicYear->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create a student previous school
     */
    public function createStudentPreviousSchool(array $data): StudentPreviousSchool
    {
        return DB::transaction(function () use ($data) {
            $studentPreviousSchool = StudentPreviousSchool::create($data);
            Cache::forget('academic.student-previous-schools');
            return $studentPreviousSchool;
        });
    }

    /**
     * Update a student previous school
     */
    public function updateStudentPreviousSchool(StudentPreviousSchool $studentPreviousSchool, array $data): StudentPreviousSchool
    {
        return DB::transaction(function () use ($studentPreviousSchool, $data) {
            $studentPreviousSchool->update($data);
            Cache::forget('academic.student-previous-schools');
            return $studentPreviousSchool->fresh();
        });
    }

    /**
     * Delete a student previous school
     */
    #[\NoDiscard]
    public function deleteStudentPreviousSchool(StudentPreviousSchool $studentPreviousSchool): bool
    {
        try {
            return DB::transaction(function () use ($studentPreviousSchool) {
                $deleted = $studentPreviousSchool->delete();
                Cache::forget('academic.student-previous-schools');
                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete student previous school', [
                'student_previous_school_id' => $studentPreviousSchool->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
