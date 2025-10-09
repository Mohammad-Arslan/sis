@extends('layouts.master')

@section('title', 'Approve Employment Letter Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Employment Letter Request Approval</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Request Approval</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Pending Employment Letter Requests</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table id="employment-letter-approval-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Request Type</th>
                                        <th>Purpose</th>
                                        <th>Status</th>
                                        <th>Requested Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $request->employee->user->first_name }} {{ $request->employee->user->last_name }}</strong><br>
                                                    <small class="text-muted">ID: {{ $request->employee->employee_id }}</small><br>
                                                    <small class="text-muted">{{ $request->employee->designation->designation_name ?: 'N/A' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst(str_replace('_', ' ', $request->request_type)) }}
                                                </span>
                                            </td>
                                            <td>{{ strlen($request->purpose) > 50 ? substr($request->purpose, 0, 50) . '...' : $request->purpose }}</td>
                                            <td>{!! $request->status_badge !!}</td>
                                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('employment-letter-requests.show', $request) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($request->status === 'pending')
                                                        <button type="button" class="btn btn-sm btn-success" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#approveModal{{ $request->id }}"
                                                                title="Approve Request">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#rejectModal{{ $request->id }}"
                                                                title="Reject Request">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                    
                                                    @if($request->status === 'approved' && $request->letter_file_path)
                                                        <a href="{{ route('employment-letter-requests.download', $request) }}" 
                                                           class="btn btn-sm btn-outline-success"
                                                           title="Download Letter">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No employment letter requests found</h5>
                            <p class="text-muted">There are no employment letter requests to review at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@foreach($requests as $request)
    @if($request->status === 'pending')
        <div class="modal fade" id="approveModal{{ $request->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveModalLabel{{ $request->id }}">Approve Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('employment-letter-requests.approve', $request) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Are you sure you want to approve this employment letter request?</p>
                            <div class="alert alert-info">
                                <strong>Employee:</strong> {{ $request->employee->first_name }} {{ $request->employee->last_name }}<br>
                                <strong>Request Type:</strong> {{ ucfirst(str_replace('_', ' ', $request->request_type)) }}<br>
                                <strong>Purpose:</strong> {{ $request->purpose }}
                            </div>
                            <input type="hidden" name="action" value="approve">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Approve Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">Reject Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('employment-letter-requests.approve', $request) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Please provide a reason for rejecting this request:</p>
                            <div class="alert alert-warning">
                                <strong>Employee:</strong> {{ $request->employee->first_name }} {{ $request->employee->last_name }}<br>
                                <strong>Request Type:</strong> {{ ucfirst(str_replace('_', ' ', $request->request_type)) }}<br>
                                <strong>Purpose:</strong> {{ $request->purpose }}
                            </div>
                            <div class="mb-3">
                                <label for="rejection_reason{{ $request->id }}" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="rejection_reason{{ $request->id }}" 
                                          name="rejection_reason" rows="3" 
                                          placeholder="Please provide a reason for rejection..." required></textarea>
                            </div>
                            <input type="hidden" name="action" value="reject">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script>
$(document).ready(function() {
    // Override the default delete success behavior for our approval table
    $(document).on('click', '.delete-record[data-table="employment-letter-approval-table"]', function(e) {
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
                        if ($('#employment-letter-approval-table tbody tr').length === 0) {
                            $('#employment-letter-approval-table').closest('.table-responsive').html(
                                '<div class="text-center py-5">' +
                                '<i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>' +
                                '<h5 class="text-muted">No employment letter requests found</h5>' +
                                '<p class="text-muted">There are no employment letter requests to review at the moment.</p>' +
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
