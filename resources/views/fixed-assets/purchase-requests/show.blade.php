@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed-assets.purchase-requests.index') }}">Purchase Requests</a></li>
    <li class="breadcrumb-item active">View Purchase Request</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Purchase Request Details</h4>
                <div class="flex-shrink-0">
                    @if($purchaseRequest->status == 'pending')
                        <a href="{{ route('fixed-assets.purchase-requests.edit', $purchaseRequest->id) }}" class="btn btn-primary btn-label btn-sm">
                            <i class="ri-pencil-fill label-icon align-middle fs-16 me-2"></i> Edit
                        </a>
                        <a href="{{ route('fixed-assets.purchase-requests.approve', $purchaseRequest->id) }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-check-fill label-icon align-middle fs-16 me-2"></i> Approve
                        </a>
                        <a href="{{ route('fixed-assets.purchase-requests.reject', $purchaseRequest->id) }}" class="btn btn-danger btn-label btn-sm">
                            <i class="ri-close-fill label-icon align-middle fs-16 me-2"></i> Reject
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h5>General Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Status</th>
                                            <td>
                                                @if($purchaseRequest->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif($purchaseRequest->status == 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($purchaseRequest->status == 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Supplier</th>
                                            <td>{{ $purchaseRequest->supplier->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Branch</th>
                                            <td>{{ $purchaseRequest->branch->br_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $purchaseRequest->department->department_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Required Date</th>
                                            <td>{{ $purchaseRequest->required_date ? date('Y-m-d', strtotime($purchaseRequest->required_date)) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Budget Code</th>
                                            <td>{{ $purchaseRequest->budget_code ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Total Cost</th>
                                            <td>{{ number_format($purchaseRequest->total_cost, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <h5>Request Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="30%">Requested By</th>
                                            <td>{{ $purchaseRequest->requestedBy->first_name ?? '' }} {{ $purchaseRequest->requestedBy->last_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $purchaseRequest->created_at ? date('Y-m-d H:i', strtotime($purchaseRequest->created_at)) : 'N/A' }}</td>
                                        </tr>
                                        @if($purchaseRequest->status == 'approved')
                                        <tr>
                                            <th>Approved By</th>
                                            <td>
                                                @if($purchaseRequest->approvedBy)
                                                    {{ $purchaseRequest->approvedBy->first_name ?? '' }} {{ $purchaseRequest->approvedBy->last_name ?? '' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Approved At</th>
                                            <td>{{ $purchaseRequest->approved_at ? date('Y-m-d H:i', strtotime($purchaseRequest->approved_at)) : 'N/A' }}</td>
                                        </tr>
                                        @endif
                                        @if($purchaseRequest->status == 'rejected')
                                        <tr>
                                            <th>Rejected By</th>
                                            <td>
                                                @if($purchaseRequest->rejectedBy)
                                                    {{ $purchaseRequest->rejectedBy->first_name ?? '' }} {{ $purchaseRequest->rejectedBy->last_name ?? '' }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Rejected At</th>
                                            <td>{{ $purchaseRequest->rejected_at ? date('Y-m-d H:i', strtotime($purchaseRequest->rejected_at)) : 'N/A' }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Justification</h5>
                        <div class="card">
                            <div class="card-body">
                                {{ $purchaseRequest->justification ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>

                @if($purchaseRequest->remarks)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Remarks</h5>
                        <div class="card">
                            <div class="card-body">
                                {{ $purchaseRequest->remarks }}
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
                                        $items = json_decode($purchaseRequest->items, true);
                                        $grandTotal = 0;
                                    @endphp
                                    @if(is_array($items))
                                        @foreach($items as $index => $item)
                                            @php
                                                $quantity = $item['quantity'] ?? 0;
                                                $unitPrice = $item['unit_price'] ?? 0;
                                                $total = $quantity * $unitPrice;
                                                $grandTotal += $total;
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
                                        <th colspan="6" class="text-end">Grand Total</th>
                                        <th>{{ number_format($grandTotal, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('fixed-assets.purchase-requests.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
