<?php

namespace App\Jobs;

use App\Events\EmployeeImportProgress;
use App\Imports\ImportEmployee;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        try {
            Log::info("Starting employee import job", [
                'import_id' => $this->importId,
                'file_path' => $this->filePath,
                'user_id' => $this->userId
            ]);

            // Initialize cache for tracking progress
            $this->initializeCache();

            // Broadcast initial status
            broadcast(new EmployeeImportProgress(
                $this->importId,
                0,
                0,
                0,
                0,
                0,
                'starting',
                'Initializing import...'
            ))->toOthers();

            // Create import instance with progress callback
            $import = new ImportEmployee();
            
            // Set up progress tracking
            $import->setProgressCallback(function($stats) {
                $this->updateProgress($stats);
            });

            // Process the import
            Excel::import($import, Storage::path($this->filePath));

            // Get final statistics
            $stats = $import->getImportStats();

            // Update cache with final stats
            Cache::put("employee_import_{$this->importId}_stats", $stats, now()->addHours(24));

            // Broadcast completion
            broadcast(new EmployeeImportProgress(
                $this->importId,
                $stats['total_processed'],
                $stats['total_rows'],
                $stats['imported'],
                $stats['skipped'],
                $stats['errors'],
                'completed',
                "Import completed successfully! Imported: {$stats['imported']}, Skipped: {$stats['skipped']}"
            ))->toOthers();

            // Clean up temporary file
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
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

            // Broadcast error
            broadcast(new EmployeeImportProgress(
                $this->importId,
                0,
                0,
                0,
                0,
                0,
                'failed',
                'Import failed: ' . $e->getMessage()
            ))->toOthers();

            // Clean up temporary file
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
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
    protected function updateProgress(array $stats): void
    {
        // Update cache
        Cache::put("employee_import_{$this->importId}_stats", $stats, now()->addHours(24));

        // Broadcast progress
        broadcast(new EmployeeImportProgress(
            $this->importId,
            $stats['total_processed'] ?? 0,
            $stats['total_rows'] ?? 0,
            $stats['imported'] ?? 0,
            $stats['skipped'] ?? 0,
            $stats['errors'] ?? 0,
            'processing',
            'Processing employees...'
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
            'Import failed permanently: ' . $exception->getMessage()
        ))->toOthers();

        // Clean up
        Cache::forget("employee_import_{$this->importId}_status");
        Cache::forget("employee_import_{$this->importId}_stats");

        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }
}
