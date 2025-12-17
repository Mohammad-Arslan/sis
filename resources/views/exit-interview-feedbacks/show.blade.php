@extends('layouts.master')

@section('title', 'Exit Interview Feedback Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 d-flex align-items-center">
                            <i class="fas fa-clipboard-list me-2"></i>
                            <span>Exit Interview Feedback Details</span>
                        </h3>
                        <div class="d-flex gap-2">
                            <a href="{{ route('exit-interview-feedbacks.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to List
                            </a>
                            
                            @if($exitInterviewFeedback->status === 'draft')
                                <a href="{{ route('exit-interview-feedbacks.edit', $exitInterviewFeedback) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i> Edit Feedback
                                </a>
                                
                                <a href="{{ route('exit-interview-feedbacks.destroy', $exitInterviewFeedback) }}" 
                                   class="btn btn-danger delete-record"
                                   data-table="exit-interview-feedbacks-table"
                                   data-isajax="false">
                                    <i class="fas fa-trash me-2"></i> Delete Feedback
                                </a>
                            @endif
                            
                            @if($exitInterviewFeedback->status === 'submitted' && (auth()->user()->hasRole('human_resource') || auth()->user()->hasRole('hr') || auth()->user()->can('review-exit-interviews') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')))
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                                    <i class="fas fa-check me-2"></i> Approve Feedback
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Basic Information Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-user me-2"></i>
                                <span>Basic Information</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Employee Name:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->employee->user->first_name }} {{ $exitInterviewFeedback->employee->user->last_name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Employee ID:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->employee_id_code }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Department:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->department ? $exitInterviewFeedback->department->department_name : 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Designation/Role:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->designation_role }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Date of Joining:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->date_of_joining ? $exitInterviewFeedback->date_of_joining->format('F d, Y') : 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Last Working Day:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->last_working_day->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Reporting Manager:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->reporting_manager ?: 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Status:</label>
                                        <p class="form-control-plaintext">{!! $exitInterviewFeedback->status_badge !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Created Date:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->created_at->format('F d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                @if($exitInterviewFeedback->reviewed_at)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Reviewed Date:</label>
                                        <p class="form-control-plaintext h5">{{ $exitInterviewFeedback->reviewed_at->format('F d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Reason for Leaving Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                <span>Reason for Leaving</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Reason for Leaving:</label>
                                <div class="alert alert-info">
                                    <strong>{{ $exitInterviewFeedback->reason_for_leaving_type ?: 'Not specified' }}</strong>
                                    @if($exitInterviewFeedback->reason_for_leaving_other)
                                        <br><small class="text-muted">{{ $exitInterviewFeedback->reason_for_leaving_other }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Satisfaction & Experience Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-star me-2"></i>
                                <span>Job Satisfaction & Experience</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Overall Job Satisfaction:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->overall_job_satisfaction ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->overall_job_satisfaction ?? 0 }}/5)</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Relationship with Supervisor:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->relationship_with_supervisor_rating ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->relationship_with_supervisor_rating ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->relationship_with_supervisor_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->relationship_with_supervisor_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Relationship with Colleagues:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->relationship_with_colleagues_rating ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->relationship_with_colleagues_rating ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->relationship_with_colleagues_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->relationship_with_colleagues_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Training & Development:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->training_development_rating ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->training_development_rating ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->training_development_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->training_development_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Workload & Work-Life Balance:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->workload_worklife_balance_rating ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->workload_worklife_balance_rating ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->workload_worklife_balance_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->workload_worklife_balance_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compensation & Benefits Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-dollar-sign me-2"></i>
                                <span>Compensation & Benefits</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Salary & Benefits Satisfaction:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->salary_benefits_satisfaction ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->salary_benefits_satisfaction ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->salary_benefits_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->salary_benefits_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Performance Appraisal Fairness:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->performance_appraisal_fairness ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->performance_appraisal_fairness ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->performance_appraisal_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->performance_appraisal_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Organizational Culture & Environment Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-building me-2"></i>
                                <span>Organizational Culture & Environment</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Work Environment:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->work_environment_rating ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->work_environment_rating ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->work_environment_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->work_environment_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Policies & Procedures:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->policies_procedures_rating ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->policies_procedures_rating ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->policies_procedures_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->policies_procedures_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Communication & Transparency:</label>
                                        <div class="rating-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="rating-star {{ $i <= ($exitInterviewFeedback->communication_transparency_rating ?? 0) ? 'active' : '' }}">{{ $i }}</span>
                                            @endfor
                                            <span class="ms-2 text-muted">({{ $exitInterviewFeedback->communication_transparency_rating ?? 0 }}/5)</span>
                                        </div>
                                        @if($exitInterviewFeedback->communication_transparency_comments)
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $exitInterviewFeedback->communication_transparency_comments }}</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($exitInterviewFeedback->staff_retention_suggestions)
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Staff Retention Suggestions:</label>
                                <div class="alert alert-light">
                                    {{ $exitInterviewFeedback->staff_retention_suggestions }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Exit Process & Feedback Section -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-clipboard-check me-2"></i>
                                <span>Exit Process & Feedback</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($exitInterviewFeedback->clearance_status)
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Clearance Status:</label>
                                <div class="row">
                                    @foreach(['library', 'accounts', 'it_equipment', 'hr'] as $status)
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       {{ in_array($status, $exitInterviewFeedback->clearance_status ?? []) ? 'checked' : '' }} disabled>
                                                <label class="form-check-label text-capitalize">
                                                    {{ str_replace('_', ' ', $status) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Would recommend company:</label>
                                        <div class="alert {{ $exitInterviewFeedback->would_recommend_company ? 'alert-success' : 'alert-danger' }}">
                                            <strong>{{ $exitInterviewFeedback->would_recommend_company ? 'Yes' : 'No' }}</strong>
                                            @if($exitInterviewFeedback->recommendation_comments)
                                                <br><small>{{ $exitInterviewFeedback->recommendation_comments }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-muted">Would rejoin in future:</label>
                                        <div class="alert {{ $exitInterviewFeedback->would_rejoin_future ? 'alert-success' : 'alert-warning' }}">
                                            <strong>{{ $exitInterviewFeedback->would_rejoin_future ? 'Yes' : 'No' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($exitInterviewFeedback->what_liked_most)
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">What did you like most about working here?</label>
                                <div class="alert alert-light">
                                    {{ $exitInterviewFeedback->what_liked_most }}
                                </div>
                            </div>
                            @endif

                            @if($exitInterviewFeedback->what_liked_least)
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">What did you like least about working here?</label>
                                <div class="alert alert-light">
                                    {{ $exitInterviewFeedback->what_liked_least }}
                                </div>
                            </div>
                            @endif

                            @if($exitInterviewFeedback->suggestions_for_improvement)
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Suggestions for Improvement:</label>
                                <div class="alert alert-light">
                                    {{ $exitInterviewFeedback->suggestions_for_improvement }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>


                    <!-- HR Notes Section -->
                    @if($exitInterviewFeedback->hr_notes)
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0 text-dark d-flex align-items-center">
                                <i class="fas fa-sticky-note me-2"></i>
                                <span>HR Notes</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-light">
                                {{ $exitInterviewFeedback->hr_notes }}
                            </div>
                            @if($exitInterviewFeedback->reviewedBy)
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Reviewed by:</strong> {{ $exitInterviewFeedback->reviewedBy->first_name }} {{ $exitInterviewFeedback->reviewedBy->last_name }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Reviewed on:</strong> {{ $exitInterviewFeedback->reviewed_at->format('F d, Y g:i A') }}
                                    </small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    @if($exitInterviewFeedback->status === 'draft')
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div></div>
                                <div>
                                    <form action="{{ route('exit-interview-feedbacks.submit', $exitInterviewFeedback) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg px-4"
                                                onclick="return confirm('Are you sure you want to submit this feedback? Once submitted, you cannot edit it.')">
                                            <i class="fas fa-paper-plane me-2"></i> Submit Feedback
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Design */
.card {
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
    padding: 1rem 1.5rem;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.font-weight-bold {
    font-weight: 600;
    color: #495057;
}

.text-muted {
    color: #6c757d !important;
}

/* Rating Display */
.rating-display {
    display: flex;
    align-items: center;
    gap: 5px;
}

.rating-star {
    display: inline-block;
    width: 25px;
    height: 25px;
    line-height: 25px;
    text-align: center;
    border: 1px solid #ced4da;
    border-radius: 50%;
    background-color: #f8f9fa;
    color: #6c757d;
    font-weight: bold;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}

.rating-star.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}


/* Alert Styling */
.alert {
    border-radius: 8px;
    border: none;
}

.alert-light {
    background-color: #f8f9fa;
    border-left: 4px solid #6c757d;
}

.alert-success {
    background-color: #d4edda;
    border-left: 4px solid #28a745;
}

.alert-warning {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
}

.alert-info {
    background-color: #d1ecf1;
    border-left: 4px solid #17a2b8;
}

.alert-danger {
    background-color: #f8d7da;
    border-left: 4px solid #dc3545;
}

/* Space between icon and header text */
.card-header .fa,
.card-header .fas,
.card-header .far,
.card-header .fal,
.card-header .fab {
    margin-right: 0.5rem !important;
    margin-left: 0 !important;
}

.d-flex.align-items-center .fa,
.d-flex.align-items-center .fas,
.d-flex.align-items-center .far,
.d-flex.align-items-center .fal,
.d-flex.align-items-center .fab {
    margin-right: 0.5rem !important;
    margin-left: 0 !important;
}

/* Button Consistency */
.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .rating-display {
        flex-wrap: wrap;
    }
    
    .rating-star {
        width: 25px;
        height: 25px;
        line-height: 25px;
        font-size: 12px;
    }
    
    .btn-lg {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Debug: Check if modal elements exist
    console.log('Modal button exists:', $('#approveModal').length > 0);
    console.log('Approve button exists:', $('[data-target="#approveModal"]').length > 0);
    
    // Debug: Check if Bootstrap modal is available
    console.log('Bootstrap modal available:', typeof $.fn.modal !== 'undefined');
    
    // Add click handler for debugging and manual modal trigger
    $('[data-target="#approveModal"]').on('click', function(e) {
        e.preventDefault();
        console.log('Approve button clicked');
        
        // Try both Bootstrap 4 and 5 syntax
        try {
            $('#approveModal').modal('show');
        } catch (error) {
            console.log('Bootstrap 5 modal failed, trying Bootstrap 4:', error);
            // Fallback for Bootstrap 4
            $('#approveModal').modal({show: true});
        }
    });
});
</script>

<!-- Approval Modal -->
@if($exitInterviewFeedback->status === 'submitted' && (auth()->user()->hasRole('human_resource') || auth()->user()->hasRole('hr') || auth()->user()->can('review-exit-interviews') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')))
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title d-flex align-items-center" id="approveModalLabel">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Approve Exit Interview Feedback</span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('exit-interview-feedbacks.mark-reviewed', $exitInterviewFeedback) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle me-2"></i>
                        <span>
                            <strong>Review Information:</strong> Please add your notes and mark this feedback as reviewed.
                        </span>
                    </div>
                    
                    <div class="form-group">
                        <label for="hr_notes" class="font-weight-bold">HR Notes <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('hr_notes') is-invalid @enderror" 
                                  id="hr_notes" 
                                  name="hr_notes" 
                                  rows="4" 
                                  placeholder="Enter your review notes and comments..."
                                  required>{{ old('hr_notes') }}</textarea>
                        @error('hr_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Employee:</label>
                                <p class="form-control-plaintext">{{ $exitInterviewFeedback->employee->user->first_name }} {{ $exitInterviewFeedback->employee->user->last_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Submitted Date:</label>
                                <p class="form-control-plaintext">{{ $exitInterviewFeedback->updated_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Mark as Reviewed
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection