<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\GradeBookPdfService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GenerateGradeBookPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 300; // 5 minutes

    /**
     * The student ID
     *
     * @var int
     */
    protected $studentId;

    /**
     * The student behaviour skill ID
     *
     * @var int
     */
    protected $studentBehaviourSkillId;

    /**
     * Cache key for storing the result
     *
     * @var string
     */
    protected $cacheKey;

    /**
     * Create a new job instance.
     *
     * @param int $studentId
     * @param int $studentBehaviourSkillId
     * @return void
     */
    public function __construct(int $studentId, int $studentBehaviourSkillId)
    {
        $this->studentId = $studentId;
        $this->studentBehaviourSkillId = $studentBehaviourSkillId;
        $this->cacheKey = "gradebook_pdf_{$studentId}_{$studentBehaviourSkillId}";
        
        // Set the queue to use
        $this->onQueue('pdf-generation');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Starting PDF generation job', [
                'student_id' => $this->studentId,
                'student_behaviour_skill_id' => $this->studentBehaviourSkillId
            ]);

            // Store processing status in cache
            Cache::put($this->cacheKey . '_status', 'processing', now()->addMinutes(10));

            // Generate PDF
            $pdfService = new GradeBookPdfService();
            $pdfData = $pdfService->generateAssessmentPdf($this->studentId, $this->studentBehaviourSkillId);

            if ($pdfData) {
                // Store the result in cache
                Cache::put($this->cacheKey, $pdfData, now()->addHours(24));
                Cache::put($this->cacheKey . '_status', 'completed', now()->addMinutes(10));

                Log::info('PDF generation completed successfully', [
                    'student_id' => $this->studentId,
                    'student_behaviour_skill_id' => $this->studentBehaviourSkillId,
                    'pdf_path' => $pdfData['path'] ?? null
                ]);
            } else {
                // Mark as failed
                Cache::put($this->cacheKey . '_status', 'failed', now()->addMinutes(10));
                
                Log::error('PDF generation failed - no data returned', [
                    'student_id' => $this->studentId,
                    'student_behaviour_skill_id' => $this->studentBehaviourSkillId
                ]);
            }
        } catch (\Exception $e) {
            // Mark as failed
            Cache::put($this->cacheKey . '_status', 'failed', now()->addMinutes(10));
            
            Log::error('PDF generation job failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'student_id' => $this->studentId,
                'student_behaviour_skill_id' => $this->studentBehaviourSkillId
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        // Mark as failed in cache
        Cache::put($this->cacheKey . '_status', 'failed', now()->addMinutes(10));
        
        Log::error('PDF generation job permanently failed after retries', [
            'error' => $exception->getMessage(),
            'student_id' => $this->studentId,
            'student_behaviour_skill_id' => $this->studentBehaviourSkillId
        ]);
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return [
            'pdf-generation',
            'student:' . $this->studentId,
            'assessment:' . $this->studentBehaviourSkillId
        ];
    }
}
