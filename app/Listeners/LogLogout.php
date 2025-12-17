<?php

namespace App\Listeners;

use App\Services\ActivityLoggerService;
use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function __construct(
        private readonly ActivityLoggerService $logger
    ) {}

    public function handle(Logout $event): void
    {
        $this->logger->logLogout($event->user->id);
    }
}

