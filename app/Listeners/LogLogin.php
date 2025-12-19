<?php

namespace App\Listeners;

use App\Services\ActivityLoggerService;
use Illuminate\Auth\Events\Login;

class LogLogin
{
    public function __construct(
        private readonly ActivityLoggerService $logger
    ) {
    }

    public function handle(Login $event): void
    {
        $this->logger->logLogin($event->user->id);
    }
}
