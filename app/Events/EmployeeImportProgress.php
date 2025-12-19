<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeeImportProgress implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $importId;
    public $processed;
    public $total;
    public $imported;
    public $skipped;
    public $errors;
    public $status;
    public $message;
    public $currentRow;

    /**
     * Create a new event instance.
     */
    public function __construct(
        string $importId,
        int $processed,
        int $total,
        int $imported,
        int $skipped,
        int $errors,
        string $status = 'processing',
        string $message = '',
        int $currentRow = 0
    ) {
        $this->importId = $importId;
        $this->processed = $processed;
        $this->total = $total;
        $this->imported = $imported;
        $this->skipped = $skipped;
        $this->errors = $errors;
        $this->status = $status;
        $this->message = $message;
        $this->currentRow = $currentRow;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('employee-import.' . $this->importId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'import.progress';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'import_id' => $this->importId,
            'processed' => $this->processed,
            'total' => $this->total,
            'imported' => $this->imported,
            'skipped' => $this->skipped,
            'errors' => $this->errors,
            'current_row' => $this->currentRow, // Use actual current row tracking
            'percentage' => $this->total > 0 ? round(($this->processed / $this->total) * 100, 2) : 0,
            'status' => $this->status,
            'message' => $this->message,
        ];
    }
}
