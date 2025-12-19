<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeExportProgress implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $exportId;
    public $processed;
    public $total;
    public $exported;
    public $skipped;
    public $errors;
    public $status;
    public $message;
    public $currentRow;

    public function __construct(
        string $exportId,
        int $processed,
        int $total,
        int $exported,
        int $skipped,
        int $errors,
        string $status = 'processing',
        string $message = '',
        int $currentRow = 0
    ) {
        $this->exportId = $exportId;
        $this->processed = $processed;
        $this->total = $total;
        $this->exported = $exported;
        $this->skipped = $skipped;
        $this->errors = $errors;
        $this->status = $status;
        $this->message = $message;
        $this->currentRow = $currentRow;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('employee-export.' . $this->exportId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'export.progress';
    }

    public function broadcastWith(): array
    {
        return [
            'export_id' => $this->exportId,
            'processed' => $this->processed,
            'total' => $this->total,
            'exported' => $this->exported,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
            'current_row' => $this->currentRow,
            'percentage' => $this->total > 0 ? round(($this->processed / $this->total) * 100, 2) : 0,
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
