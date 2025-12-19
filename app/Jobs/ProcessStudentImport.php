<?php

namespace App\Jobs;

use App\Imports\ImportStudent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ProcessStudentImport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 1800; // 30 minutes
    public $tries = 1;
    public $maxExceptions = 1;

    protected $filePath;
    protected $userId;

    public function __construct($filePath, $userId = null)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle()
    {
        try {
            Log::info('Starting student import job', ['file' => $this->filePath]);

            $import = new ImportStudent();
            Excel::import($import, $this->filePath);

            $stats = $import->getImportStats();

            Log::info('Student import completed', [
                'imported' => $stats['imported_count'],
                'skipped' => $stats['skipped_count'],
                'errors' => count($stats['errors'])
            ]);

            // Clean up the temporary file
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
            }
        } catch (\Exception $e) {
            Log::error('Student import job failed', [
                'file' => $this->filePath,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Clean up on failure
            if (Storage::exists($this->filePath)) {
                Storage::delete($this->filePath);
            }

            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Student import job failed permanently', [
            'file' => $this->filePath,
            'error' => $exception->getMessage()
        ]);

        // Clean up on permanent failure
        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }
}
