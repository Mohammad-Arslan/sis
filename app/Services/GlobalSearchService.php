<?php

namespace App\Services;

use App\Models\Asset;
use App\Models\Branch;
use App\Models\ComClass;
use App\Models\Employee;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class GlobalSearchService
{
    private const MAX_RESULTS_PER_CATEGORY = 5;

    private const CACHE_TTL = 300; // 5 minutes

    /**
     * Search across all searchable entities
     */
    public function search(string $query, ?int $limit = null): array
    {
        if (empty(trim($query))) {
            return $this->getEmptyResults();
        }

        $limit = $limit ?? self::MAX_RESULTS_PER_CATEGORY;
        $cacheKey = 'global_search:'.md5($query.':'.$limit);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($query, $limit) {
            return [
                'students' => $this->searchStudents($query, $limit),
                'employees' => $this->searchEmployees($query, $limit),
                'branches' => $this->searchBranches($query, $limit),
                'classes' => $this->searchClasses($query, $limit),
                'subjects' => $this->searchSubjects($query, $limit),
                'users' => $this->searchUsers($query, $limit),
                'assets' => $this->searchAssets($query, $limit),
            ];
        });
    }

    /**
     * Search students by name, registration number, or roll number
     */
    private function searchStudents(string $query, int $limit): array
    {
        $searchTerm = '%'.$query.'%';

        return Student::query()
            ->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', $searchTerm)
                    ->orWhere('middle_name', 'like', $searchTerm)
                    ->orWhere('last_name', 'like', $searchTerm)
                    ->orWhere('registration_no', 'like', $searchTerm)
                    ->orWhere('roll_no', 'like', $searchTerm);
            })
            ->limit($limit)
            ->get()
            ->map(fn ($student) => $this->formatStudentResult($student))
            ->toArray();
    }

    /**
     * Search employees by name or employee ID
     */
    private function searchEmployees(string $query, int $limit): array
    {
        $searchTerm = '%'.$query.'%';

        return Employee::query()
            ->where(function ($q) use ($searchTerm) {
                $q->where('preferred_name', 'like', $searchTerm)
                    ->orWhere('employee_id', 'like', $searchTerm);
            })
            ->limit($limit)
            ->get()
            ->map(fn ($employee) => $this->formatEmployeeResult($employee))
            ->toArray();
    }

    /**
     * Search branches by name
     */
    private function searchBranches(string $query, int $limit): array
    {
        $searchTerm = '%'.$query.'%';

        return Branch::query()
            ->where('br_name', 'like', $searchTerm)
            ->limit($limit)
            ->get()
            ->map(fn ($branch) => $this->formatBranchResult($branch))
            ->toArray();
    }

    /**
     * Search classes by name
     */
    private function searchClasses(string $query, int $limit): array
    {
        $searchTerm = '%'.$query.'%';

        return ComClass::query()
            ->where('class_name', 'like', $searchTerm)
            ->limit($limit)
            ->get()
            ->map(fn ($class) => $this->formatClassResult($class))
            ->toArray();
    }

    /**
     * Search subjects by name
     */
    private function searchSubjects(string $query, int $limit): array
    {
        $searchTerm = '%'.$query.'%';

        return Subject::query()
            ->where('subject_name', 'like', $searchTerm)
            ->limit($limit)
            ->get()
            ->map(fn ($subject) => $this->formatSubjectResult($subject))
            ->toArray();
    }

    /**
     * Search users by name or email
     */
    private function searchUsers(string $query, int $limit): array
    {
        $searchTerm = '%'.$query.'%';

        return User::query()
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            })
            ->limit($limit)
            ->get()
            ->map(fn ($user) => $this->formatUserResult($user))
            ->toArray();
    }

    /**
     * Search assets by name, asset tag, or serial number
     */
    private function searchAssets(string $query, int $limit): array
    {
        $searchTerm = '%'.$query.'%';

        return Asset::query()
            ->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('asset_tag', 'like', $searchTerm)
                    ->orWhere('serial_number', 'like', $searchTerm);
            })
            ->limit($limit)
            ->get()
            ->map(fn ($asset) => $this->formatAssetResult($asset))
            ->toArray();
    }

    /**
     * Format student result for display
     */
    private function formatStudentResult(Student $student): array
    {
        return [
            'id' => $student->id,
            'title' => trim(implode(' ', array_filter([
                $student->first_name,
                $student->middle_name,
                $student->last_name,
            ]))),
            'subtitle' => $student->registration_no ? "Reg: {$student->registration_no}" : "Roll: {$student->roll_no}",
            'url' => route('students.index', ['search' => $student->registration_no ?? $student->roll_no]),
            'icon' => 'ri-user-line',
            'type' => 'student',
        ];
    }

    /**
     * Format employee result for display
     */
    private function formatEmployeeResult(Employee $employee): array
    {
        return [
            'id' => $employee->id,
            'title' => $employee->preferred_name ?? 'N/A',
            'subtitle' => $employee->employee_id ? "ID: {$employee->employee_id}" : 'Employee',
            'url' => route('employees.index', ['search' => $employee->employee_id ?? $employee->preferred_name]),
            'icon' => 'ri-briefcase-line',
            'type' => 'employee',
        ];
    }

    /**
     * Format branch result for display
     */
    private function formatBranchResult(Branch $branch): array
    {
        return [
            'id' => $branch->id,
            'title' => $branch->br_name,
            'subtitle' => $branch->abbreviation ?? 'Branch',
            'url' => route('branches.index', ['search' => $branch->br_name]),
            'icon' => 'ri-building-line',
            'type' => 'branch',
        ];
    }

    /**
     * Format class result for display
     */
    private function formatClassResult(ComClass $class): array
    {
        return [
            'id' => $class->id,
            'title' => $class->class_name,
            'subtitle' => $class->abbreviation ?? 'Class',
            'url' => route('class-subject-settings.index').'#classes',
            'icon' => 'ri-book-open-line',
            'type' => 'class',
        ];
    }

    /**
     * Format subject result for display
     */
    private function formatSubjectResult(Subject $subject): array
    {
        return [
            'id' => $subject->id,
            'title' => $subject->subject_name,
            'subtitle' => $subject->abbreviation ?? 'Subject',
            'url' => route('class-subject-settings.index').'#subjects',
            'icon' => 'ri-book-2-line',
            'type' => 'subject',
        ];
    }

    /**
     * Format user result for display
     */
    private function formatUserResult(User $user): array
    {
        return [
            'id' => $user->id,
            'title' => $user->name,
            'subtitle' => $user->email,
            'url' => route('users.show', $user->id),
            'icon' => 'ri-account-circle-line',
            'type' => 'user',
        ];
    }

    /**
     * Format asset result for display
     */
    private function formatAssetResult(Asset $asset): array
    {
        return [
            'id' => $asset->id,
            'title' => $asset->name,
            'subtitle' => $asset->asset_tag ? "Tag: {$asset->asset_tag}" : ($asset->serial_number ? "SN: {$asset->serial_number}" : 'Asset'),
            'url' => route('fixed-assets.assets.index', ['search' => $asset->asset_tag ?? $asset->name]),
            'icon' => 'ri-folder-line',
            'type' => 'asset',
        ];
    }

    /**
     * Get empty results structure
     */
    private function getEmptyResults(): array
    {
        return [
            'students' => [],
            'employees' => [],
            'branches' => [],
            'classes' => [],
            'subjects' => [],
            'users' => [],
            'assets' => [],
        ];
    }

    /**
     * Clear search cache (useful for cache invalidation)
     */
    public function clearCache(): void
    {
        // Only clear global search cache, not all cache
        // Cache::flush(); // This would clear all cache
        // For now, we rely on TTL expiration (5 minutes)
    }
}
