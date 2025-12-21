<?php

namespace App\Services;

use App\Models\Language;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Traits\GeneratesActionButtons;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectService
{
    use GeneratesActionButtons;

    /**
     * Get all subjects with relationships and caching
     */
    public function getAllSubjects(): Collection
    {
        return Cache::remember('subjects.all', 3600, fn () => Subject::with(['subject_group', 'language'])->get());
    }

    /**
     * Get subjects for DataTables
     */
    public function getSubjectsForDataTable(): Collection
    {
        return $this->getAllSubjects();
    }

    /**
     * Get all subject groups with caching
     */
    public function getSubjectGroups(): Collection
    {
        return Cache::remember('subjects.subject-groups', 3600, fn () => SubjectGroup::all());
    }

    /**
     * Get all languages with caching
     */
    public function getLanguages(): Collection
    {
        return Cache::remember('subjects.languages', 3600, fn () => Language::all());
    }

    /**
     * Create a new subject
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
     * Update an existing subject
     */
    public function updateSubject(Subject $subject, array $data): Subject
    {
        return DB::transaction(function () use ($subject, $data) {
            $subject->update($data);
            $this->clearSubjectCache();

            return $subject->fresh(['subject_group', 'language']);
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
     * Get a subject by ID with relationships
     */
    public function getSubjectById(int $id): ?Subject
    {
        return Subject::with(['subject_group', 'language'])->find($id);
    }

    /**
     * Get subject groups for DataTables
     */
    public function getSubjectGroupsForDataTable(): Collection
    {
        return $this->getSubjectGroups();
    }

    /**
     * Create a new subject group
     */
    public function createSubjectGroup(array $data): SubjectGroup
    {
        return DB::transaction(function () use ($data) {
            $subjectGroup = SubjectGroup::create($data);
            $this->clearSubjectCache();

            return $subjectGroup;
        });
    }

    /**
     * Update an existing subject group
     */
    public function updateSubjectGroup(SubjectGroup $subjectGroup, array $data): SubjectGroup
    {
        return DB::transaction(function () use ($subjectGroup, $data) {
            $subjectGroup->update($data);
            $this->clearSubjectCache();

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
                $this->clearSubjectCache();

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
     * Get a subject group by ID
     */
    public function getSubjectGroupById(int $id): ?SubjectGroup
    {
        return SubjectGroup::find($id);
    }

    /**
     * Clear all subject-related cache
     */
    private function clearSubjectCache(): void
    {
        Cache::forget('subjects.all');
        Cache::forget('subjects.subject-groups');
        Cache::forget('subjects.languages');
    }
}
