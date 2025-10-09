@extends('layouts.master')

@section('title', 'Review Exit Interview Feedbacks')

@section('content')
<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-top: none;
}

.table td {
    vertical-align: middle;
    color: #495057;
}

.table td strong {
    color: #212529;
}

.table td small {
    color: #6c757d;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-info {
    background-color: #17a2b8 !important;
    color: white !important;
}

.rating-stars {
    font-size: 0.9rem;
}

.employee-info {
    line-height: 1.4;
}

.employee-info strong {
    color: #495057;
}

.employee-info small {
    color: #6c757d;
}

.department-badge {
    background-color: #17a2b8 !important;
    color: white !important;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
}

.actions-column {
    white-space: nowrap;
}

.actions-column .badge {
    background-color: #28a745 !important;
    color: white !important;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
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

/* Ensure all text is readable */
.table td, .table th {
    color: #495057 !important;
}

.table td strong, .table th strong {
    color: #212529 !important;
}

.table td small, .table th small {
    color: #6c757d !important;
}

/* Override any light text colors */
.text-muted {
    color: #6c757d !important;
}

.reason-leaving {
    max-width: 200px;
}

.actions-column {
    white-space: nowrap;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Review Exit Interview Feedbacks</h4>
                        <a href="{{ route('exit-interview-feedbacks.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to My Feedbacks
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($feedbacks->count() > 0)
                        <div class="table-responsive">
                            <table id="exit-interview-review-table" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Last Working Day</th>
                                        <th>Reason for Leaving</th>
                                        <th>Overall Rating</th>
                                        <th>Status</th>
                                        <th>Submitted Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feedbacks as $feedback)
                                        <tr>
                                            <td>
                                                <div class="employee-info">
                                                    <strong>{{ $feedback->employee->user->first_name }} {{ $feedback->employee->user->last_name }}</strong><br>
                                                    <small class="text-muted">ID: {{ $feedback->employee_id_code ?: $feedback->employee->employee_id }}</small><br>
                                                    <small class="text-muted">{{ $feedback->designation_role ?: ($feedback->employee->designation->designation_name ?? 'N/A') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-info department-badge">
                                                    {{ $feedback->department->department_name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $feedback->last_working_day->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $feedback->last_working_day->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="reason-leaving">
                                                    <strong>{{ $feedback->reason_for_leaving_type ?? 'Not specified' }}</strong>
                                                    @if($feedback->reason_for_leaving_other)
                                                        <br><small class="text-muted">{{ Str::limit($feedback->reason_for_leaving_other, 30) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($feedback->overall_job_satisfaction)
                                                    <div class="d-flex align-items-center rating-stars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $feedback->overall_job_satisfaction ? 'text-warning' : 'text-muted' }}"></i>
                                                        @endfor
                                                        <span class="ms-1 text-muted">({{ $feedback->overall_job_satisfaction }}/5)</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Not rated</span>
                                                @endif
                                            </td>
                                            <td>{!! $feedback->status_badge !!}</td>
                                            <td>
                                                <strong>{{ $feedback->created_at->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $feedback->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td class="actions-column">
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('exit-interview-feedbacks.show', $feedback) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($feedback->status === 'submitted')
                                                        <button type="button" class="btn btn-sm btn-success" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#reviewModal{{ $feedback->id }}"
                                                                title="Mark as Reviewed">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($feedback->status === 'reviewed')
                                                        <span class="badge badge-success" style="background-color: #28a745 !important; color: white !important; font-weight: 500;">
                                                            <i class="fas fa-check-circle me-1"></i>Reviewed
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $feedbacks->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No exit interview feedbacks found</h5>
                            <p class="text-muted">There are no exit interview feedbacks to review at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Modals -->
@foreach($feedbacks as $feedback)
    @if($feedback->status === 'submitted')
        <div class="modal fade" id="reviewModal{{ $feedback->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $feedback->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reviewModalLabel{{ $feedback->id }}">Mark as Reviewed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('exit-interview-feedbacks.mark-reviewed', $feedback) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Employee:</label>
                                <p class="form-control-plaintext">{{ $feedback->employee->user->first_name }} {{ $feedback->employee->user->last_name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Reason for Leaving:</label>
                                <div class="card">
                                    <div class="card-body">
                                        <p class="mb-0">
                                            <strong>{{ $feedback->reason_for_leaving_type ?? 'Not specified' }}</strong>
                                            @if($feedback->reason_for_leaving_other)
                                                <br><small class="text-muted">{{ $feedback->reason_for_leaving_other }}</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="hr_notes_{{ $feedback->id }}" class="form-label">HR Notes</label>
                                <textarea class="form-control" 
                                          id="hr_notes_{{ $feedback->id }}" 
                                          name="hr_notes" 
                                          rows="4" 
                                          placeholder="Add any notes or comments about this feedback..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Mark as Reviewed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach
@endsection

