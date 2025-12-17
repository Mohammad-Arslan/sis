<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExitInterviewFeedback extends Model
{
    use HasFactory;

    protected $table = 'exit_interview_feedbacks';

    protected $fillable = [
        'employee_id',
        'employee_id_code',
        'department_id',
        'designation_role',
        'date_of_joining',
        'last_working_day',
        'reporting_manager',
        'reason_for_leaving_type',
        'reason_for_leaving_other',
        'overall_job_satisfaction',
        'relationship_with_supervisor_rating',
        'relationship_with_supervisor_comments',
        'relationship_with_colleagues_rating',
        'relationship_with_colleagues_comments',
        'training_development_rating',
        'training_development_comments',
        'workload_worklife_balance_rating',
        'workload_worklife_balance_comments',
        'salary_benefits_satisfaction',
        'salary_benefits_comments',
        'performance_appraisal_fairness',
        'performance_appraisal_comments',
        'work_environment_rating',
        'work_environment_comments',
        'policies_procedures_rating',
        'policies_procedures_comments',
        'communication_transparency_rating',
        'communication_transparency_comments',
        'staff_retention_suggestions',
        'clearance_status',
        'would_recommend_company',
        'recommendation_comments',
        'would_rejoin_future',
        'what_liked_most',
        'what_liked_least',
        'suggestions_for_improvement',
        'status',
        'reviewed_by',
        'reviewed_at',
        'hr_notes'
    ];

    protected $casts = [
        'last_working_day' => 'date',
        'date_of_joining' => 'date',
        'reviewed_at' => 'datetime',
        'clearance_status' => 'array',
        'would_recommend_company' => 'boolean',
        'would_rejoin_future' => 'boolean',
    ];

    /**
     * Get the employee that owns the exit interview feedback.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the department that the employee belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who reviewed the feedback.
     */
    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scope a query to only include submitted feedbacks.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope a query to only include reviewed feedbacks.
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Get the status badge attribute.
     */
    public function getStatusBadgeAttribute()
    {
        switch ($this->status) {
            case 'draft':
                return '<span class="badge bg-warning">Draft</span>';
            case 'submitted':
                return '<span class="badge bg-info">Submitted</span>';
            case 'reviewed':
                return '<span class="badge bg-success">Reviewed</span>';
            default:
                return '<span class="badge bg-secondary">Unknown</span>';
        }
    }
}
