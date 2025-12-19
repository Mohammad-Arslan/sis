<?php

namespace App\Listeners;

use App\Services\ActivityLoggerService;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function __construct(
        private readonly ActivityLoggerService $logger
    ) {
    }

    public function handle(Failed $event): void
    {
        $email = $event->credentials['email'] ?? 'unknown';
        $this->logger->logFailedLogin($email);
        // Test comment for pre-commit hook
    }
}
