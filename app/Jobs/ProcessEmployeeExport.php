<?php

namespace App\Jobs;

use App\Events\EmployeeExportProgress;
use App\Models\ImportProgress;
use App\Exports\ExportEmployee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessEmployeeExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour timeout
    public $tries = 3; // Retry 3 times on failure
    public $backoff = 60; // Wait 60 seconds before retrying

    protected $exportId;
    protected $userId;
    protected $filters;
    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $exportId, int $userId, array $filters = [])
    {
        $this->exportId = $exportId;
        $this->userId = $userId;
        $this->filters = $filters;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Log::info("Starting employee export job", [
            //     'export_id' => $this->exportId,
            //     'user_id' => $this->userId,
            //     'filters' => $this->filters
            // ]);

            // Get export progress record
            $exportProgress = ImportProgress::where('import_id', $this->exportId)
                ->where('import_type', 'employee_export')
                ->first();
            
            if (!$exportProgress) {
                // Log::error("Export progress record not found for ID: {$this->exportId}");
                return;
            }

            // Mark as started
            $exportProgress->markAsStarted();

            // Get total count for progress calculation
            $totalCount = $this->getTotalEmployeeCount();
            
            // Update total rows in database
            $exportProgress->updateProgress([
                'total_rows' => $totalCount,
                'current_message' => 'Export process initiated.',
            ]);

            // Broadcast initial status
            broadcast(new EmployeeExportProgress(
                $this->exportId,
                0,
                $totalCount,
                0,
                0,
                0,
                'starting',
                'Export process initiated.',
                0
            ))->toOthers();

            // Create export instance with progress callback
            $export = new ExportEmployee($this->filters);
            $export->setProgressCallback(function($stats) use ($exportProgress) {
                $this->updateProgress($stats, $exportProgress);
            });

            // Generate file path
            $this->filePath = 'exports/employees/employee_export_' . $this->exportId . '.xlsx';
            
            // Process the export
            Excel::store($export, $this->filePath);

            // Get final statistics
            $stats = $export->getExportStats();

            // Mark as completed
            $exportProgress->markAsCompleted();

            // Update final progress
            $exportProgress->updateProgress([
                'processed_rows' => $stats['total_processed'],
                'imported_count' => $stats['exported'],
                'skipped_count' => $stats['skipped'],
                'error_count' => $stats['errors'],
                'current_message' => 'Export completed successfully!',
            ]);

            // Broadcast completion
            broadcast(new EmployeeExportProgress(
                $this->exportId,
                $stats['total_processed'],
                $stats['total_rows'],
                $stats['exported'],
                $stats['skipped'],
                $stats['errors'],
                'completed',
                "Export completed successfully! Exported: {$stats['exported']}, Skipped: {$stats['skipped']}",
                $stats['total_processed']
            ))->toOthers();

            // Log::info("Employee export job completed successfully", [
            //     'export_id' => $this->exportId,
            //     'stats' => $stats,
            //     'file_path' => $this->filePath
            // ]);

        } catch (\Exception $e) {
            // Log::error("Employee export job failed", [
            //     'export_id' => $this->exportId,
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString()
            // ]);

            // Mark as failed
            $exportProgress = ImportProgress::where('import_id', $this->exportId)
                ->where('import_type', 'employee_export')
                ->first();
            if ($exportProgress) {
                $exportProgress->markAsFailed($e->getMessage());
            }

            // Broadcast error
            broadcast(new EmployeeExportProgress(
                $this->exportId,
                0,
                0,
                0,
                0,
                0,
                'failed',
                'Export failed: ' . $e->getMessage(),
                0
            ))->toOthers();

            throw $e;
        }
    }

    /**
     * Get total employee count for progress calculation
     */
    protected function getTotalEmployeeCount(): int
    {
        $query = \App\Models\Employee::query();
        
        // Apply filters if any
        if (!empty($this->filters['company_id'])) {
            $query->where('company_id', $this->filters['company_id']);
        }
        
        if (!empty($this->filters['branch_id'])) {
            $query->where('branch_id', $this->filters['branch_id']);
        }
        
        if (!empty($this->filters['department_id'])) {
            $query->where('department_id', $this->filters['department_id']);
        }
        
        if (!empty($this->filters['designation_id'])) {
            $query->where('designation_id', $this->filters['designation_id']);
        }
        
        if (!empty($this->filters['gender'])) {
            $query->whereHas('user', function($q) {
                $q->where('gender', $this->filters['gender']);
            });
        }
        
        if (!empty($this->filters['job_status'])) {
            $query->where('job_status', $this->filters['job_status']);
        }
        
        if (!empty($this->filters['date_from'])) {
            $query->where('hiring_date', '>=', $this->filters['date_from']);
        }
        
        if (!empty($this->filters['date_to'])) {
            $query->where('hiring_date', '<=', $this->filters['date_to']);
        }
        
        return $query->count();
    }

    /**
     * Update progress and broadcast
     */
    protected function updateProgress(array $stats, ImportProgress $exportProgress): void
    {
        // Update database progress
        $exportProgress->updateProgress([
            'processed_rows' => $stats['total_processed'] ?? 0,
            'imported_count' => $stats['exported'] ?? 0,
            'skipped_count' => $stats['skipped'] ?? 0,
            'error_count' => $stats['errors'] ?? 0,
            'current_row' => $stats['current_row'] ?? 0,
            'current_message' => 'Processing employees...',
        ]);

        // Broadcast progress
        broadcast(new EmployeeExportProgress(
            $this->exportId,
            $stats['total_processed'] ?? 0,
            $stats['total_rows'] ?? 0,
            $stats['exported'] ?? 0,
            $stats['skipped'] ?? 0,
            $stats['errors'] ?? 0,
            'processing',
            'Processing employees...',
            $stats['current_row'] ?? 0
        ))->toOthers();
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // Log::error("Employee export job failed permanently", [
        //     'export_id' => $this->exportId,
        //     'error' => $exception->getMessage()
        // ]);

        // Mark as failed
        $exportProgress = ImportProgress::where('import_id', $this->exportId)
            ->where('import_type', 'employee_export')
            ->first();
        if ($exportProgress) {
            $exportProgress->markAsFailed($exception->getMessage());
        }

        // Broadcast final failure
        broadcast(new EmployeeExportProgress(
            $this->exportId,
            0,
            0,
            0,
            0,
            0,
            'failed',
            'Export failed permanently: ' . $exception->getMessage(),
            0
        ))->toOthers();
    }
}