<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentLetterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'request_type',
        'purpose',
        'additional_notes',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'letter_content',
        'letter_file_path',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return '<span class="badge bg-warning">Pending</span>';
            case 'approved':
                return '<span class="badge bg-success">Approved</span>';
            case 'rejected':
                return '<span class="badge bg-danger">Rejected</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
}
