<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_id',
        'import_type',
        'user_id',
        'file_name',
        'total_rows',
        'processed_rows',
        'imported_count',
        'skipped_count',
        'error_count',
        'status',
        'current_message',
        'errors',
        'current_row',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'errors' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the user who initiated the import
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Update progress
     */
    public function updateProgress(array $data)
    {
        $this->update($data);
        return $this;
    }

    /**
     * Add error to the errors array
     */
    public function addError(array $error)
    {
        $errors = $this->errors ?? [];
        $errors[] = $error;
        $this->update(['errors' => $errors]);
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        if ($this->total_rows == 0) {
            return 0;
        }
        return round(($this->processed_rows / $this->total_rows) * 100, 2);
    }

    /**
     * Mark as started
     */
    public function markAsStarted()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark as failed
     */
    public function markAsFailed(string $message = null)
    {
        $this->update([
            'status' => 'failed',
            'completed_at' => now(),
            'current_message' => $message ?? 'Import failed',
        ]);
    }
}
