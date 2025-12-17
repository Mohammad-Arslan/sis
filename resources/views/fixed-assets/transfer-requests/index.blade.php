@extends('layouts.master')
@section('content')
@push('header_scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .transfer-icon {
        width: 48px;
        height: 48px;
        background: #f3f4f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    
    .transfer-icon svg {
        width: 24px;
        height: 24px;
        color: #6b7280;
    }
    
    .search-input {
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 12px 16px 12px 44px;
        width: 100%;
        font-size: 14px;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .search-wrapper {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-primary:hover {
        background: #2563eb;
    }
    
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
    
    .empty-state {
        text-align: center;
        padding: 48px 24px;
    }
    
    .empty-state-text {
        color: #6b7280;
        margin-bottom: 8px;
    }
    
    .empty-state-suggestion {
        color: #9ca3af;
        margin-bottom: 24px;
    }
</style>
@endpush

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-2">Transfer Requests</h1>
                    <p class="text-muted">Manage asset transfer requests between branches and departments.</p>
                </div>
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('fixed-assets.transfer-requests.create') }}'">
                    <i class="fas fa-plus me-2"></i>New Transfer Request
                </button>
            </div>

            <!-- Search & Filter Section -->
            <div class="section-card">
                <h5 class="section-title">Search & Filter</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" id="searchTransfers" placeholder="Search by title or transfer number...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="branchFilter">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Transfer Requests Section -->
            <div class="section-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="section-title mb-0">Transfer Requests (<span id="transfersCount">{{ $transferRequests->count() }}</span>)</h5>
                </div>
                <div id="transfersList">
                    @if($transferRequests->count() > 0)
                        <!-- Transfer Requests List -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Transfer #</th>
                                        <th>Title</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Requested By</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transferRequests as $transfer)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">{{ $transfer->transfer_number }}</span>
                                            </td>
                                            <td>
                                                <strong>{{ $transfer->title }}</strong>
                                                @if($transfer->description)
                                                    <br><small class="text-muted">{{ Str::limit($transfer->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $transfer->sourceBranch->br_name }}
                                                @if($transfer->sourceDepartment)
                                                    <br><small class="text-muted">{{ $transfer->sourceDepartment->dept_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $transfer->destinationBranch->br_name }}
                                                @if($transfer->destinationDepartment)
                                                    <br><small class="text-muted">{{ $transfer->destinationDepartment->dept_name }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $transfer->requestedByUser->first_name }} {{ $transfer->requestedByUser->last_name }}
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = [
                                                        'pending' => 'bg-warning',
                                                        'approved' => 'bg-info',
                                                        'rejected' => 'bg-danger',
                                                        'completed' => 'bg-success'
                                                    ][$transfer->status];
                                                @endphp
                                                <span class="badge {{ $statusClass }}">{{ ucfirst($transfer->status) }}</span>
                                            </td>
                                            <td>
                                                {{ $transfer->created_at->format('M d, Y') }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('fixed-assets.transfer-requests.show', $transfer->id) }}" 
                                                        class="btn btn-sm btn-outline-primary" 
                                                        title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($transfer->status === 'pending')
                                                        <a href="{{ route('fixed-assets.transfer-requests.edit', $transfer->id) }}" 
                                                            class="btn btn-sm btn-outline-secondary" 
                                                            title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" 
                                                            class="btn btn-sm btn-outline-danger delete-transfer" 
                                                            data-id="{{ $transfer->id }}"
                                                            data-title="{{ $transfer->title }}"
                                                            title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="empty-state">
                            <div class="transfer-icon">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
                                </svg>
                            </div>
                            <h5 class="empty-state-text">No transfer requests found</h5>
                            <p class="empty-state-suggestion">Get started by creating a new transfer request.</p>
                            <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('fixed-assets.transfer-requests.create') }}'">
                                <i class="fas fa-plus me-2"></i>New Transfer Request
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('footer_scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchTransfers').on('input', function() {
        filterTransfers();
    });

    // Filter functionality
    $('#statusFilter, #branchFilter').on('change', function() {
        filterTransfers();
    });

    function filterTransfers() {
        const searchTerm = $('#searchTransfers').val().toLowerCase();
        const statusFilter = $('#statusFilter').val();
        const branchFilter = $('#branchFilter').val();
        
        let visibleCount = 0;
        
        $('tbody tr').each(function() {
            const $row = $(this);
            const title = $row.find('td:nth-child(2) strong').text().toLowerCase();
            const transferNumber = $row.find('td:nth-child(1) .badge').text().toLowerCase();
            const status = $row.find('td:nth-child(6) .badge').text().toLowerCase();
            const sourceBranch = $row.find('td:nth-child(3)').text().toLowerCase();
            const destinationBranch = $row.find('td:nth-child(4)').text().toLowerCase();
            
            let showRow = true;
            
            // Search filter
            if (searchTerm && !title.includes(searchTerm) && !transferNumber.includes(searchTerm)) {
                showRow = false;
            }
            
            // Status filter
            if (statusFilter && !status.includes(statusFilter.toLowerCase())) {
                showRow = false;
            }
            
            // Branch filter
            if (branchFilter && !sourceBranch.includes(branchFilter) && !destinationBranch.includes(branchFilter)) {
                showRow = false;
            }
            
            if (showRow) {
                $row.show();
                visibleCount++;
            } else {
                $row.hide();
            }
        });
        
        // Update count
        $('#transfersCount').text(visibleCount);
        
        // Show/hide empty state
        if (visibleCount === 0) {
            if ($('.empty-state').length === 0) {
                $('tbody').after(`
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="empty-state">
                                <div class="transfer-icon">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
                                    </svg>
                                </div>
                                <h5 class="empty-state-text">No transfer requests found</h5>
                                <p class="empty-state-suggestion">Try adjusting your search or filters.</p>
                            </div>
                        </td>
                    </tr>
                `);
            }
        } else {
            $('.empty-state').closest('tr').remove();
        }
    }

    // Delete transfer functionality
    $('.delete-transfer').on('click', function() {
        const transferId = $(this).data('id');
        const transferTitle = $(this).data('title');
        const deleteBtn = $(this);
        
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to delete the transfer request "${transferTitle}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                deleteBtn.prop('disabled', true);
                deleteBtn.html('<i class="fas fa-spinner fa-spin"></i>');
                
                // Make AJAX call to delete transfer request
                $.ajax({
                    url: `{{ route('fixed-assets.transfer-requests.destroy', '') }}/${transferId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Remove the row from the table
                                deleteBtn.closest('tr').fadeOut(300, function() {
                                    $(this).remove();
                                    // Update count
                                    const currentCount = parseInt($('#transfersCount').text());
                                    $('#transfersCount').text(currentCount - 1);
                                    
                                    // Show empty state if no transfers left
                                    if ($('tbody tr').length === 0) {
                                        $('tbody').append(`
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <div class="transfer-icon">
                                                            <svg fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z"/>
                                                            </svg>
                                                        </div>
                                                        <h5 class="empty-state-text">No transfer requests found</h5>
                                                        <p class="empty-state-suggestion">Try creating a new transfer request.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        `);
                                    }
                                });
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred while deleting the transfer request.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage,
                            confirmButtonText: 'OK'
                        });
                    },
                    complete: function() {
                        // Reset button state
                        deleteBtn.prop('disabled', false);
                        deleteBtn.html('<i class="fas fa-trash"></i>');
                    }
                });
            }
        });
    });
});
</script>
@endpush

@endsection
