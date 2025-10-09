@extends('layouts.master')

@section('content')
<x-breadcrumb>
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('fixed-assets.purchase-orders.index') }}">Purchase Orders</a></li>
    <li class="breadcrumb-item active">View Purchase Order</li>
</x-breadcrumb>

<div class="row">
    <div class="col-lg-12">
        @include('components.flash_message')
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Purchase Order Details</h4>
                <div class="flex-shrink-0">
                    <a href="{{ route('fixed-assets.purchase-orders.print', $purchaseOrder->id) }}" class="btn btn-info btn-label btn-sm" target="_blank">
                        <i class="ri-printer-fill label-icon align-middle fs-16 me-2"></i> Print
                    </a>
                    @if(in_array($purchaseOrder->status, ['draft', 'pending']))
                        <a href="{{ route('fixed-assets.purchase-orders.edit', $purchaseOrder->id) }}" class="btn btn-primary btn-label btn-sm">
                            <i class="ri-pencil-fill label-icon align-middle fs-16 me-2"></i> Edit
                        </a>
                    @endif
                    @if($purchaseOrder->status == 'pending')
                        <a href="{{ route('fixed-assets.purchase-orders.approve', $purchaseOrder->id) }}" class="btn btn-success btn-label btn-sm">
                            <i class="ri-check-fill label-icon align-middle fs-16 me-2"></i> Approve
                        </a>
                        <a href="{{ route('fixed-assets.purchase-orders.reject', $purchaseOrder->id) }}" class="btn btn-danger btn-label btn-sm">
                            <i class="ri-close-fill label-icon align-middle fs-16 me-2"></i> Reject
                        </a>
                    @endif
                    @if($purchaseOrder->status == 'approved')
                        <a href="{{ route('fixed-assets.purchase-orders.receive', $purchaseOrder->id) }}" class="btn btn-warning btn-label btn-sm">
                            <i class="ri-inbox-archive-fill label-icon align-middle fs-16 me-2"></i> Mark as Received
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4>Purchase Order: {{ $purchaseOrder->po_number }}</h4>
                                <p class="mb-1">Date: {{ $purchaseOrder->created_at->format('Y-m-d') }}</p>
                                <p class="mb-1">Status: 
                                    @if($purchaseOrder->status == 'draft')
                                        <span class="badge bg-secondary">Draft</span>
                                    @elseif($purchaseOrder->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($purchaseOrder->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($purchaseOrder->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($purchaseOrder->status == 'completed')
                                        <span class="badge bg-info">Completed</span>
                                    @elseif($purchaseOrder->status == 'cancelled')
                                        <span class="badge bg-dark">Cancelled</span>
                                    @endif
                                </p>
                                @if($purchaseOrder->purchaseRequest)
                                    <p class="mb-1">Based on Purchase Request: PR-{{ $purchaseOrder->purchaseRequest->id }}</p>
                                @endif
                            </div>
                            <div class="text-end">
                                <h5>Supplier</h5>
                                <p class="mb-1">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</p>
                                <p class="mb-1">{{ $purchaseOrder->supplier->contact_person ?? '' }}</p>
                                <p class="mb-1">{{ $purchaseOrder->supplier->email ?? '' }}</p>
                                <p class="mb-1">{{ $purchaseOrder->supplier->phone ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5>Shipping Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Expected Delivery Date</th>
                                            <td>{{ $purchaseOrder->expected_delivery_date ? date('Y-m-d', strtotime($purchaseOrder->expected_delivery_date)) : 'N/A' }}</td>
                                        </tr>
                                        @if($purchaseOrder->delivery_date)
                                        <tr>
                                            <th>Actual Delivery Date</th>
                                            <td>{{ date('Y-m-d', strtotime($purchaseOrder->delivery_date)) }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Shipping Method</th>
                                            <td>{{ $purchaseOrder->shipping_method ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping Terms</th>
                                            <td>{{ $purchaseOrder->shipping_terms ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Delivery Address</th>
                                            <td>{{ $purchaseOrder->delivery_address ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h5>Order Information</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <th width="40%">Branch</th>
                                            <td>{{ $purchaseOrder->branch->br_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Department</th>
                                            <td>{{ $purchaseOrder->department->department_name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Terms</th>
                                            <td>{{ $purchaseOrder->payment_terms ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Billing Address</th>
                                            <td>{{ $purchaseOrder->billing_address ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                @if($purchaseOrder->remarks)
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Remarks</h5>
                        <div class="card">
                            <div class="card-body">
                                {{ $purchaseOrder->remarks }}
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
                                        $items = json_decode($purchaseOrder->items, true);
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
                                        <th colspan="6" class="text-end">Subtotal</th>
                                        <th>{{ number_format($subtotal, 2) }}</th>
                                    </tr>
                                    @if($purchaseOrder->discount_amount > 0)
                                    <tr>
                                        <th colspan="6" class="text-end">Discount</th>
                                        <th>{{ number_format($purchaseOrder->discount_amount, 2) }}</th>
                                    </tr>
                                    @endif
                                    @if($purchaseOrder->tax_amount > 0)
                                    <tr>
                                        <th colspan="6" class="text-end">Tax</th>
                                        <th>{{ number_format($purchaseOrder->tax_amount, 2) }}</th>
                                    </tr>
                                    @endif
                                    @if($purchaseOrder->shipping_cost > 0)
                                    <tr>
                                        <th colspan="6" class="text-end">Shipping</th>
                                        <th>{{ number_format($purchaseOrder->shipping_cost, 2) }}</th>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th colspan="6" class="text-end">Grand Total</th>
                                        <th>{{ number_format($purchaseOrder->total_cost, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <h5>Approval Information</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th width="30%">Created By</th>
                                        <td>{{ $purchaseOrder->createdBy->first_name ?? '' }} {{ $purchaseOrder->createdBy->last_name ?? 'N/A' }} ({{ $purchaseOrder->created_at ? date('Y-m-d H:i', strtotime($purchaseOrder->created_at)) : 'N/A' }})</td>
                                    </tr>
                                    @if($purchaseOrder->status == 'approved' || $purchaseOrder->status == 'completed')
                                    <tr>
                                        <th>Approved By</th>
                                        <td>{{ $purchaseOrder->approvedBy->first_name ?? '' }} {{ $purchaseOrder->approvedBy->last_name ?? 'N/A' }} ({{ $purchaseOrder->approved_at ? date('Y-m-d H:i', strtotime($purchaseOrder->approved_at)) : 'N/A' }})</td>
                                    </tr>
                                    @endif
                                    @if($purchaseOrder->status == 'rejected')
                                    <tr>
                                        <th>Rejected By</th>
                                        <td>{{ $purchaseOrder->rejectedBy->first_name ?? '' }} {{ $purchaseOrder->rejectedBy->last_name ?? 'N/A' }} ({{ $purchaseOrder->rejected_at ? date('Y-m-d H:i', strtotime($purchaseOrder->rejected_at)) : 'N/A' }})</td>
                                    </tr>
                                    @endif
                                    @if($purchaseOrder->status == 'completed')
                                    <tr>
                                        <th>Received By</th>
                                        <td>{{ $purchaseOrder->receivedBy->first_name ?? '' }} {{ $purchaseOrder->receivedBy->last_name ?? 'N/A' }} ({{ $purchaseOrder->completed_at ? date('Y-m-d H:i', strtotime($purchaseOrder->completed_at)) : 'N/A' }})</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12 text-end">
                        <a href="{{ route('fixed-assets.purchase-orders.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
