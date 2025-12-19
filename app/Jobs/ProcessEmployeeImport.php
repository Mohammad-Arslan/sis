<?php

namespace App\Jobs;

use App\Events\EmployeeImportProgress;
use App\Imports\ImportEmployee;
use App\Models\ImportProgress;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProcessEmployeeImport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 3600; // 1 hour timeout
    public $tries = 3; // Retry 3 times on failure
    public $backoff = 60; // Wait 60 seconds before retrying

    protected $filePath;
    protected $importId;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath, string $importId, int $userId)
    {
        $this->filePath = $filePath;
        $this->importId = $importId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Disable Telescope during import to prevent memory exhaustion
        if (class_exists(\Laravel\Telescope\Telescope::class)) {
            \Laravel\Telescope\Telescope::stopRecording();
        }

        // Increase memory limit for large imports (will use value from ImportEmployee constructor)
        // This is a safety measure in case the job runs before ImportEmployee sets it
        @ini_set('memory_limit', '1024M');

        try {
            Log::info("Starting employee import job", [
                'import_id' => $this->importId,
                'file_path' => $this->filePath,
                'user_id' => $this->userId
            ]);

            // Get import progress record
            $importProgress = ImportProgress::where('import_id', $this->importId)->first();

            if (! $importProgress) {
                Log::error("Import progress record not found for ID: {$this->importId}");
                return;
            }

            // Mark as started
            $importProgress->markAsStarted();

            // Get total rows for progress calculation
            $totalRows = Excel::toCollection(new ImportEmployee(), Storage::disk('local')->path($this->filePath))->flatten(1)->count();

            // Update total rows in database
            $importProgress->updateProgress([
                'total_rows' => $totalRows,
                'current_message' => 'Import process initiated.',
            ]);

            // Broadcast initial status
            broadcast(new EmployeeImportProgress(
                $this->importId,
                0,
                $totalRows,
                0,
                0,
                0,
                'starting',
                'Import process initiated.',
                0
            ))->toOthers();

            // Create import instance with progress callback
            $import = new ImportEmployee($this->importId, $this->userId);

            // Set up progress tracking
            $import->setProgressCallback(function ($stats) use ($importProgress) {
                $this->updateProgress($stats, $importProgress);
            });

            // Process the import
            Excel::import($import, Storage::disk('local')->path($this->filePath));

            // Get final statistics
            $stats = $import->getImportStats();

            // Mark as completed
            $importProgress->markAsCompleted();

            // Broadcast completion
            broadcast(new EmployeeImportProgress(
                $this->importId,
                $stats['total_processed'],
                $stats['total_rows'],
                $stats['imported'],
                $stats['skipped'],
                $stats['errors'],
                'completed',
                "Import completed successfully! Imported: {$stats['imported']}, Skipped: {$stats['skipped']}",
                $stats['total_processed']
            ))->toOthers();

            // Clean up temporary file
            if (Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }

            Log::info("Employee import job completed successfully", [
                'import_id' => $this->importId,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error("Employee import job failed", [
                'import_id' => $this->importId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Mark as failed
            $importProgress = ImportProgress::where('import_id', $this->importId)->first();
            if ($importProgress) {
                $importProgress->markAsFailed($e->getMessage());
            }

            // Broadcast error
            broadcast(new EmployeeImportProgress(
                $this->importId,
                0,
                0,
                0,
                0,
                0,
                'failed',
                'Import failed: ' . $e->getMessage(),
                0
            ))->toOthers();

            // Clean up temporary file
            if (Storage::disk('local')->exists($this->filePath)) {
                Storage::disk('local')->delete($this->filePath);
            }

            throw $e;
        }
    }

    /**
     * Initialize cache for tracking progress
     */
    protected function initializeCache(): void
    {
        Cache::put("employee_import_{$this->importId}_status", 'processing', now()->addHours(24));
        Cache::put("employee_import_{$this->importId}_stats", [
            'processed' => 0,
            'total' => 0,
            'imported' => 0,
            'skipped' => 0,
            'errors' => 0
        ], now()->addHours(24));
    }

    /**
     * Update progress and broadcast
     */
    protected function updateProgress(array $stats, ImportProgress $importProgress): void
    {
        // Update database progress
        $importProgress->updateProgress([
            'processed_rows' => $stats['total_processed'] ?? 0,
            'imported_count' => $stats['imported'] ?? 0,
            'skipped_count' => $stats['skipped'] ?? 0,
            'error_count' => $stats['errors'] ?? 0,
            'current_row' => $stats['total_processed'] ?? 0,
            'current_message' => 'Processing employees...',
        ]);

        // Broadcast progress
        broadcast(new EmployeeImportProgress(
            $this->importId,
            $stats['total_processed'] ?? 0,
            $stats['total_rows'] ?? 0,
            $stats['imported'] ?? 0,
            $stats['skipped'] ?? 0,
            $stats['errors'] ?? 0,
            'processing',
            'Processing employees...',
            $stats['total_processed'] ?? 0
        ))->toOthers();
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Employee import job failed permanently", [
            'import_id' => $this->importId,
            'error' => $exception->getMessage()
        ]);

        // Broadcast final failure
        broadcast(new EmployeeImportProgress(
            $this->importId,
            0,
            0,
            0,
            0,
            0,
            'failed',
            'Import failed permanently: ' . $exception->getMessage(),
            0
        ))->toOthers();

        // Clean up
        Cache::forget("employee_import_{$this->importId}_status");
        Cache::forget("employee_import_{$this->importId}_stats");

        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }
}
