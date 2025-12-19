<?php

namespace App\Services;

use App\Models\Student;
use App\Exports\ExportStudent;
use App\Exports\ExportStudentStreaming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportService
{
    /**
     * Configure PHP settings for large exports
     */
    public static function configureForLargeExport(): void
    {
        // Set memory limit for large exports
        ini_set('memory_limit', env('EXCEL_MEMORY_LIMIT', '1G'));

        // Set execution time limit
        ini_set('max_execution_time', env('EXCEL_EXECUTION_TIME', 300));

        // Disable output buffering for streaming
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for large file downloads
        if (! headers_sent()) {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Cache-Control: max-age=0');
        }
    }

    /**
     * Get the appropriate export class based on dataset size
     */
    public static function getExportClass(Request $request): string
    {
        $estimatedCount = self::estimateRecordCount($request);

        // Use streaming export for very large datasets (50K+ records)
        if ($estimatedCount > 50000) {
            return ExportStudentStreaming::class;
        }

        // Use optimized export for smaller datasets
        return ExportStudent::class;
    }

    /**
     * Estimate the number of records that will be exported
     */
    private static function estimateRecordCount(Request $request): int
    {
        $query = Student::query();

        // Apply the same filters as the export
        if (auth()->user()->hasRole('network_associate')) {
            $query->where('branch_id', get_set_NWABranchId());
        }

        if ($request->academic_year_id && $request->academic_year_id != '') {
            $query->whereHas('class_students', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id)
                  ->where('is_valid', 1);
            });
        }

        if ($request->region_id && $request->region_id > 0) {
            $query->whereHas('branch', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }

        if ($request->branch_id && $request->branch_id > 0) {
            $query->where('branch_id', $request->branch_id);
        } elseif (! isSuperAdmin() && ! isHeadOfficeEmp()) {
            $query->where('branch_id', get_branch_id());
        }

        if ($request->section_id && $request->section_id > 0) {
            $query->whereHas('std_fee_package', function ($q) use ($request) {
                $q->where('section_id', $request->section_id);
            });
        }

        if ($request->class_id && $request->class_id > 0) {
            $query->whereHas('active_class.branch_class_sections', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->gender && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        if ($request->status && $request->status != 'all') {
            if (in_array($request->status, ['on_roll', 'registered', 'left', 'pass-out', 'processing'])) {
                $query->where('status', $request->status);
            } elseif (in_array($request->status, ['transferred'])) {
                $query->where('from_branch', '!=', null);
            } else {
                $query->whereNull('status');
            }
        }

        if ($request->searchName && $request->searchName != null) {
            $query->where(function ($q) use ($request) {
                $q->orWhere('first_name', 'like', '%' . $request->searchName . '%')
                  ->orWhere('middle_name', 'like', '%' . $request->searchName . '%')
                  ->orWhere('last_name', 'like', '%' . $request->searchName . '%')
                  ->orWhere('gender', 'like', '' . $request->searchName . '%')
                  ->orWhere('registration_no', 'like', '%' . $request->searchName . '%')
                  ->orWhere('roll_no', 'like', '%' . $request->searchName . '%');
            });
        }

        return $query->count();
    }

    /**
     * Get export performance recommendations
     */
    public static function getExportRecommendations(Request $request): array
    {
        $estimatedCount = self::estimateRecordCount($request);
        $memoryLimit = ini_get('memory_limit');
        $maxExecutionTime = ini_get('max_execution_time');

        $recommendations = [
            'estimated_records' => $estimatedCount,
            'current_memory_limit' => $memoryLimit,
            'current_execution_time' => $maxExecutionTime,
            'recommended_strategy' => 'optimized',
            'estimated_duration' => '2-5 minutes',
            'memory_usage' => 'Low',
            'should_use_queue' => false,
            'warnings' => []
        ];

        // Determine strategy and recommendations
        if ($estimatedCount > 50000) {
            $recommendations['recommended_strategy'] = 'streaming';
            $recommendations['estimated_duration'] = '5-15 minutes';
            $recommendations['memory_usage'] = 'Very Low';
            $recommendations['should_use_queue'] = true;
            $recommendations['warnings'][] = 'Large dataset detected. Consider using background job for better user experience.';
        } elseif ($estimatedCount > 10000) {
            $recommendations['estimated_duration'] = '3-8 minutes';
            $recommendations['memory_usage'] = 'Medium';
            $recommendations['should_use_queue'] = true;
            $recommendations['warnings'][] = 'Medium dataset detected. Consider using background job.';
        } elseif ($estimatedCount > 5000) {
            $recommendations['estimated_duration'] = '1-3 minutes';
            $recommendations['memory_usage'] = 'Low';
        }

        // Check memory limit
        $memoryLimitBytes = self::convertToBytes($memoryLimit);
        if ($memoryLimitBytes < 536870912) { // Less than 512MB
            $recommendations['warnings'][] = 'Memory limit is low. Consider increasing PHP memory_limit to 1G or higher.';
        }

        // Check execution time
        if ($maxExecutionTime < 300) { // Less than 5 minutes
            $recommendations['warnings'][] = 'Execution time limit is low. Consider increasing max_execution_time to 300 seconds or higher.';
        }

        return $recommendations;
    }

    /**
     * Convert memory limit string to bytes
     */
    private static function convertToBytes(string $memoryLimit): int
    {
        $unit = strtolower(substr($memoryLimit, -1));
        $value = (int) substr($memoryLimit, 0, -1);

        switch ($unit) {
            case 'k':
                return $value * 1024;
            case 'm':
                return $value * 1024 * 1024;
            case 'g':
                return $value * 1024 * 1024 * 1024;
            default:
                return $value;
        }
    }

    /**
     * Get export statistics
     */
    public static function getExportStats(): array
    {
        return [
            'total_students' => Student::count(),
            'students_by_status' => Student::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
            'students_by_branch' => Student::join('branches', 'students.branch_id', '=', 'branches.id')
                ->select('branches.br_name', DB::raw('count(*) as count'))
                ->groupBy('branches.id', 'branches.br_name')
                ->pluck('count', 'br_name')
                ->toArray(),
            'recent_exports' => self::getRecentExports(),
        ];
    }

    /**
     * Get recent export history (if logging is implemented)
     */
    private static function getRecentExports(): array
    {
        // This could be implemented to track export history
        // For now, return empty array
        return [];
    }

    /**
     * Validate export request
     */
    public static function validateExportRequest(Request $request): array
    {
        $errors = [];

        // Check if user has permission
        if (! auth()->user()->can('export-student')) {
            $errors[] = 'You do not have permission to export students.';
        }

        // Check if filters are reasonable
        $estimatedCount = self::estimateRecordCount($request);
        if ($estimatedCount > 100000) {
            $errors[] = 'Export would contain more than 100,000 records. Please apply more specific filters.';
        }

        // Check system resources
        $memoryLimit = ini_get('memory_limit');
        $memoryLimitBytes = self::convertToBytes($memoryLimit);
        if ($memoryLimitBytes < 268435456) { // Less than 256MB
            $errors[] = 'System memory limit is too low for this export. Contact administrator.';
        }

        return $errors;
    }
}
