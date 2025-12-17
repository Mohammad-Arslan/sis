<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ImportErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'import_id',
        'import_type',
        'user_id',
        'row_number',
        'error_type',
        'field_name',
        'error_message',
        'problematic_value',
        'row_data',
        'occurred_at',
    ];

    protected $casts = [
        'row_data' => 'array',
        'occurred_at' => 'datetime',
    ];

    /**
     * Get the user who initiated the import
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the import progress record
     */
    public function importProgress()
    {
        return $this->belongsTo(ImportProgress::class, 'import_id', 'import_id');
    }

    /**
     * Scope to get errors for a specific import
     */
    public function scopeForImport($query, $importId)
    {
        return $query->where('import_id', $importId);
    }

    /**
     * Scope to get errors by type
     */
    public function scopeByErrorType($query, $errorType)
    {
        return $query->where('error_type', $errorType);
    }

    /**
     * Get error type badge class for UI
     */
    public function getErrorTypeBadgeClassAttribute()
    {
        $classes = [
            'validation_error' => 'badge-danger',
            'import_error' => 'badge-warning',
            'lookup_error' => 'badge-info',
            'missing_fields' => 'badge-secondary',
            'database_error' => 'badge-dark',
        ];

        return $classes[$this->error_type] ?? 'badge-secondary';
    }

    /**
     * Get formatted error type for display
     */
    public function getFormattedErrorTypeAttribute()
    {
        $types = [
            'validation_error' => 'Validation Error',
            'import_error' => 'Import Error',
            'lookup_error' => 'Lookup Error',
            'missing_fields' => 'Missing Fields',
            'database_error' => 'Database Error',
        ];

        return $types[$this->error_type] ?? 'Unknown Error';
    }

    /**
     * Get truncated error message for display
     */
    public function getTruncatedErrorMessageAttribute()
    {
        return strlen($this->error_message) > 100 
            ? substr($this->error_message, 0, 100) . '...'
            : $this->error_message;
    }

    /**
     * Get formatted occurred at time
     */
    public function getFormattedOccurredAtAttribute()
    {
        return $this->occurred_at->format('M d, Y H:i:s');
    }

    /**
     * Static method to truncate logs for a specific import
     */
    public static function truncateForImport($importId)
    {
        return static::where('import_id', $importId)->delete();
    }

    /**
     * Static method to truncate all logs older than specified days
     */
    public static function truncateOldLogs($days = 30)
    {
        $cutoffDate = Carbon::now()->subDays($days);
        return static::where('created_at', '<', $cutoffDate)->delete();
    }
}
