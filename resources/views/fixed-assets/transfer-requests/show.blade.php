@extends('layouts.master')
@section('content')
@push('header_scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .section-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 16px;
    }

    .info-group {
        margin-bottom: 1rem;
    }

    .info-label {
        font-weight: 500;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: #111827;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #3b82f6;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-date {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .timeline-title {
        font-weight: 500;
        color: #111827;
        margin: 0.25rem 0;
    }

    .timeline-description {
        color: #6b7280;
        font-size: 0.875rem;
    }
</style>
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">Transfer Request Details</h1>
                    <p class="text-muted">View and manage transfer request information.</p>
                </div>
                <div>
                    @if($transferRequest->status === 'pending')
                        <button type="button" class="btn btn-success me-2" id="approveBtn">
                            <i class="fas fa-check me-2"></i>Approve
                        </button>
                        <button type="button" class="btn btn-danger me-2" id="rejectBtn">
                            <i class="fas fa-times me-2"></i>Reject
                        </button>
                        <a href="{{ route('fixed-assets.transfer-requests.edit', $transferRequest->id) }}" class="btn btn-primary me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    @elseif($transferRequest->status === 'approved')
                        <button type="button" class="btn btn-success me-2" id="receiveBtn">
                            <i class="fas fa-box me-2"></i>Mark as Received
                        </button>
                    @endif
                    <a href="{{ route('fixed-assets.transfer-requests.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Basic Information -->
                    <div class="section-card">
                        <h5 class="section-title">Basic Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Transfer Number</div>
                                    <div class="info-value">
                                        <span class="badge bg-secondary">{{ $transferRequest->transfer_number }}</span>
                                    </div>
                                </div>
                                <div class="info-group">
                                    <div class="info-label">Title</div>
                                    <div class="info-value">{{ $transferRequest->title }}</div>
                                </div>
                                <div class="info-group">
                                    <div class="info-label">Status</div>
                                    <div class="info-value">
                                        @php
                                            $statusClass = [
                                                'pending' => 'bg-warning',
                                                'approved' => 'bg-info',
                                                'rejected' => 'bg-danger',
                                                'completed' => 'bg-success'
                                            ][$transferRequest->status];
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ ucfirst($transferRequest->status) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-group">
                                    <div class="info-label">Requested By</div>
                                    <div class="info-value">{{ $transferRequest->requestedByUser->first_name }} {{ $transferRequest->requestedByUser->last_name }}</div>
                                </div>
                                <div class="info-group">
                                    <div class="info-label">Request Date</div>
                                    <div class="info-value">{{ $transferRequest->created_at->format('M d, Y H:i A') }}</div>
                                </div>
                                @if($transferRequest->status !== 'pending')
                                    <div class="info-group">
                                        <div class="info-label">
                                            {{ $transferRequest->status === 'rejected' ? 'Rejected' : 'Approved' }} By
                                        </div>
                                        <div class="info-value">
                                            {{ $transferRequest->approvedByUser->first_name }} {{ $transferRequest->approvedByUser->last_name }}
                                            <br>
                                            <small class="text-muted">{{ $transferRequest->approved_at->format('M d, Y H:i A') }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="info-group">
                                    <div class="info-label">Description</div>
                                    <div class="info-value">{{ $transferRequest->description ?: 'No description provided.' }}</div>
                                </div>
                                <div class="info-group">
                                    <div class="info-label">Reason for Transfer</div>
                                    <div class="info-value">{{ $transferRequest->reason }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Source and Destination -->
                    <div class="section-card">
                        <h5 class="section-title">Source and Destination</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Source</h6>
                                <div class="info-group">
                                    <div class="info-label">Branch</div>
                                    <div class="info-value">{{ $transferRequest->sourceBranch->br_name }}</div>
                                </div>
                                @if($transferRequest->sourceDepartment)
                                    <div class="info-group">
                                        <div class="info-label">Department</div>
                                        <div class="info-value">{{ $transferRequest->sourceDepartment->dept_name }}</div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3">Destination</h6>
                                <div class="info-group">
                                    <div class="info-label">Branch</div>
                                    <div class="info-value">{{ $transferRequest->destinationBranch->br_name }}</div>
                                </div>
                                @if($transferRequest->destinationDepartment)
                                    <div class="info-group">
                                        <div class="info-label">Department</div>
                                        <div class="info-value">{{ $transferRequest->destinationDepartment->dept_name }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Assets -->
                    <div class="section-card">
                        <h5 class="section-title">Assets</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Asset</th>
                                        <th>Quantity</th>
                                        <th>Condition</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transferRequest->transferItems as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->asset->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $item->asset->asset_tag }}</small>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>
                                                @if($item->condition)
                                                    <span class="badge bg-info">{{ ucfirst($item->condition) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->notes ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Status Timeline -->
                    <div class="section-card">
                        <h5 class="section-title">Status Timeline</h5>
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-date">{{ $transferRequest->created_at->format('M d, Y H:i A') }}</div>
                                <div class="timeline-title">Transfer Request Created</div>
                                <div class="timeline-description">
                                    Created by {{ $transferRequest->requestedByUser->first_name }} {{ $transferRequest->requestedByUser->last_name }}
                                </div>
                            </div>

                            @if($transferRequest->status !== 'pending')
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ $transferRequest->approved_at->format('M d, Y H:i A') }}</div>
                                    <div class="timeline-title">
                                        Request {{ $transferRequest->status === 'rejected' ? 'Rejected' : 'Approved' }}
                                    </div>
                                    <div class="timeline-description">
                                        {{ $transferRequest->status === 'rejected' ? 'Rejected' : 'Approved' }} by 
                                        {{ $transferRequest->approvedByUser->first_name }} {{ $transferRequest->approvedByUser->last_name }}
                                    </div>
                                </div>
                            @endif

                            @if($transferRequest->status === 'completed')
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ $transferRequest->received_at->format('M d, Y H:i A') }}</div>
                                    <div class="timeline-title">Transfer Completed</div>
                                    <div class="timeline-description">
                                        Received by {{ $transferRequest->receivedByUser->first_name }} {{ $transferRequest->receivedByUser->last_name }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('footer_scripts')
<script>
$(document).ready(function() {
    // Approve transfer request
    $('#approveBtn').on('click', function() {
        Swal.fire({
            title: 'Approve Transfer Request',
            text: 'Are you sure you want to approve this transfer request?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, approve it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('fixed-assets.transfer-requests.approve', $transferRequest->id) }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while approving the transfer request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Reject transfer request
    $('#rejectBtn').on('click', function() {
        Swal.fire({
            title: 'Reject Transfer Request',
            text: 'Are you sure you want to reject this transfer request?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reject it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('fixed-assets.transfer-requests.reject', $transferRequest->id) }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Rejected!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while rejecting the transfer request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });

    // Mark as received
    $('#receiveBtn').on('click', function() {
        Swal.fire({
            title: 'Mark as Received',
            text: 'Are you sure you want to mark this transfer as received? This will update the asset locations.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, mark as received!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('fixed-assets.transfer-requests.receive', $transferRequest->id) }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Received!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while marking the transfer as received.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush

@endsection
