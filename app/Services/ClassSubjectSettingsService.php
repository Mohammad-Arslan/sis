<?php

namespace App\Services;

use App\Models\AttendanceType;
use App\Models\Branch;
use App\Models\ClassSubject;
use App\Models\ComClass;
use App\Models\Language;
use App\Models\State;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Traits\GeneratesActionButtons;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClassSubjectSettingsService
{
    use GeneratesActionButtons;

    /**
     * Get all classes with caching
     */
    public function getClasses(): Collection
    {
        return Cache::remember('class-subject-settings.classes', 3600, fn () => ComClass::with('attendance_type')->get());
    }

    /**
     * Get all subjects with caching
     */
    public function getSubjects(): Collection
    {
        return Cache::remember('class-subject-settings.subjects', 3600, fn () => Subject::with(['subject_group', 'language'])->get());
    }

    /**
     * Get all subject groups with caching
     */
    public function getSubjectGroups(): Collection
    {
        return Cache::remember('class-subject-settings.subject-groups', 3600, fn () => SubjectGroup::all());
    }

    /**
     * Get all languages with caching
     */
    public function getLanguages(): Collection
    {
        return Cache::remember('class-subject-settings.languages', 3600, fn () => Language::all());
    }

    /**
     * Get all branches with caching
     */
    public function getBranches(): Collection
    {
        return Cache::remember('class-subject-settings.branches', 3600, fn () => Branch::all());
    }

    /**
     * Get all states with caching
     */
    public function getStates(): Collection
    {
        return Cache::remember('class-subject-settings.states', 3600, fn () => State::all());
    }

    /**
     * Get all attendance types with caching
     */
    public function getAttendanceTypes(): Collection
    {
        return Cache::remember('class-subject-settings.attendance-types', 3600, fn () => AttendanceType::all());
    }

    /**
     * Create a class
     */
    public function createClass(array $data): ComClass
    {
        return DB::transaction(function () use ($data) {
            $class = ComClass::create($data);
            $this->clearClassCache();

            return $class->load('attendance_type');
        });
    }

    /**
     * Update a class
     */
    public function updateClass(ComClass $class, array $data): ComClass
    {
        return DB::transaction(function () use ($class, $data) {
            $class->update($data);
            $this->clearClassCache();

            return $class->fresh()->load('attendance_type');
        });
    }

    /**
     * Delete a class
     */
    #[\NoDiscard]
    public function deleteClass(ComClass $class): bool
    {
        try {
            return DB::transaction(function () use ($class) {
                $deleted = $class->delete();
                $this->clearClassCache();

                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete class', [
                'class_id' => $class->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a class subject
     */
    public function createClassSubject(array $data): ClassSubject
    {
        return DB::transaction(function () use ($data) {
            $classSubject = ClassSubject::create($data);
            $this->clearClassSubjectCache();

            return $classSubject->load(['branch', 'class', 'subject', 'state']);
        });
    }

    /**
     * Update a class subject
     */
    public function updateClassSubject(ClassSubject $classSubject, array $data): ClassSubject
    {
        return DB::transaction(function () use ($classSubject, $data) {
            $classSubject->update($data);
            $this->clearClassSubjectCache();

            return $classSubject->fresh()->load(['branch', 'class', 'subject', 'state']);
        });
    }

    /**
     * Delete a class subject
     */
    #[\NoDiscard]
    public function deleteClassSubject(ClassSubject $classSubject): bool
    {
        try {
            return DB::transaction(function () use ($classSubject) {
                $deleted = $classSubject->delete();
                $this->clearClassSubjectCache();

                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete class subject', [
                'class_subject_id' => $classSubject->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a subject
     */
    public function createSubject(array $data): Subject
    {
        return DB::transaction(function () use ($data) {
            $subject = Subject::create($data);
            $this->clearSubjectCache();

            return $subject->load(['subject_group', 'language']);
        });
    }

    /**
     * Update a subject
     */
    public function updateSubject(Subject $subject, array $data): Subject
    {
        return DB::transaction(function () use ($subject, $data) {
            $subject->update($data);
            $this->clearSubjectCache();

            return $subject->fresh()->load(['subject_group', 'language']);
        });
    }

    /**
     * Delete a subject
     */
    #[\NoDiscard]
    public function deleteSubject(Subject $subject): bool
    {
        try {
            return DB::transaction(function () use ($subject) {
                $deleted = $subject->delete();
                $this->clearSubjectCache();

                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete subject', [
                'subject_id' => $subject->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a subject group
     */
    public function createSubjectGroup(array $data): SubjectGroup
    {
        return DB::transaction(function () use ($data) {
            $subjectGroup = SubjectGroup::create($data);
            $this->clearSubjectGroupCache();

            return $subjectGroup;
        });
    }

    /**
     * Update a subject group
     */
    public function updateSubjectGroup(SubjectGroup $subjectGroup, array $data): SubjectGroup
    {
        return DB::transaction(function () use ($subjectGroup, $data) {
            $subjectGroup->update($data);
            $this->clearSubjectGroupCache();

            return $subjectGroup->fresh();
        });
    }

    /**
     * Delete a subject group
     */
    #[\NoDiscard]
    public function deleteSubjectGroup(SubjectGroup $subjectGroup): bool
    {
        try {
            return DB::transaction(function () use ($subjectGroup) {
                $deleted = $subjectGroup->delete();
                $this->clearSubjectGroupCache();

                return $deleted;
            });
        } catch (\Exception $e) {
            Log::error('Failed to delete subject group', [
                'subject_group_id' => $subjectGroup->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Clear all class-related cache
     */
    private function clearClassCache(): void
    {
        Cache::forget('class-subject-settings.classes');
        Cache::forget('class-subject-settings.attendance-types');
    }

    /**
     * Clear all class subject-related cache
     */
    private function clearClassSubjectCache(): void
    {
        Cache::forget('class-subject-settings.class-subjects');
    }

    /**
     * Clear all subject-related cache
     */
    private function clearSubjectCache(): void
    {
        Cache::forget('class-subject-settings.subjects');
        Cache::forget('class-subject-settings.subject-groups');
        Cache::forget('class-subject-settings.languages');
    }

    /**
     * Clear all subject group-related cache
     */
    private function clearSubjectGroupCache(): void
    {
        Cache::forget('class-subject-settings.subject-groups');
        Cache::forget('class-subject-settings.subjects');
    }
}
