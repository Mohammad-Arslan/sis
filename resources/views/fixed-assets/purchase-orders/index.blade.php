@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Purchase Orders</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Purchase Orders</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('fixed-assets.purchase-orders.create') }}" class="btn btn-primary btn-label btn-sm">
                        <i class="ri-add-fill label-icon align-middle fs-16 me-2"></i> Create New Order
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">PO Number</th>
                                <th scope="col">Supplier</th>
                                <th scope="col">Branch</th>
                                <th scope="col">Department</th>
                                <th scope="col">Total Cost</th>
                                <th scope="col">Status</th>
                                <th scope="col">Expected Delivery</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($purchaseOrders as $key => $order)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $order->po_number }}</td>
                                    <td>{{ $order->supplier->name ?? 'N/A' }}</td>
                                    <td>{{ $order->branch->br_name ?? 'N/A' }}</td>
                                    <td>{{ $order->department->department_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($order->total_cost, 2) }}</td>
                                    <td>
                                        @if($order->status == 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($order->status == 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($order->status == 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @elseif($order->status == 'completed')
                                            <span class="badge bg-info">Completed</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-dark">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $order->expected_delivery_date ? date('Y-m-d', strtotime($order->expected_delivery_date)) : 'N/A' }}</td>
                                    <td>{{ $order->created_at ? date('Y-m-d', strtotime($order->created_at)) : 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('fixed-assets.purchase-orders.show', $order->id) }}" class="btn btn-sm btn-soft-primary" title="View">
                                                <i class="ri-eye-fill"></i>
                                            </a>
                                            <a href="{{ route('fixed-assets.purchase-orders.print', $order->id) }}" class="btn btn-sm btn-soft-info" target="_blank" title="Print">
                                                <i class="ri-printer-fill"></i>
                                            </a>
                                            
                                            @if(in_array($order->status, ['draft', 'pending']))
                                                <a href="{{ route('fixed-assets.purchase-orders.edit', $order->id) }}" class="btn btn-sm btn-soft-warning" title="Edit">
                                                    <i class="ri-pencil-fill"></i>
                                                </a>
                                            @endif
                                            
                                            @if($order->status == 'pending')
                                                <a href="{{ route('fixed-assets.purchase-orders.approve', $order->id) }}" class="btn btn-sm btn-soft-success" title="Approve">
                                                    <i class="ri-check-fill"></i>
                                                </a>
                                                <a href="{{ route('fixed-assets.purchase-orders.reject', $order->id) }}" class="btn btn-sm btn-soft-danger" title="Reject">
                                                    <i class="ri-close-fill"></i>
                                                </a>
                                            @endif
                                            
                                            @if($order->status == 'approved')
                                                <a href="{{ route('fixed-assets.purchase-orders.receive', $order->id) }}" class="btn btn-sm btn-soft-success" title="Mark as Received">
                                                    <i class="ri-inbox-archive-fill"></i>
                                                </a>
                                            @endif
                                            
                                            @if(in_array($order->status, ['draft', 'pending']))
                                                <form action="{{ route('fixed-assets.purchase-orders.destroy', $order->id) }}" method="POST" class="delete-form d-inline">
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
                                    <td colspan="10" class="text-center">No purchase orders found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    {{ $purchaseOrders->links() }}
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
