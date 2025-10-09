@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Goods Received Notes</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Goods Received Notes</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('fixed-assets.grn.create') }}" class="btn btn-primary btn-label btn-sm">
                        <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Create GRN
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">GRN Number</th>
                                <th scope="col">Supplier</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Department</th>
                                <th scope="col">Received Date</th>
                                <th scope="col">Total Cost</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($grns as $grn)
                                <tr>
                                    <td>{{ $grn->grn_number }}</td>
                                    <td>{{ $grn->supplier->name ?? 'N/A' }}</td>
                                    <td>{{ $grn->branch->br_name ?? 'N/A' }}</td>
                                    <td>{{ $grn->department->department_name ?? 'N/A' }}</td>
                                    <td>{{ $grn->received_date ? date('Y-m-d', strtotime($grn->received_date)) : 'N/A' }}</td>
                                    <td>{{ number_format($grn->total_cost, 2) }}</td>
                                    <td>
                                        @if($grn->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($grn->status == 'verified')
                                            <span class="badge bg-success">Verified</span>
                                        @elseif($grn->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('fixed-assets.grn.show', $grn->id) }}" class="btn btn-sm btn-soft-primary" title="View">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                            
                                            @if($grn->status == 'pending')
                                                <a href="{{ route('fixed-assets.grn.edit', $grn->id) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                                <a href="{{ route('fixed-assets.grn.verify', $grn->id) }}" class="btn btn-sm btn-soft-success" title="Verify">
                                                    <i class="ri-check-double-line"></i>
                                                </a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger reject-grn" data-id="{{ $grn->id }}" title="Reject">
                                                    <i class="ri-close-circle-line"></i>
                                                </a>
                                                <form action="{{ route('fixed-assets.grn.destroy', $grn->id) }}" method="POST" class="delete-form d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="Delete">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('fixed-assets.grn.print', $grn->id) }}" class="btn btn-sm btn-soft-info" target="_blank" title="Print">
                                                <i class="ri-printer-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No goods received notes found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $grns->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectGrnModal" tabindex="-1" aria-labelledby="rejectGrnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectGrnForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectGrnModalLabel">Reject Goods Received Note</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });

        // Rejection modal
        const rejectGrnModal = document.getElementById('rejectGrnModal');
        const rejectGrnForm = document.getElementById('rejectGrnForm');
        
        document.querySelectorAll('.reject-grn').forEach(button => {
            button.addEventListener('click', function() {
                const grnId = this.getAttribute('data-id');
                rejectGrnForm.action = `{{ url('fixed-assets/grn') }}/${grnId}/reject`;
                
                const modal = new bootstrap.Modal(rejectGrnModal);
                modal.show();
            });
        });
        
        // Add tooltips to action buttons
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'top'
            });
        });
    });
</script>
@endpush
