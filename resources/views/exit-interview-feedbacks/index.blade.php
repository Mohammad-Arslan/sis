@extends('layouts.master')

@section('title', 'My Exit Interview Feedbacks')

@section('content')
<style>
.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    border-top: none;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-info {
    background-color: #17a2b8 !important;
    color: white !important;
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

.rating-stars {
    font-size: 0.9rem;
}

.actions-column {
    white-space: nowrap;
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
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">My Exit Interview Feedbacks</h4>
                        <a href="{{ route('exit-interview-feedbacks.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> New Feedback
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
                            <table id="exit-interview-feedbacks-table" class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Last Working Day</th>
                                        <th>Reason for Leaving</th>
                                        <th>Overall Rating</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Reviewed Date</th>
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
                                                <span class="badge badge-info">
                                                    {{ $feedback->department->department_name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $feedback->last_working_day->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $feedback->last_working_day->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div>
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
                                            <td>
                                                @if($feedback->reviewed_at)
                                                    <strong>{{ $feedback->reviewed_at->format('M d, Y') }}</strong><br>
                                                    <small class="text-muted">{{ $feedback->reviewed_at->format('h:i A') }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="actions-column">
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('exit-interview-feedbacks.show', $feedback) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($feedback->status === 'draft')
                                                        <a href="{{ route('exit-interview-feedbacks.edit', $feedback) }}" 
                                                           class="btn btn-sm btn-outline-warning"
                                                           title="Edit Feedback">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        
                                                        <a href="{{ route('exit-interview-feedbacks.destroy', $feedback) }}" 
                                                           class="btn btn-sm btn-outline-danger delete-record"
                                                           data-table="exit-interview-feedbacks-table"
                                                           data-isajax="false"
                                                           title="Delete Feedback">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    @if($feedback->status === 'draft')
                                                        <form action="{{ route('exit-interview-feedbacks.submit', $feedback) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                    title="Submit Feedback"
                                                                    onclick="return confirm('Are you sure you want to submit this feedback? Once submitted, you cannot edit it.')">
                                                                <i class="fas fa-paper-plane"></i>
                                                            </button>
                                                        </form>
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
                            <p class="text-muted">You haven't submitted any exit interview feedbacks yet.</p>
                            <a href="{{ route('exit-interview-feedbacks.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Your First Feedback
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Override the default delete success behavior for our table
    $(document).on('click', '.delete-record[data-table="exit-interview-feedbacks-table"]', function(e) {
        e.preventDefault();
        
        var url = $(this).attr('href');
        var this_var = $(this);
        
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>Are you sure?</h4>' +
                '<p class="mx-4 mb-0 text-muted">Are you Sure You want to Delete this Record ?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Delete It!',
            cancelButtonClass: 'btn btn-danger w-xs mb-1',
            buttonsStyling: false,
            showCloseButton: true
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    cache: false,
                    success: function(data) {
                        // Remove the table row
                        this_var.closest('tr').remove();
                        
                        // Check if table is empty and show message
                        if ($('#exit-interview-feedbacks-table tbody tr').length === 0) {
                            $('#exit-interview-feedbacks-table').closest('.table-responsive').html(
                                '<div class="text-center py-5">' +
                                '<i class="fas fa-comments fa-3x text-muted mb-3"></i>' +
                                '<h5 class="text-muted">No exit interview feedbacks found</h5>' +
                                '<p class="text-muted">You haven\'t submitted any exit interview feedbacks yet.</p>' +
                                '<a href="{{ route("exit-interview-feedbacks.create") }}" class="btn btn-primary">' +
                                '<i class="fas fa-plus"></i> Create Your First Feedback</a>' +
                                '</div>'
                            );
                        }
                        
                        // Show success message
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success !</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + (data.message || 'Record has been successfully deleted.') + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    },
                    error: function(xhr) {
                        var message = 'An error occurred while deleting the record.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Error !</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + message + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    }
                });
            }
        });
    });
});
</script>
@endsection
