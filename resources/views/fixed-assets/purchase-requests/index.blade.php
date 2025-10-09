@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Purchase Requests</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Purchase Requests</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('fixed-assets.purchase-requests.create') }}" class="btn btn-primary btn-label btn-sm">
                        <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create New Request
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Supplier</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Department</th>
                                <th scope="col">Total Cost</th>
                                <th scope="col">Status</th>
                                <th scope="col">Required Date</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($purchaseRequests as $key => $request)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $request->supplier->name ?? 'N/A' }}</td>
                                    <td>{{ $request->branch->br_name ?? 'N/A' }}</td>
                                    <td>{{ $request->department->department_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($request->total_cost, 2) }}</td>
                                    <td>
                                        @if($request->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($request->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($request->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->required_date ? date('Y-m-d', strtotime($request->required_date)) : 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('fixed-assets.purchase-requests.show', $request->id) }}" class="btn btn-sm btn-soft-primary" title="View">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                            @if($request->status == 'pending')
                                                <a href="{{ route('fixed-assets.purchase-requests.edit', $request->id) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                                <a href="{{ route('fixed-assets.purchase-requests.approve', $request->id) }}" class="btn btn-sm btn-soft-success" title="Approve">
                                                    <i class="ri-check-fill"></i>
                                                </a>
                                                <a href="{{ route('fixed-assets.purchase-requests.reject', $request->id) }}" class="btn btn-sm btn-soft-danger" title="Reject">
                                                    <i class="ri-close-fill"></i>
                                                </a>
                                                <form action="{{ route('fixed-assets.purchase-requests.destroy', $request->id) }}" method="POST" class="delete-form d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-soft-danger" title="Delete">
                                                        <i class="ri-delete-bin-fill"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No purchase requests found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $purchaseRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation using SweetAlert
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
