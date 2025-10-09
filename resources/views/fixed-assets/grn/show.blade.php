@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed-assets.grn.index') }}">Goods Received Notes</a></li>
    <li class="breadcrumb-item active">View GRN</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Goods Received Note Details</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('fixed-assets.grn.print', $grn->id) }}" class="btn btn-info btn-label btn-sm" target="_blank">
                        <i class="ri-printer-fill label-icon align-middle fs-16 me-2"></i> Print
                    </a>
                    @if($grn->status == 'pending')
                        <a href="{{ route('fixed-assets.grn.edit', $grn->id) }}" class="btn btn-primary btn-label btn-sm">
                            <i class="ri-pencil-fill label-icon align-middle fs-16 me-2"></i> Edit
                        </a>
                        <a href="{{ route('fixed-assets.grn.verify', $grn->id) }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-check-double-line label-icon align-middle fs-16 me-2"></i> Verify
                        </a>
                        <button type="button" class="btn btn-danger btn-label btn-sm" data-bs-toggle="modal" data-bs-target="#rejectGrnModal">
                            <i class="ri-close-circle-line label-icon align-middle fs-16 me-2"></i> Reject
                        </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>GRN: {{ $grn->grn_number }}</h4>
                                <p class="mb-1">Received Date: {{ $grn->received_date ? date('Y-m-d', strtotime($grn->received_date)) : 'N/A' }}</p>
                                <p class="mb-1">Status: 
                                    @if($grn->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($grn->status == 'verified')
                                        <span class="badge bg-success">Verified</span>
                                    @elseif($grn->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </p>
                                @if($grn->purchase_order)
                                    <p class="mb-1">Based on Purchase Order: {{ $grn->purchase_order->po_number }}</p>
                                @endif
                                @if($grn->delivery_note_number)
                                    <p class="mb-1">Delivery Note: {{ $grn->delivery_note_number }}</p>
                                @endif
                                @if($grn->invoice_number)
                                    <p class="mb-1">Invoice Number: {{ $grn->invoice_number }}</p>
                                @endif
                            </div>
                            <div class="text-end">
                                <h5>Supplier</h5>
                                <p class="mb-1">{{ $grn->supplier->name ?? 'N/A' }}</p>
                                <p class="mb-1">{{ $grn->supplier->contact_person ?? '' }}</p>
                                <p class="mb-1">{{ $grn->supplier->email ?? '' }}</p>
                                <p class="mb-1">{{ $grn->supplier->phone ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5>Location Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Branch</th>
                                            <td>{{ $grn->branch->br_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $grn->department->department_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>User</th>
                                            <td>{{ $grn->user->first_name ?? '' }} {{ $grn->user->last_name ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5>Processing Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Received By</th>
                                            <td>{{ $grn->receivedBy->first_name ?? '' }} {{ $grn->receivedBy->last_name ?? 'N/A' }} ({{ $grn->created_at ? date('Y-m-d H:i', strtotime($grn->created_at)) : 'N/A' }})</td>
                                        </tr>
                                        @if($grn->status == 'verified')
                                        <tr>
                                            <th>Verified By</th>
                                            <td>{{ $grn->verifiedBy->first_name ?? '' }} {{ $grn->verifiedBy->last_name ?? 'N/A' }} ({{ $grn->verified_at ? date('Y-m-d H:i', strtotime($grn->verified_at)) : 'N/A' }})</td>
                                        </tr>
                                        @endif
                                        @if($grn->status == 'rejected')
                                        <tr>
                                            <th>Rejected By</th>
                                            <td>{{ $grn->rejectedBy->first_name ?? '' }} {{ $grn->rejectedBy->last_name ?? 'N/A' }} ({{ $grn->rejected_at ? date('Y-m-d H:i', strtotime($grn->rejected_at)) : 'N/A' }})</td>
                                        </tr>
                                        <tr>
                                            <th>Rejection Reason</th>
                                            <td>{{ $grn->rejection_reason ?? 'N/A' }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($grn->remarks)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Remarks</h5>
                        <div class="card">
                            <div class="card-body">
                                {{ $grn->remarks }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Items</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $items = json_decode($grn->items, true);
                                        $subtotal = 0;
                                    @endphp
                                    @if(is_array($items))
                                        @foreach($items as $index => $item)
                                            @php
                                                $quantity = $item['quantity'] ?? 0;
                                                $unitPrice = $item['unit_price'] ?? 0;
                                                $total = $quantity * $unitPrice;
                                                $subtotal += $total;
                                            @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item['name'] ?? 'N/A' }}</td>
                                                <td>{{ $item['description'] ?? 'N/A' }}</td>
                                                <td>
                                                    @php
                                                        $categoryId = $item['category_id'] ?? null;
                                                        $category = $categories->where('id', $categoryId)->first();
                                                    @endphp
                                                    {{ $category->name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $quantity }}</td>
                                                <td>{{ number_format($unitPrice, 2) }}</td>
                                                <td>{{ number_format($total, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-end">Total</th>
                                        <th>{{ number_format($grn->total_cost, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('fixed-assets.grn.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectGrnModal" tabindex="-1" aria-labelledby="rejectGrnModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('fixed-assets.grn.reject', $grn->id) }}" method="POST">
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
