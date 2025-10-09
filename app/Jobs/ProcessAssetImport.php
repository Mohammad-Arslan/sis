<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportAsset;
use Carbon\Carbon;

class ProcessAssetImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 60 minutes for large imports
    public $tries = 1; // Only try once to avoid duplicate imports
    public $maxExceptions = 1;
    
    // Add memory management for large imports
    public $deleteWhenMissingModels = true; // Clean up if model is missing

    protected $filePath;
    protected $userId;
    protected $importId;
    protected $specificLogFile;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath, $userId, $importId = null)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
        $this->importId = $importId;
        $this->specificLogFile = $importId ? "asset_import_{$importId}.log" : null;
        
        // Set higher memory limit for this job
        ini_set('memory_limit', '2G');
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Create a specific log file for this import
            if ($this->specificLogFile) {
                $logPath = storage_path("logs/{$this->specificLogFile}");
                File::put($logPath, ''); // Create or clear the file
            }
            
            $this->writeLog('info', 'Starting asset import job', [
                'file_path' => $this->filePath,
                'user_id' => $this->userId,
                'import_id' => $this->importId,
                'started_at' => now()->toDateTimeString(),
                'memory_limit' => ini_get('memory_limit')
            ]);

            // Check if file exists
            if (!Storage::exists($this->filePath)) {
                throw new \Exception("Import file not found: {$this->filePath}");
            }

            // Get file size for logging
            $fileSize = Storage::size($this->filePath);
            $this->writeLog('info', 'Asset import file details', [
                'file_path' => $this->filePath,
                'file_size' => $this->formatBytes($fileSize),
                'file_size_bytes' => $fileSize
            ]);

            // Create import instance with optimized settings
            $import = new ImportAsset($this->importId);
            
            // Reset counters
            $import->resetCounters();

            // Process the import
            $startTime = microtime(true);
            
            // Use queue import for very large files to optimize memory usage
            if ($fileSize > 10 * 1024 * 1024) { // Over 10MB
                $this->writeLog('info', 'Using queue import for large file', [
                    'file_size' => $this->formatBytes($fileSize)
                ]);
                
                Excel::queueImport($import, $this->filePath);
            } else {
                Excel::import($import, $this->filePath);
            }
            
            $endTime = microtime(true);
            $executionTime = round($endTime - $startTime, 2);

            // Log import summary before getting final stats
            $import->logImportSummary();
            
            // Get import statistics
            $stats = $import->getImportStats();
            
            // Log completion
            $this->writeLog('info', 'Asset import job completed successfully', [
                'import_id' => $this->importId,
                'user_id' => $this->userId,
                'execution_time_seconds' => $executionTime,
                'stats' => $stats,
                'completed_at' => now()->toDateTimeString(),
                'memory_peak' => $this->formatBytes(memory_get_peak_usage(true))
            ]);

            // Store import results in cache for retrieval by frontend
            $importResults = [
                'status' => 'completed',
                'imported_count' => $stats['imported'],
                'skipped_count' => $stats['skipped'],
                'error_count' => $stats['errors'],
                'total_processed' => $stats['total_processed'],
                'total_rows' => $stats['total_rows'],
                'execution_time' => $executionTime,
                'completed_at' => now()->toDateTimeString(),
                'user_id' => $this->userId,
                'file_path' => $this->filePath,
                'import_id' => $this->importId
            ];

            // Store results in cache with a unique key
            $cacheKey = 'asset_import_' . ($this->importId ?? uniqid());
            \Cache::put($cacheKey, $importResults, 3600); // Store for 1 hour

            // Clean up the temporary file
            $this->cleanupFile();

            // Log success message
            $this->writeLog('info', 'Asset import job cleanup completed', [
                'import_id' => $this->importId,
                'cache_key' => $cacheKey,
                'file_cleaned_up' => true
            ]);

        } catch (\Exception $e) {
            $this->writeLog('error', 'Asset import job failed', [
                'import_id' => $this->importId,
                'user_id' => $this->userId,
                'file_path' => $this->filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'failed_at' => now()->toDateTimeString(),
                'memory_peak' => $this->formatBytes(memory_get_peak_usage(true))
            ]);

            // Store error results in cache
            $errorResults = [
                'status' => 'failed',
                'error' => $e->getMessage(),
                'failed_at' => now()->toDateTimeString(),
                'user_id' => $this->userId,
                'file_path' => $this->filePath,
                'import_id' => $this->importId
            ];

            $cacheKey = 'asset_import_' . ($this->importId ?? uniqid());
            \Cache::put($cacheKey, $errorResults, 3600);

            // Clean up the temporary file even on error
            $this->cleanupFile();

            // Re-throw the exception to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        $this->writeLog('error', 'Asset import job failed permanently', [
            'import_id' => $this->importId,
            'user_id' => $this->userId,
            'file_path' => $this->filePath,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'failed_at' => now()->toDateTimeString(),
            'memory_peak' => $this->formatBytes(memory_get_peak_usage(true))
        ]);

        // Store failure results in cache
        $failureResults = [
            'status' => 'failed',
            'error' => $exception->getMessage(),
            'failed_at' => now()->toDateTimeString(),
            'user_id' => $this->userId,
            'file_path' => $this->filePath,
            'import_id' => $this->importId
        ];

        $cacheKey = 'asset_import_' . ($this->importId ?? uniqid());
        \Cache::put($cacheKey, $failureResults, 3600);

        // Clean up the temporary file
        $this->cleanupFile();
    }

    /**
     * Clean up the temporary import file
     */
    private function cleanupFile()
    {
        try {
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
                $this->writeLog('info', 'Asset import temporary file cleaned up', [
                    'file_path' => $this->filePath,
                    'import_id' => $this->importId
                ]);
            }
        } catch (\Exception $e) {
            $this->writeLog('warning', 'Failed to clean up asset import temporary file', [
                'file_path' => $this->filePath,
                'error' => $e->getMessage(),
                'import_id' => $this->importId
            ]);
        }
    }
    
    /**
     * Write log to both general and specific log file
     */
    private function writeLog($level, $message, $context = [])
    {
        // Always log to the main Laravel log
        Log::$level($message, $context);
        
        $logEntry = array_merge([
            'level' => $level,
            'message' => $message,
            'timestamp' => now()->toDateTimeString(),
            'import_id' => $this->importId
        ], $context);
        
        // Only write error-related logs to the main asset import log
        $errorLevels = ['error', 'critical', 'alert', 'emergency'];
        if (in_array($level, $errorLevels)) {
            File::append(storage_path('logs/asset_import.log'), json_encode($logEntry) . PHP_EOL);
        }
        
        // Always write to specific log file if we have one
        if ($this->specificLogFile) {
            File::append(storage_path("logs/{$this->specificLogFile}"), json_encode($logEntry) . PHP_EOL);
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
