<?php

namespace App\Jobs;

use App\Services\ActivityLoggerService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExampleLoggedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly ActivityLoggerService $logger
    ) {
    }

    public function handle(): void
    {
        $this->logger->logJob(
            jobName: static::class,
            payload: $this->job->getRawBody() ? json_decode($this->job->getRawBody(), true) : null
        );
    }
}
