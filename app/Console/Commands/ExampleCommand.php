<?php

namespace App\Console\Commands;

use App\Services\ActivityLoggerService;
use Illuminate\Console\Command;

class ExampleCommand extends Command
{
    protected $signature = 'example:log-command';
    protected $description = 'Example command that logs its execution';

    public function __construct(
        private readonly ActivityLoggerService $logger
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->logger->logCommand('example:log-command');

        $this->info('Command executed and logged!');

        return Command::SUCCESS;
    }
}
