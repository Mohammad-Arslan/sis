@extends('layouts.master')

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .custom-list-group {
            list-style: none;
            padding: 0;
            font-family: "Courier New", monospace;
        }

        .header {
            display: flex;
            justify-content: space-between;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            color: #333;
            /* Darker text color */
        }

        .values {
            display: flex;
            justify-content: space-between;
            padding: 8px;
        }

        .label {
            width: 25%;
            text-align: center;
            font-weight: bold;
            color: #555;
            /* Slightly darker text color */
        }

        .value {
            width: 25%;
            text-align: center;
            font-weight: normal;
            /* Regular font weight */
            color: #000;
            /* Black text color */
        }

        .table-style tr th:nth-child(2) {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }

        .table-style tr td:nth-child(2) {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }

        .table-style1 tr th:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }

        .table-style1 tr td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: white;
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #4F80E1;
            border-color: #4F80E1;
        }

        .page-link {
            position: relative;
            display: block;
            color: #4F80E1;
            text-decoration: none;
            background-color: #F0F7FF;
            border: 1px solid var(--vz-border-color);
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            margin: 2px 10px;
            white-space: nowrap;
            justify-content: flex-end;
        }

        .page-item.disabled .page-link {
            color: #4F80E1;
            pointer-events: none;
            background-color: #F0F7FF;
            border-color: var(--vz-border-color);
        }

        .text-primary {
            color: #018DF0 !important;
        }

        .btn-success {
            color: #605BFF;
            background-color: #F0EFFF;
            border-color: #F0EFFF;
        }

        .btn-success:hover {
            color: #605BFF;
            background-color: #F0EFFF;
            border-color: #F0EFFF;
        }

        .btn-primary {
            color: #fff;
            background-color: #4787F3;
            border-color: #4787F3;
        }

        .btn-primary:hover {
            color: #fff;
            background-color: #4787F3;
            border-color: #4787F3;
        }

        .bg-primary {
            --vz-bg-opacity: 1;
            background-color: #4787F3 !important;
        }

        .btn-info {
            color: #fff;
            background-color: #4787F3;
            border-color: #4787F3;
        }

        /* Super Admin Dashboard Enhancements */
        .card-animate {
            transition: all 0.3s ease;
        }
        
        .card-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .counter-value {
            font-weight: 700;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .bg-soft-primary { background-color: rgba(71, 135, 243, 0.1) !important; }
        .bg-soft-success { background-color: rgba(40, 167, 69, 0.1) !important; }
        .bg-soft-info { background-color: rgba(23, 162, 184, 0.1) !important; }
        .bg-soft-warning { background-color: rgba(255, 193, 7, 0.1) !important; }
        .bg-soft-danger { background-color: rgba(220, 53, 69, 0.1) !important; }
        .bg-soft-secondary { background-color: rgba(108, 117, 125, 0.1) !important; }
        .bg-soft-dark { background-color: rgba(52, 58, 64, 0.1) !important; }

        .border-start {
            border-left-width: 3px !important;
        }

        .fs-13 { font-size: 13px !important; }
        .fs-12 { font-size: 12px !important; }
        .fs-6 { font-size: 14px !important; }

        .alert {
            border-radius: 8px;
        }

        .card {
            border-radius: 10px;
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        @media (max-width: 768px) {
            .col-xl-3 {
                margin-bottom: 1rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    {{-- @include('crm.board.board_header')

    <div class="tasks-board mb-3" id="kanbanboard">

        @foreach ($task_statuses as $task_status)
            @include('crm.board.tasks_list')
        @endforeach

    </div> --}}
    @role('super_admin')
        <!-- Super Admin Comprehensive Dashboard -->

        <!-- Executive Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card card-animate border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                    <i class="fas fa-building fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">Total Branches</p>
                                <h4 class="mb-0"><span class="counter-value" id="total-branches-counter" data-target="{{ $total_branches ?? 0 }}">{{ $total_branches ?? 0 }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card card-animate border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success text-success rounded-circle">
                                    <i class="fas fa-users fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">Total Students</p>
                                <h4 class="mb-0"><span class="counter-value" id="total-students-counter" data-target="{{ $total_students ?? 0 }}">{{ $total_students ?? 0 }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card card-animate border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="fas fa-user-tie fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">Total Staff</p>
                                <h4 class="mb-0"><span class="counter-value" id="total-staff-counter" data-target="{{ $total_employees ?? 0 }}">{{ $total_employees ?? 0 }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-4 col-sm-6">
                <div class="card card-animate border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning text-warning rounded-circle">
                                    <i class="fas fa-boxes fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">Total Assets</p>
                                <h4 class="mb-0"><span class="counter-value" id="total-assets-counter" data-target="{{ $total_assets ?? 0 }}">{{ $total_assets ?? 0 }}</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Student Analytics Section -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-muted mb-3">
                    <i class="fas fa-users me-2"></i>Student Analytics Overview
                </h5>
            </div>
            <!-- Student Status Cards -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success text-success rounded-circle">
                                    <i class="fas fa-user-check fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">On Roll Students</p>
                                <h4 class="mb-0 text-success"><span class="counter-value" id="onroll-counter" data-target="{{ $onroll ?? 0 }}">{{ $onroll ?? 0 }}</span></h4>
                                <p class="text-muted mb-0 fs-12">Active enrollment</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="fas fa-user-plus fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">Registered</p>
                                <h4 class="mb-0 text-info"><span class="counter-value" id="register-counter" data-target="{{ $register ?? 0 }}">{{ $register ?? 0 }}</span></h4>
                                <p class="text-muted mb-0 fs-12">New registrations</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning text-warning rounded-circle">
                                    <i class="fas fa-user-clock fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">Processing</p>
                                <h4 class="mb-0 text-warning"><span class="counter-value" id="processing-counter" data-target="{{ $processing ?? 0 }}">{{ $processing ?? 0 }}</span></h4>
                                <p class="text-muted mb-0 fs-12">Pending approval</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-danger text-danger rounded-circle">
                                    <i class="fas fa-user-minus fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 fs-13">Left Students</p>
                                <h4 class="mb-0 text-danger"><span class="counter-value" id="left-counter" data-target="{{ $left ?? 0 }}">{{ $left ?? 0 }}</span></h4>
                                <p class="text-muted mb-0 fs-12">Withdrawn/transferred</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Branch-wise Staff and Students Chart -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>Branch-wise Staff & Students Overview
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            <select id="branch-chart-academic-year" class="form-select form-select-sm" style="width: auto; min-width: 150px;">
                                <option value="">All Time</option>
                                @foreach($academic_years ?? [] as $year)
                                    <option value="{{ $year['id'] }}">{{ $year['title'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary btn-sm" onclick="updateBranchChart()">
                                <i class="fas fa-filter me-1"></i>Apply
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetBranchChartFilters()">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="branch-staff-students-chart" style="height: 500px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Student Enrollment Trends</h5>
                        <div class="d-flex align-items-center gap-2">
                            <select id="trends-academic-year" class="form-select form-select-sm" style="width: auto; min-width: 150px;">
                                <option value="">All Time</option>
                                @foreach($academic_years ?? [] as $year)
                                    <option value="{{ $year['id'] }}">{{ $year['title'] }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary btn-sm" onclick="updateStudentTrendsChart()">
                                <i class="fas fa-filter me-1"></i>Apply
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetTrendsFilters()">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="student-trends-chart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Student Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div id="student-status-chart" style="height: 300px;"></div>
                        <div class="mt-3">
                            @if(($students_with_arrears ?? 0) > 0)
                            <div class="alert alert-warning py-2 mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>{{ $students_with_arrears }}</strong> students have payment arrears
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted fs-13">Active Rate</span>
                                <span class="fw-bold text-success">{{ $onroll > 0 ? round(($onroll / (($onroll ?? 0) + ($register ?? 0) + ($processing ?? 0) + ($left ?? 0))) * 100, 1) : 0 }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial & Operations Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-muted mb-3">
                    <i class="fas fa-chart-pie me-2"></i>Financial & Operations Overview
                </h5>
            </div>
            <!-- Critical Alerts Row -->
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm {{ (isset($pending_invoices) && $pending_invoices > 0) ? 'border-start border-danger border-3' : '' }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-danger text-danger rounded-circle">
                                    <i class="fas fa-file-invoice-dollar fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted mb-1 fs-13">Pending Invoices</p>
                                    @if(isset($pending_invoices) && $pending_invoices > 0)
                                        <span class="badge bg-danger-subtle text-danger">Action Required</span>
                                    @endif
                                </div>
                                <h4 class="mb-0 text-danger"><span class="counter-value" data-target="{{ $pending_invoices ?? 0 }}">{{ $pending_invoices ?? 0 }}</span></h4>
                                <p class="text-muted mb-0 fs-12">
                                    <i class="fas fa-clock me-1"></i>{{ ($overdue_invoices ?? 0) }} overdue
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm {{ (isset($pending_purchase_requests) && $pending_purchase_requests > 0) ? 'border-start border-warning border-3' : '' }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning text-warning rounded-circle">
                                    <i class="fas fa-shopping-cart fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted mb-1 fs-13">Purchase Requests</p>
                                    @if(isset($pending_purchase_requests) && $pending_purchase_requests > 0)
                                        <span class="badge bg-warning-subtle text-warning">Pending</span>
                                    @endif
                                </div>
                                <h4 class="mb-0 text-warning"><span class="counter-value" data-target="{{ $pending_purchase_requests ?? 0 }}">{{ $pending_purchase_requests ?? 0 }}</span></h4>
                                <p class="text-muted mb-0 fs-12">
                                    <i class="fas fa-list me-1"></i>{{ ($purchase_orders ?? 0) }} total orders
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm {{ (isset($asset_transfer_requests) && $asset_transfer_requests > 0) ? 'border-start border-info border-3' : '' }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info text-info rounded-circle">
                                    <i class="fas fa-exchange-alt fs-4"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted mb-1 fs-13">Asset Transfers</p>
                                    @if(isset($asset_transfer_requests) && $asset_transfer_requests > 0)
                                        <span class="badge bg-info-subtle text-info">In Progress</span>
                                    @endif
                                </div>
                                <h4 class="mb-0 text-info"><span class="counter-value" data-target="{{ $asset_transfer_requests ?? 0 }}">{{ $asset_transfer_requests ?? 0 }}</span></h4>
                                <p class="text-muted mb-0 fs-12">
                                    <i class="fas fa-boxes me-1"></i>{{ ($total_assets ?? 0) }} total assets
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Management Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-muted mb-3">
                    <i class="fas fa-user-graduate me-2"></i>Student Management Activities
                </h5>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-2">
                                <i class="fas fa-user-plus"></i>
                            </span>
                        </div>
                        <h4 class="mb-1"><span class="counter-value" data-target="{{ $admission_inquiries ?? 0 }}">{{ $admission_inquiries ?? 0 }}</span></h4>
                        <p class="text-muted mb-0">Admission Inquiries</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title bg-soft-success text-success rounded-circle fs-2">
                                <i class="fas fa-exchange-alt"></i>
                            </span>
                        </div>
                        <h4 class="mb-1"><span class="counter-value" data-target="{{ $transfer_students ?? 0 }}">{{ $transfer_students ?? 0 }}</span></h4>
                        <p class="text-muted mb-0">Student Transfers</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title bg-soft-warning text-warning rounded-circle fs-2">
                                <i class="fas fa-user-minus"></i>
                            </span>
                        </div>
                        <h4 class="mb-1"><span class="counter-value" data-target="{{ $withdrawal_students ?? 0 }}">{{ $withdrawal_students ?? 0 }}</span></h4>
                        <p class="text-muted mb-0">Withdrawals</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="avatar-md mx-auto mb-3">
                            <span class="avatar-title bg-soft-info text-info rounded-circle fs-2">
                                <i class="fas fa-level-up-alt"></i>
                            </span>
                        </div>
                        <h4 class="mb-1"><span class="counter-value" data-target="{{ $promotion_students ?? 0 }}">{{ $promotion_students ?? 0 }}</span></h4>
                        <p class="text-muted mb-0">Promotions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics & Distribution Section -->
        <div class="row mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">Branch Distribution by Province</h5>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-outline-primary" onclick="exportChart('branch-distribution-chart')">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="branch-distribution-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">HR & Exit Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-danger text-danger rounded-circle">
                                        <i class="fas fa-sign-out-alt fs-6"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">Exit Interviews</p>
                                </div>
                            </div>
                            <h5 class="mb-0 text-danger">{{ $exit_interviews ?? 0 }}</h5>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-circle">
                                        <i class="fas fa-exclamation-triangle fs-6"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">Students with Arrears</p>
                                </div>
                            </div>
                            <h5 class="mb-0 text-warning">{{ $students_with_arrears ?? 0 }}</h5>
                        </div>

                        <div class="mt-4">
                            <h6 class="text-muted mb-3">Province Summary</h6>
                            @if(isset($all_states) && $all_states->count() > 0)
                                @foreach($all_states->take(3) as $state)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted fs-13">{{ $state->state_name }}</span>
                                    <span class="fw-bold">{{ $state->contact_information_count }} branches</span>
                                </div>
                                @endforeach
                                @if($all_states->count() > 3)
                                <div class="text-center mt-3">
                                    <a href="{{ route('branches.index') }}" class="text-primary fs-13">View all branches</a>
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Dashboard & Quick Actions -->
        <div class="row mb-4">
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">System Alerts & Notifications</h5>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-sync me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning border-0 mb-3" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-warning rounded-circle">
                                        <i class="fas fa-exclamation-triangle fs-6"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Payment Reminders</h6>
                                    <p class="mb-0 fs-13">{{ ($overdue_invoices ?? 0) }} invoices are overdue and require immediate attention.</p>
                                </div>
                            </div>
                        </div>

                        @if(($pending_purchase_requests ?? 0) > 0)
                        <div class="alert alert-info border-0 mb-3" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-info rounded-circle">
                                        <i class="fas fa-shopping-cart fs-6"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Purchase Approvals</h6>
                                    <p class="mb-0 fs-13">{{ $pending_purchase_requests }} purchase requests awaiting approval.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(($asset_transfer_requests ?? 0) > 0)
                        <div class="alert alert-primary border-0 mb-3" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-primary rounded-circle">
                                        <i class="fas fa-exchange-alt fs-6"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Asset Transfers</h6>
                                    <p class="mb-0 fs-13">{{ $asset_transfer_requests }} asset transfer requests in progress.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="alert alert-success border-0 mb-0" role="alert">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-success rounded-circle">
                                        <i class="fas fa-check fs-6"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">System Status</h6>
                                    <p class="mb-0 fs-13">All systems are operational. Last updated: {{ now()->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-grid">
                                    <a href="{{ url('students/create?tab=personal') }}" class="btn btn-outline-primary">
                                        <i class="fas fa-user-plus mb-2 fs-4 d-block"></i>
                                        <span class="fs-13">Add Student</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <a href="{{ route('enhanced-bulk-challans') }}" class="btn btn-outline-success">
                                        <i class="fas fa-file-invoice mb-2 fs-4 d-block"></i>
                                        <span class="fs-13">Generate Invoice</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <a href="{{ url('employees/create?tab=basic_info') }}" class="btn btn-outline-info">
                                        <i class="fas fa-user-tie mb-2 fs-4 d-block"></i>
                                        <span class="fs-13">Add Employee</span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-grid">
                                    <a href="{{route('fixed-assets.assets.index') }}" class="btn btn-outline-warning">
                                        <i class="fas fa-boxes mb-2 fs-4 d-block"></i>
                                        <span class="fs-13">Manage Assets</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Enhanced Analytics & Performance Metrics -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-muted mb-3">
                    <i class="fas fa-chart-line me-2"></i>Performance Analytics & Trends
                </h5>
            </div>
            
            <!-- Revenue & Financial Performance -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">Revenue Trends</h5>
                        <div class="flex-shrink-0 d-flex align-items-center gap-2">
                            <select id="revenue-academic-year-filter" class="form-select form-select-sm" style="width: 150px;">
                                <option value="">Academic Year</option>
                                @foreach($academic_years as $academicYear)
                                    <option value="{{ $academicYear['id'] }}" {{ $academicYear['is_current'] ? 'selected' : '' }}>
                                        {{ $academicYear['title'] }}
                                    </option>
                                @endforeach
                            </select>
                            <select id="revenue-branch-filter" class="form-select form-select-sm" style="width: 200px;">
                                <option value="">All Branches</option>
                                @foreach($all_branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportChart('revenue-trends-chart')">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="revenue-trends-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Student Growth Analysis -->
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">Student Growth Analysis</h5>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-outline-primary" onclick="exportChart('student-growth-chart')">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                              </div>
                                </div>
                    <div class="card-body">
                        <!-- Filter Controls -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="growth-branch-filter" class="form-label">Branch</label>
                                <select class="form-select form-select-sm" id="growth-branch-filter">
                                    <option value="">All Branches</option>
                                    @if(isset($all_branches))
                                        @foreach($all_branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="growth-academic-year-filter" class="form-label">Academic Year</label>
                                <select class="form-select form-select-sm" id="growth-academic-year-filter">
                                    <option value="">All Years</option>
                                    @if(isset($academic_years))
                                        @foreach($academic_years as $year)
                                            <option value="{{ $year->id }}" {{ isset($year->is_current) && $year->is_current ? 'selected' : '' }}>
                                                {{ $year->title ?? 'Academic Year ' . $year->id }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                </div>
                            <div class="col-md-4">
                                <label for="growth-date-range" class="form-label">Date Range</label>
                                <select class="form-select form-select-sm" id="growth-date-range">
                                    <option value="all">All Time</option>
                                    <option value="current_year">Current Year</option>
                                    <option value="last_6_months">Last 6 Months</option>
                                    <option value="last_3_months">Last 3 Months</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                        </div>

                        <!-- Custom Date Range (Hidden by default) -->
                        <div class="row mb-3" id="custom-date-range" style="display: none;">
                            <div class="col-md-6">
                                <label for="growth-start-date" class="form-label">Start Date</label>
                                <input type="date" class="form-control form-control-sm" id="growth-start-date">
                    </div>
                            <div class="col-md-6">
                                <label for="growth-end-date" class="form-label">End Date</label>
                                <input type="date" class="form-control form-control-sm" id="growth-end-date">
                                </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                <button class="btn btn-sm btn-primary" onclick="updateStudentGrowthChart()">
                                    <i class="fas fa-sync-alt me-1"></i>Refresh
                                </button>
                                <button class="btn btn-sm btn-outline-secondary" onclick="resetGrowthFilters()">
                                    <i class="fas fa-undo me-1"></i>Reset
                                </button>
                                </div>
                            <div class="text-muted small">
                                <span id="growth-last-updated">Last updated: {{ now()->format('M d, Y H:i') }}</span>
                            </div>
                        </div>

                        <div id="student-growth-chart" style="height: 350px;"></div>
                                </div>
                    </div>
                </div>
            </div>
            


        <!-- Top Performing Branches -->
        <div class="row mb-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">Top Performing Branches</h5>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-outline-primary" onclick="exportChart('top-branches-chart')">
                                <i class="fas fa-download me-1"></i>Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <button class="btn btn-sm btn-primary" onclick="updateBranchesChart()">
                                    <i class="fas fa-sync-alt me-1"></i>Refresh
                                </button>
                            </div>
                            <div class="text-muted small">
                                <span id="branches-last-updated">Last updated: {{ now()->format('M d, Y H:i') }}</span>
                            </div>
                        </div>

                        {{-- <div class="mb-3">
                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Showing top 10 branches with highest profit (Revenue - Expenses)
                            </p>
                        </div> --}}

                        <div id="top-branches-chart" style="height: 600px;"></div>
                          </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Recently Added Students</h5>
                        <div class="flex-shrink-0">
                            <a href="{{ route('students.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-muted fw-semibold">Student</th>
                                        <th class="text-muted fw-semibold">Branch</th>
                                        <th class="text-muted fw-semibold">Status</th>
                                        <th class="text-muted fw-semibold">Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recent_students ?? [] as $student)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-xs flex-shrink-0 me-2">
                                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                        <i class="fas fa-user fs-6"></i>
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-13">{{ $student['name'] }}</h6>
                                                    <small class="text-muted">{{ $student['roll_number'] }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-soft-info text-info">{{ $student['branch_name'] }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($student['status']) {
                                                    'on_roll' => 'bg-soft-success text-success',
                                                    'registered' => 'bg-soft-warning text-warning',
                                                    'left' => 'bg-soft-danger text-danger',
                                                    'Processing' => 'bg-soft-info text-info',
                                                    default => 'bg-soft-secondary text-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $student['status'])) }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="text-muted fs-13">{{ $student['formatted_date'] }}</span>
                                                <small class="text-muted d-block">{{ $student['formatted_date_full'] }}</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-user-graduate fs-2 mb-2"></i>
                                                <p class="mb-0">No recent students found</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- onboarding Details Table start --}}
        {{-- <div class="row">
            <div class="col-xl-12">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                        <i class="ri-error-warning-line label-icon"></i><strong>Error</strong>
                        - {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="card card-height-100">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title flex-grow-1 mb-0">Onboarding Applications</h4>
                        <div class="flex-shrink-0">
                            <a href="https://drive.google.com/drive/folders/1MlQdUWnB7oaaJ-rb9G0lc0IpXaDWvwge" target="_blank"
                                class="btn btn-sm btn-primary pull-right ml-3">
                                Franchise Agreements
                            </a>
                        </div>
                    </div><!-- end cardheader -->
                    <div class="card-body">
        {{-- <div class="col-md-2 col-sm-12">
                              <div class="form-label-group in-border">
                                  <input id="myInput_onboard" type="text" placeholder="Search.." class="form-control">
                                  <label for="myInput_onboard" class="form-label">Search...</label>
                              </div>
                          </div> --}}
        {{-- </div>
                        <div class="table-responsive table-card table-style">
                            <table id="onboarding-detail-table" class="table table-nowrap table-centered align-middle">
                                <thead class="bg-light text-muted">
                                    <tr>
                                    <tr>
                                        <th>Applicant Name</th>
                                        <th>Proposed School Name</th>
                                        <th>School Type</th>
                                        <th>Agreement Type</th>
                                        <th>Total Franchise Fee</th>
                                        <th>Received Amount</th>
                                        <th>Agreement Date</th>
                                        <th>Operational Date</th>
                                        <th>Status</th>
                                        <th>DD Review / Approval</th>
                                        <th>Action</th>
                                        <th>QA / IASF Report</th>
                                    </tr>
                                    </tr><!-- end tr -->
                                </thead><!-- thead -->
                                <tbody>

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th>Applicant Name</th>
                                        <th>Proposed School Name</th>
                                        <th>School Type</th>
                                        <th>Agreement Type</th>
                                        <th>Total Franchise Fee</th>
                                        <th>Received Amount</th>
                                        <th>Agreement Date</th>
                                        <th>Operational Date</th>
                                        <th>Status</th>
                                        <th>DD Review / Approval</th>
                                        <th>Action</th>
                                        <th>QA / IASF Report</th>
                                    </tr>
                                </tfoot>
                            </table><!-- end table -->
                        </div>

                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row --> --}}
        {{-- onboarding Details Table End --}}
        <!-- Financial Performance Overview -->
        <div class="row mb-4">
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Financial Health</h5>
                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#financialHealthModal">
                            <i class="fas fa-info-circle me-1"></i>How we calculate
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-success text-success rounded-circle">
                                        <i class="fas fa-dollar-sign fs-6"></i>
                                    </span>
                                                        </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">Total Revenue</p>
                                </div>
                            </div>
                            <h5 class="mb-0 text-success">PKR {{ number_format($financial_health['total_revenue'] ?? 0) }}</h5>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-circle">
                                        <i class="fas fa-exclamation-triangle fs-6"></i>
                                                                        </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">Outstanding Payments</p>
                                </div>
                            </div>
                            <h5 class="mb-0 text-warning">PKR {{ number_format($financial_health['outstanding_payments'] ?? 0) }}</h5>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-info text-info rounded-circle">
                                        <i class="fas fa-chart-pie fs-6"></i>
                                                                        </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">Collection Rate</p>
                                </div>
                            </div>
                            <h5 class="mb-0 text-info">{{ $financial_health['collection_rate'] ?? 0 }}%</h5>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0">Growth Indicators</h5>
                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#growthIndicatorsModal">
                            <i class="fas fa-info-circle me-1"></i>How we calculate
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle fs-6">
                                        %
                                    </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">Monthly Growth</p>
                                </div>
                            </div>
                            <h5 class="mb-0 text-primary">{{ $growth_indicators['monthly_growth'] >= 0 ? '+' : '' }}{{ $growth_indicators['monthly_growth'] ?? 0 }}%</h5>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-success text-success rounded-circle">
                                        <i class="fas fa-users fs-6"></i>
                                                                        </span>
                                </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">New Admissions</p>
                                </div>
                            </div>
                            <h5 class="mb-0 text-success">+{{ $growth_indicators['new_admissions'] ?? 0 }} this month</h5>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xs flex-shrink-0 me-3">
                                    <span class="avatar-title bg-soft-info text-info rounded-circle">
                                        <i class="fas fa-building fs-6"></i>
                                                                        </span>
                                                        </div>
                                <div>
                                    <p class="text-muted mb-0 fs-13">Branch Expansion</p>
                                                        </div>
                                                    </div>
                            <h5 class="mb-0 text-info">+{{ $growth_indicators['branch_expansion'] ?? 0 }} new branches</h5>
                                                </div>
                                            </div>
                            </div>
                        </div>
                    </div>

        {{-- Visitor Table start --}}

        {{-- visitor  Table End --}}
    @endrole

    @role('network_associate')
        <div class="row project-wrapper">
            <div class="col-xxl-12">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card card-animate">
                            <div class="card-header">
                                <h6 class="card-title mb-0">School Timing</h6>
                            </div>
                            <div class="card-body">
                                <div class="row ">
                                    <div class="col-xl-6">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                    <i data-feather="clock" class="text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="card-title">Early Years (Pre-Nursery, Nursery & KG)</h6>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="card-text text-muted mb-0">Monday to Thursday<br />
                                                        *Pre-Nursery: 8:00 a.m. - 12:30 p.m.<br />
                                                        **Nursery and KG: 8:00 a.m. - 1:00 p.m.<br />
                                                        Friday<br />
                                                        8:00 a.m. - 11:30 a.m.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                    <i data-feather="clock" class="text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="card-title">Primary Classes (I to V)</h6>
                                                <div class="d-flex align-items-center mb-3">
                                                    <p class="card-text text-muted mb-0">Monday to Thursday<br />
                                                        8:00 a.m. - 2:00 p.m.<br />
                                                        Friday<br />
                                                        8:00 a.m. - 12:30 p.m.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                            <div class="card-footer">
                                <div class="col-xl-12">
                                    <p class="card-text text-muted mb-0">
                                        *Please note that for the first two weeks of the Academic Session, the timing for
                                        <strong>Pre-
                                            Nursery students</strong> is 9:00 a.m. – 11:00 a.m. After the first two weeks, the
                                        students will follow the regular School Timing i.e. 8:00 a.m. - 12:30 p.m. This would
                                        help students settle down in the
                                        classroom routine.<br /><br />
                                        **In case of <strong>new admissions in Nursery</strong> where students are not yet
                                        accustomed to going to school,
                                        shorter timing will be followed for the first two weeks, just as Pre-Nursery, and then
                                        moved to
                                        regular school timing.<br /><br />
                                        <strong>Please note that the school timing remains the same for Summers and
                                            Winters.</strong>
                                    </p>
                                </div>
                                <div>
                                </div>
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div>
                </div>
                <div class="row project-wrapper">
                    <div class="col-xxl-12">
                        <div class="col-md-4 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="academic_year_id">
                                    <option value="">Please select</option>
                                    @foreach ($academic_years as $academic_year)
                                        <option @if ($academic_year->active == 1) selected @endif
                                            value="{{ $academic_year->id }}">{{ $academic_year->title }} </option>
                                    @endforeach
                                </select>
                                <label for="academic_year_id" class="form-label">Academic Year</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                    <i data-feather="award" class="text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted mb-3">Registration</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 id='reg-student' class="fs-4 flex-grow-1 mb-0"><span
                                                            class="counter-value"
                                                            data-target="{{ $register_students_count }}">{{ $register_students_count }}</span>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div>
                            </div><!-- end col -->
                            <div class="col-xl-4">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                    <i data-feather="dollar-sign" class="text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted mb-3">Registration Revenue</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 id='reg-student-rev' class="fs-4 flex-grow-1 mb-0"><span
                                                            class="counter-value"
                                                            data-target="{{ $register_students_revenue }}">{{ $register_students_revenue }}</span>
                                                    </h4>
                                                </div>
                                                {{-- <p class="text-muted mb-0">Registrations this month</p> --}}
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div>
                            </div><!-- end col -->
                            <div class="col-xl-4">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                                    <i data-feather="user" class="text-primary"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden ms-3">
                                                <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Active
                                                    Students</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    <h4 id='active-student-rev' class="fs-4 flex-grow-1 mb-0"><span
                                                            class="counter-value"
                                                            data-target="{{ $active_students_count }}">{{ $active_students_count }}</span>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div>
                            </div><!-- end col -->

                            {{-- <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="clock" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Total Revenue</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="0">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col --> --}}
                            {{-- <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                            <i data-feather="briefcase" class="text-primary"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Active Students Revenue</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="0">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col --> --}}

                            {{-- <div class="col-xl-4">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-soft-info text-info rounded-2 fs-2">
                                            <i data-feather="clock" class="text-info"></i>
                                        </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">Total Royalty</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value" data-target="0">0</span></h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col --> --}}
                            <div class="col-xl-8">
                                <div class="card card-animate">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0">
                                                <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                                    <i data-feather="dollar-sign" class="text-warning"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="text-uppercase fw-medium text-muted mb-3">Fee Structure</p>
                                                <div class="d-flex align-items-center mb-3">
                                                    {{-- @foreach ($fee as $fee) --}}
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span>Admission Fee</span></h4>
                                                    <h4 id="AF" class="fs-4 flex-grow-1 mb-0 filter"><span>Rs.
                                                            {{ $fee->amount }}</span></h4>
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span>Tuition Fee</span></h4>
                                                    <h4 id="TF" class="fs-4 flex-grow-1 mb-0 filter"><span>Rs.
                                                            {{ $fee->amount }}</span></h4>
                                                    {{-- @endforeach --}}
                                                    <h4 class="fs-4 flex-grow-1 mb-0"><span>Security Fee</span></h4>
                                                    <h4 id="SF" class="fs-4 flex-grow-1 mb-0 filter"><span>Rs.
                                                            {{ $fee->amount }}</span></h4>
                                                </div>
                                                {{-- <p class="text-muted mb-0">Registrations this month</p> --}}
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div>
                            </div><!-- end col -->

                        </div><!-- end row -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header border-0 align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Student Details</h4>
                                        <div>
                                            <div class="col-md-12 col-sm-12">
                                                <div class="form-label-group in-border">
                                                    <select class="filter form-select" id="academic_year_id_graph"
                                                        name="academic_year_id_graph">
                                                        <option value="">Please select</option>
                                                        @foreach ($academic_years as $academic_year)
                                                            <option @if ($academic_year->active == 1) selected @endif
                                                                value="{{ $academic_year->id }}">{{ $academic_year->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="academic_year_id_graph" class="form-label">Academic
                                                        Year</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- end card header -->

                                    <div class="card-header p-0 border-0 bg-soft-light">
                                        <div class="row g-0 text-center">
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1" id="OR"><span class="counter-value"
                                                            data-target="{{ $onroll }}">{{ $onroll }}</span></h5>
                                                    <p class="text-muted mb-0">On Roll</p>
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1" id="R"><span class="counter-value"
                                                            data-target="{{ $register }}">{{ $register }}</span></h5>
                                                    <p class="text-muted mb-0">Register</p>
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 border border-dashed border-start-0">
                                                    <h5 class="mb-1"><span class="counter-value"
                                                            data-target="{{ $processing }}">{{ $processing }}</span></h5>
                                                    <p class="text-muted mb-0">Processing</p>
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-6 col-sm-3">
                                                <div class="p-3 border border-dashed border-start-0 border-end-0">
                                                    <h5 class="mb-1 text-danger" id="L"><span class="counter-value"
                                                            data-target="{{ $left }}">{{ $left }}</span></h5>
                                                    <p class="text-muted mb-0">Left</p>
                                                </div>
                                            </div><!--end col-->
                                        </div>
                                    </div><!-- end card header -->
                                    <div class="card-body p-0 pb-2 pr-2">
                                        <div>
                                            <div id="projects-overview-chart"
                                                data-colors='["--vz-secondary", "--vz-warning", "--vz-success"]'
                                                class="apex-charts" dir="ltr"></div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-8">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h4 class="card-title mb-0">Academic Calendar</h4>
                                </div><!-- end cardheader -->
                                <div class="card-body pt-0">
                                    <div class="upcoming-scheduled">
                                        <input type="text" class="form-control" data-provider="flatpickr"
                                            data-date-format="d M, Y" data-deafult-date="today" data-inline-date="true">
                                    </div>

                                    <h6 class="text-uppercase fw-semibold mt-4 mb-3 text-muted">Activities:</h6>
                                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span
                                                class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                                09
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">First day of Term I</h6>
                                            {{-- <p class="text-muted mb-0">iTest Factory </p> --}}
                                        </div>
                                        <div class="flex-shrink-0">
                                            <p class="text-muted mb-0">January {{-- <span class="text-uppercase">am</span> --}}</p>
                                        </div>
                                    </div><!-- end -->
                                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span
                                                class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                                12
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">Inquiry based projects</h6>
                                            {{-- <p class="text-muted mb-0">Meta4Systems</p> --}}
                                        </div>
                                        <div class="flex-shrink-0">
                                            <p class="text-muted mb-0">October {{-- <span class="text-uppercase">am</span> --}}</p>
                                        </div>
                                    </div><!-- end -->
                                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span
                                                class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                                25
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">First day of Term II</h6>
                                            {{-- <p class="text-muted mb-0">Nesta Technologies</p> --}}
                                        </div>
                                        <div class="flex-shrink-0">
                                            <p class="text-muted mb-0">July {{-- <span class="text-uppercase">pm</span> --}}</p>
                                        </div>
                                    </div><!-- end -->
                                    <div class="mini-stats-wid d-flex align-items-center mt-3">
                                        <div class="flex-shrink-0 avatar-sm">
                                            <span
                                                class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                                27
                                            </span>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">Mid year result</h6>
                                            {{-- <p class="text-muted mb-0">Nesta Technologies</p> --}}
                                        </div>
                                        <div class="flex-shrink-0">
                                            <p class="text-muted mb-0">June {{-- <span class="text-uppercase">pm</span> --}}</p>
                                        </div>
                                    </div><!-- end -->

                                    <div class="mt-3 text-center">
                                        <a href="javascript:void(0);" class="text-muted text-decoration-underline">View all
                                            Events</a>
                                    </div>

                                </div><!-- end cardbody -->
                            </div><!-- end card -->
                        </div><!-- end col -->
                        {{-- Graph start --}}


                    </div><!-- end col -->
                    <div class="col-xl-4">
                        <div class="card card-height-100">
                            <div class="card-header d-flex align-items-center">
                                <h6 class="card-title mb-0 flex-grow-1">School Manuals</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-card">
                                    <div data-simplebar style="max-height: 450px;">
                                        <ul class="list-group list-group-flush">
                                            @foreach ($school_manuals as $school_manual)
                                                <li class="list-group-item list-group-item-action">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ asset('attachment-icon.png') }}" alt=""
                                                            class="avatar-xs object-cover rounded-circle">
                                                        <div class="ms-3 flex-grow-1">
                                                            <a href="{{ $school_manual->document_type == 'file' ? get_file_from_s3('general_documents/' . $school_manual->attachment_type_id . '/' . $school_manual->file_name) : $school_manual->file_name }}"
                                                                target="_blank" class="stretched-link">
                                                                <h6 class="fs-14 mb-1">{{ $school_manual->document_name }}
                                                                </h6>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('general-document.school-manual.index') }}"
                                            class="text-muted text-decoration-underline">View all School Manuals</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="card">
                            <div class="card-header border-0">
                                <h4 class="card-title mb-0">Academic Calendar</h4>
                            </div><!-- end cardheader -->
                            <div class="card-body pt-0">
                                <div class="upcoming-scheduled">
                                    <input type="text" class="form-control" data-provider="flatpickr"
                                        data-date-format="d M, Y" data-deafult-date="today" data-inline-date="true">
                                </div>

                                <h6 class="text-uppercase fw-semibold mt-4 mb-3 text-muted">Activities:</h6>
                                <div class="mini-stats-wid d-flex align-items-center mt-3">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span
                                            class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                            09
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">First day of Term I</h6>
                                        {{-- <p class="text-muted mb-0">iTest Factory </p> --}}
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="text-muted mb-0">January {{-- <span class="text-uppercase">am</span> --}}</p>
                                    </div>
                                </div><!-- end -->
                                <div class="mini-stats-wid d-flex align-items-center mt-3">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span
                                            class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                            12
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Inquiry based projects</h6>
                                        {{-- <p class="text-muted mb-0">Meta4Systems</p> --}}
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="text-muted mb-0">October {{-- <span class="text-uppercase">am</span> --}}</p>
                                    </div>
                                </div><!-- end -->
                                <div class="mini-stats-wid d-flex align-items-center mt-3">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span
                                            class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                            25
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">First day of Term II</h6>
                                        {{-- <p class="text-muted mb-0">Nesta Technologies</p> --}}
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="text-muted mb-0">July {{-- <span class="text-uppercase">pm</span> --}}</p>
                                    </div>
                                </div><!-- end -->
                                <div class="mini-stats-wid d-flex align-items-center mt-3">
                                    <div class="flex-shrink-0 avatar-sm">
                                        <span
                                            class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                            27
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">Mid year result</h6>
                                        {{-- <p class="text-muted mb-0">Nesta Technologies</p> --}}
                                    </div>
                                    <div class="flex-shrink-0">
                                        <p class="text-muted mb-0">June {{-- <span class="text-uppercase">pm</span> --}}</p>
                                    </div>
                                </div><!-- end -->

                                <div class="mt-3 text-center">
                                    <a href="javascript:void(0);" class="text-muted text-decoration-underline">View all
                                        Events</a>
                                </div>

                            </div><!-- end cardbody -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div><!-- end row -->
                <div class="row">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Notifications</h4>
                        </div>
                        <div class="card-body">
                            <table id="system-notifications-data-table"
                                class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Notification Type</th>
                                        <th>Created At</th>
                                        <th>Notification</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Notification Type</th>
                                        <th>Created At</th>
                                        <th>Notification</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    @endrole


    @if (!auth()->user()->hasRole('super_admin'))
        @if (auth()->user()->hasRole('academic_head'))
            @include('employees.academic_head.dashboard')
        @elseif (auth()->user()->hasRole('school_head'))
            @include('employees.school_head.dashboard')
        @elseif (auth()->user()->hasRole('subject_coordinator'))
            @include('employees.subject_coordinator.dashboard')
        @elseif (auth()->user()->hasRole('manager-parent-relations'))
            @include('employees.parent_relation.dashboard')
        @elseif (auth()->user()->hasRole('parent-relation-officer'))
            @include('employees.parent_relation.dashboard')
        @elseif (auth()->user()->hasRole('head_of_qa|quality_assurance'))
            @include('employees.qa_dashboard.dashboard')
        @elseif (auth()->user()->hasRole('legal-consultant'))
            @include('employees.legal_dashboard.dashboard')
        @elseif (auth()->user()->hasRole('head-of-finance|ho-accountant') && isHeadOfficeEmp())
            @include('employees.accountant.dashboard')
        @elseif (auth()->user()->hasRole('human_resource'))
            @include('employees.dashboard.dashboard')
        @elseif (isHeadOfficeEmp())
            @include('employees.dashboard.dashboard')
        @elseif (auth()->user()->hasRole('accountant'))
            @include('employees.accountant.dashboard')
        @else
        @endif
    @endif


    <div class="modal fade" id="addmemberModal" tabindex="-1" aria-labelledby="addmemberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-soft-warning">
                    <h5 class="modal-title" id="addmemberModalLabel">Add Member</h5>
                    <button type="button" class="btn-close" id="btn-close-member" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="submissionidInput" class="form-label">Submission ID</label>
                                <input type="number" class="form-control" id="submissionidInput"
                                    placeholder="Submission ID">
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="profileimgInput" class="form-label">Profile Images</label>
                                <input class="form-control" type="file" id="profileimgInput">
                            </div><!--end col-->
                            <div class="col-lg-6">
                                <label for="firstnameInput" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstnameInput"
                                    placeholder="Enter firstname">
                            </div><!--end col-->
                            <div class="col-lg-6">
                                <label for="lastnameInput" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastnameInput"
                                    placeholder="Enter lastname">
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="designationInput" class="form-label">Designation</label>
                                <input type="text" class="form-control" id="designationInput"
                                    placeholder="Designation">
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="titleInput" class="form-label">Title</label>
                                <input type="text" class="form-control" id="titleInput" placeholder="Title">
                            </div><!--end col-->
                            <div class="col-lg-6">
                                <label for="numberInput" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="numberInput" placeholder="Phone number">
                            </div><!--end col-->
                            <div class="col-lg-6">
                                <label for="joiningdateInput" class="form-label">Joining Date</label>
                                <input type="text" class="form-control" id="joiningdateInput"
                                    data-provider="flatpickr" placeholder="Select date">
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="emailInput" class="form-label">Email ID</label>
                                <input type="email" class="form-control" id="emailInput" placeholder="Email">
                            </div><!--end col-->
                        </div><!--end row-->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"><i
                            class="ri-close-line align-bottom me-1"></i> Close</button>
                    <button type="button" class="btn btn-success" id="addMember">Add Member</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createboardModal" tabindex="-1" aria-labelledby="createboardModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-soft-info">
                    <h5 class="modal-title" id="createboardModalLabel">Add Board</h5>
                    <button type="button" class="btn-close" id="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="boardName" class="form-label">Board Name</label>
                                <input type="text" class="form-control" id="boardName"
                                    placeholder="Enter board name">
                            </div>
                            <div class="mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="addNewBoard">Add Board</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="creatertaskModal" tabindex="-1" aria-labelledby="creatertaskModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header p-3 bg-soft-info">
                    <h5 class="modal-title" id="creatertaskModalLabel">Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#">
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <label for="projectName" class="form-label">Project Name</label>
                                <input type="text" class="form-control" id="projectName"
                                    placeholder="Enter project name">
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="sub-tasks" class="form-label">Task Title</label>
                                <input type="text" class="form-control" id="sub-tasks" placeholder="Task title">
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="task-description" class="form-label">Task Description</label>
                                <textarea class="form-control" id="task-description" rows="3"></textarea>
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="formFile" class="form-label">Tasks Images</label>
                                <input class="form-control" type="file" id="formFile">
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <label for="tasks-progress" class="form-label">Add Team Member</label>
                                <div data-simplebar style="height: 95px;">
                                    <ul class="list-unstyled vstack gap-2 mb-0">
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="anna-adame">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="anna-adame">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-1.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Anna Adame
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="frank-hook">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="frank-hook">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-3.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Frank Hook
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="alexis-clarke">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="alexis-clarke">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-6.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Alexis Clarke
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="herbert-stokes">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="herbert-stokes">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-2.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Herbert Stokes
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="michael-morris">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="michael-morris">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-7.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Michael Morris
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="nancy-martino">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="nancy-martino">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-5.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Nancy Martino
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="thomas-taylor">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="thomas-taylor">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-8.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Thomas Taylor
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="form-check d-flex align-items-center">
                                                <input class="form-check-input me-3" type="checkbox" value=""
                                                    id="tonya-noble">
                                                <label class="form-check-label d-flex align-items-center"
                                                    for="tonya-noble">
                                                    <span class="flex-shrink-0">
                                                        <img src="{{ asset('theme/dist/default/assets/images/users/avatar-10.jpg') }}"
                                                            alt="" class="avatar-xxs rounded-circle" />
                                                    </span>
                                                    <span class="flex-grow-1 ms-2">
                                                        Tonya Noble
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-4">
                                <label for="due-date" class="form-label">Due Date</label>
                                <input type="text" class="form-control" id="due-date" data-provider="flatpickr"
                                    placeholder="Select date">
                            </div><!--end col-->
                            <div class="col-lg-4">
                                <label for="categories" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="categories" placeholder="Enter tag">
                            </div><!--end col-->
                            <div class="col-lg-4">
                                <label for="tasks-progress" class="form-label">Tasks Progress</label>
                                <input type="text" class="form-control" maxlength="3" id="tasks-progress"
                                    placeholder="Enter progress">
                            </div><!--end col-->
                            <div class="mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success">Add Task</button>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="btn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this tasks ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger" id="delete-record">Yes, Delete It!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade zoomIn" id="branchNotFound" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
                </div> --}}
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/psnhyobz.json" trigger="loop"
                            colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Notification</h4>
                            <p class="text-muted mx-4 mb-0">No branch associated with your account. You cannot proceed
                                further.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="btn w-sm btn-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="align-middle" data-key="t-logout">Okay</span>
                            </a>
                        </form>
                        {{-- <a href="{{ route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();" class="btn w-sm btn-danger" >Okay</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection


@push('header_scripts')
    <link rel="stylesheet" href="{{ asset('theme/dist/default/assets/libs/dragula/dragula.min.css') }}" />
    <style>
        /* .form-select {
            background-color: #fff !important;
            border: 1px solid #dee2e6 !important;
        }
        .form-select:focus {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        } */
    </style>
@endpush

@push('footer_scripts')
    <!-- ECharts Library -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js"></script>
    
    <!-- Super Admin Dashboard Charts -->
    <script>
        @role('super_admin')
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Student Status Pie Chart
            if (document.getElementById('student-status-chart')) {
                window.studentChart = echarts.init(document.getElementById('student-status-chart'));
                const studentData = [
                    {value: {{ $onroll ?? 0 }}, name: 'On Roll', itemStyle: {color: '#28a745'}},
                    {value: {{ $register ?? 0 }}, name: 'Registered', itemStyle: {color: '#17a2b8'}},
                    {value: {{ $processing ?? 0 }}, name: 'Processing', itemStyle: {color: '#ffc107'}},
                    {value: {{ $left ?? 0 }}, name: 'Left', itemStyle: {color: '#dc3545'}}
                ];
                
                const studentOption = {
                    tooltip: {
                        trigger: 'item',
                        formatter: '{a} <br/>{b}: {c} ({d}%)'
                    },
                    legend: {
                        orient: 'vertical',
                        left: 10,
                        top: 'center',
                        textStyle: {
                            fontSize: 12
                        }
                    },
                    series: [{
                        name: 'Students',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        center: ['70%', '50%'],
                        avoidLabelOverlap: false,
                        label: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            label: {
                                show: true,
                                fontSize: '18',
                                fontWeight: 'bold'
                            }
                        },
                        labelLine: {
                            show: false
                        },
                        data: studentData
                    }]
                };
                window.studentChart.setOption(studentOption);
            }

            // Initialize Branch Distribution Chart
            if (document.getElementById('branch-distribution-chart')) {
                window.branchChart = echarts.init(document.getElementById('branch-distribution-chart'));
                const stateNames = @json($all_states->pluck('state_name') ?? []);
                const branchCounts = @json($all_states->pluck('contact_information_count') ?? []);
                
                const branchOption = {
                    title: {
                        text: 'Branch Distribution',
                        left: 'center',
                        textStyle: {
                            fontSize: 16,
                            fontWeight: 'normal'
                        }
                    },
                    tooltip: {
                        trigger: 'axis',
                        axisPointer: {
                            type: 'shadow'
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: stateNames,
                        axisLabel: {
                            rotate: 45,
                            fontSize: 11
                        }
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        name: 'Branches',
                        type: 'bar',
                        data: branchCounts,
                        itemStyle: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                {offset: 0, color: '#4F80E1'},
                                {offset: 1, color: '#2C5282'}
                            ])
                        },
                        emphasis: {
                            itemStyle: {
                                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                    {offset: 0, color: '#667eea'},
                                    {offset: 1, color: '#764ba2'}
                                ])
                            }
                        }
                    }]
                };
                window.branchChart.setOption(branchOption);
            }

            // Student Trends Filter Functions (define before use)
            window.updateStudentTrendsChart = function() {
                const academicYearId = document.getElementById('trends-academic-year').value;
                
                // Show loading state
                if (window.trendsChart) {
                    window.trendsChart.showLoading();
                }
                
                $.ajax({
                    url: '{{ route("student-trends-data") }}',
                    method: 'GET',
                    data: {
                        academic_year_id: academicYearId
                    },
                    success: function(response) {
                        if (window.trendsChart) {
                            window.trendsChart.hideLoading();
                            
                            // Complete chart option with all necessary properties
                            const option = {
                                title: {
                                    text: 'Student Enrollment Trends',
                                    left: 'center',
                                    textStyle: {
                                        fontSize: 16,
                                        fontWeight: 'normal'
                                    }
                                },
                                tooltip: {
                                    trigger: 'axis',
                                    formatter: function(params) {
                                        let result = params[0].axisValue + '<br/>';
                                        params.forEach(function(item) {
                                            result += item.marker + item.seriesName + ': ' + item.value + '<br/>';
                                        });
                                        return result;
                                    }
                                },
                                legend: {
                                    data: ['On Roll', 'New Registrations', 'Withdrawals'],
                                    top: '10%'
                                },
                                grid: {
                                    left: '3%',
                                    right: '4%',
                                    bottom: '3%',
                                    containLabel: true
                                },
                                toolbox: {
                                    feature: {
                                        saveAsImage: {}
                                    }
                                },
                                xAxis: {
                                    type: 'category',
                                    boundaryGap: false,
                                    data: response.months
                                },
                                yAxis: {
                                    type: 'value',
                                    name: 'Number of Students'
                                },
                                series: [
                                    {
                                        name: 'On Roll',
                                        type: 'line',
                                        data: response.on_roll,
                                        smooth: true,
                                        itemStyle: {color: '#28a745'},
                                        lineStyle: {width: 3},
                                        symbol: 'circle',
                                        symbolSize: 6
                                    },
                                    {
                                        name: 'New Registrations',
                                        type: 'line',
                                        data: response.new_registrations,
                                        smooth: true,
                                        itemStyle: {color: '#17a2b8'},
                                        lineStyle: {width: 3},
                                        symbol: 'circle',
                                        symbolSize: 6
                                    },
                                    {
                                        name: 'Withdrawals',
                                        type: 'line',
                                        data: response.withdrawals,
                                        smooth: true,
                                        itemStyle: {color: '#dc3545'},
                                        lineStyle: {width: 3},
                                        symbol: 'circle',
                                        symbolSize: 6
                                    }
                                ]
                            };
                            window.trendsChart.setOption(option);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching trends data:', error);
                        if (window.trendsChart) {
                            window.trendsChart.hideLoading();
                        }
                    }
                });
            };

            window.resetTrendsFilters = function() {
                document.getElementById('trends-academic-year').value = '';
                updateStudentTrendsChart();
            };

            // Initialize Student Trends Chart
            if (document.getElementById('student-trends-chart')) {
                window.trendsChart = echarts.init(document.getElementById('student-trends-chart'));
                
                // Load initial data (All Time)
                updateStudentTrendsChart();
            }

            // Initialize Revenue Trends Chart
            if (document.getElementById('revenue-trends-chart')) {
                window.revenueChart = echarts.init(document.getElementById('revenue-trends-chart'));
                
                // Get initial data from server
                const revenueData = @json($revenue_trends);
                
                const revenueOption = {
                    title: {
                        text: 'Revenue Trends',
                        left: 'center',
                        textStyle: {
                            fontSize: 16,
                            fontWeight: 'normal'
                        }
                    },
                    tooltip: {
                        trigger: 'axis',
                        formatter: function(params) {
                            let result = params[0].name + '<br/>';
                            params.forEach(function(item) {
                                result += item.seriesName + ': PKR ' + item.value.toLocaleString() + '<br/>';
                            });
                            return result;
                        }
                    },
                    legend: {
                        data: ['Revenue', 'Expenses', 'Profit'],
                        top: '10%'
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        data: revenueData.months
                    },
                    yAxis: {
                        type: 'value',
                        axisLabel: {
                            formatter: function(value) {
                                return 'PKR ' + (value / 1000) + 'K';
                            }
                        }
                    },
                    series: [
                        {
                            name: 'Revenue',
                            type: 'bar',
                            data: revenueData.revenue,
                            itemStyle: {color: '#28a745'}
                        },
                        {
                            name: 'Expenses',
                            type: 'bar',
                            data: revenueData.expenses,
                            itemStyle: {color: '#dc3545'}
                        },
                        {
                            name: 'Profit',
                            type: 'line',
                            data: revenueData.profit,
                            smooth: true,
                            itemStyle: {color: '#007bff'}
                        }
                    ]
                };
                window.revenueChart.setOption(revenueOption);
                
                // Handle branch and academic year filter changes
                document.getElementById('revenue-branch-filter').addEventListener('change', function() {
                    updateRevenueChart();
                });
                
                document.getElementById('revenue-academic-year-filter').addEventListener('change', function() {
                    updateRevenueChart();
                });
            }

            // Initialize Student Growth Analysis Chart
            let growthChart = null;
            
            // Function to update Student Growth Chart (defined globally first)
            window.updateStudentGrowthChart = function() {
                if (!growthChart) return;
                
                const branchFilter = document.getElementById('growth-branch-filter');
                const academicYearFilter = document.getElementById('growth-academic-year-filter');
                const dateRangeFilter = document.getElementById('growth-date-range');
                const startDateFilter = document.getElementById('growth-start-date');
                const endDateFilter = document.getElementById('growth-end-date');
                
                const branchId = branchFilter ? branchFilter.value : '';
                const academicYearId = academicYearFilter ? academicYearFilter.value : '';
                const dateRange = dateRangeFilter ? dateRangeFilter.value : '';
                const startDate = startDateFilter ? startDateFilter.value : '';
                const endDate = endDateFilter ? endDateFilter.value : '';
                
                // Show loading state
                growthChart.showLoading({
                    text: 'Loading data...',
                    color: '#4F80E1',
                    textColor: '#000',
                    maskColor: 'rgba(255, 255, 255, 0.8)',
                    zlevel: 0
                });
                
                // Fetch data from server
                fetch(`{{ route('student-growth-data') }}?branch_id=${branchId}&academic_year_id=${academicYearId}&date_range=${dateRange}&start_date=${startDate}&end_date=${endDate}`)
                    .then(response => response.json())
                    .then(data => {
                        growthChart.hideLoading();
                
                const growthOption = {
                    title: {
                        text: 'Student Growth Analysis',
                        left: 'center',
                        textStyle: {
                            fontSize: 16,
                            fontWeight: 'normal'
                        }
                    },
                    tooltip: {
                        trigger: 'item',
                                formatter: function(params) {
                                    return `${params.name}: ${params.value} (${params.percent}%)`;
                                }
                    },
                    legend: {
                        orient: 'vertical',
                        left: 10,
                        top: 'center'
                    },
                    series: [{
                        name: 'Growth Metrics',
                        type: 'pie',
                        radius: ['40%', '70%'],
                        center: ['60%', '50%'],
                                data: data.data.map(item => ({
                                    value: item.value,
                                    name: item.name,
                                    itemStyle: {
                                        color: item.name === 'On Roll' ? '#28a745' :
                                               item.name === 'New Registrations' ? '#17a2b8' :
                                               item.name === 'Processing' ? '#ffc107' : '#dc3545'
                                    }
                                })),
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }]
                };
                        
                growthChart.setOption(growthOption);
                        
                        // Update last updated timestamp
                        const lastUpdatedElement = document.getElementById('growth-last-updated');
                        if (lastUpdatedElement) {
                            lastUpdatedElement.textContent = `Last updated: ${data.last_updated}`;
                        }
                    })
                    .catch(error => {
                        growthChart.hideLoading();
                        console.error('Error fetching student growth data:', error);
                        // Show error message
                        growthChart.setOption({
                    title: {
                                text: 'Error loading data',
                        left: 'center',
                        textStyle: {
                                    color: '#dc3545',
                                    fontSize: 14
                                }
                            }
                        });
                    });
            };
            
            // Initialize chart and event listeners after function definitions
            if (document.getElementById('student-growth-chart')) {
                growthChart = echarts.init(document.getElementById('student-growth-chart'));
                
                // Load initial data
                updateStudentGrowthChart();
            }
            
            // Add event listeners for filters (with null checks)
            const branchFilter = document.getElementById('growth-branch-filter');
            const academicYearFilter = document.getElementById('growth-academic-year-filter');
            const dateRangeFilter = document.getElementById('growth-date-range');
            const startDateFilter = document.getElementById('growth-start-date');
            const endDateFilter = document.getElementById('growth-end-date');
            
            if (branchFilter) {
                branchFilter.addEventListener('change', updateStudentGrowthChart);
            }
            if (academicYearFilter) {
                academicYearFilter.addEventListener('change', updateStudentGrowthChart);
            }
            if (dateRangeFilter) {
                dateRangeFilter.addEventListener('change', function() {
                    const customRange = document.getElementById('custom-date-range');
                    if (this.value === 'custom') {
                        if (customRange) customRange.style.display = 'block';
                    } else {
                        if (customRange) customRange.style.display = 'none';
                    }
                    updateStudentGrowthChart();
                });
            }
            if (startDateFilter) {
                startDateFilter.addEventListener('change', updateStudentGrowthChart);
            }
            if (endDateFilter) {
                endDateFilter.addEventListener('change', updateStudentGrowthChart);
            }
            
            // Function to reset filters
            window.resetGrowthFilters = function() {
                const branchFilter = document.getElementById('growth-branch-filter');
                const academicYearFilter = document.getElementById('growth-academic-year-filter');
                const dateRangeFilter = document.getElementById('growth-date-range');
                const startDateFilter = document.getElementById('growth-start-date');
                const endDateFilter = document.getElementById('growth-end-date');
                const customRange = document.getElementById('custom-date-range');
                
                if (branchFilter) branchFilter.value = '';
                if (academicYearFilter) academicYearFilter.value = '';
                if (dateRangeFilter) dateRangeFilter.value = 'all';
                if (startDateFilter) startDateFilter.value = '';
                if (endDateFilter) endDateFilter.value = '';
                if (customRange) customRange.style.display = 'none';
                
                updateStudentGrowthChart();
            };
            

            // Function to update Branches Chart (defined globally)
            window.updateBranchesChart = function() {
                if (!branchesChart) return;
                
                // Show loading state
                branchesChart.showLoading({
                    text: 'Loading data...',
                    color: '#4F80E1',
                    textColor: '#000',
                    maskColor: 'rgba(255, 255, 255, 0.8)',
                    zlevel: 0
                });
                
                // Fetch data from server (no filters needed)
                fetch(`{{ route('branches-performance-data') }}`)
                    .then(response => response.json())
                    .then(data => {
                        branchesChart.hideLoading();
                        
                        // Check if there's no data
                        if (!data.branches || data.branches.length === 0) {
                            branchesChart.setOption({
                                title: {
                                    text: 'No Profitable Branches Found',
                                    left: 'center',
                                    top: '25%',
                                    textStyle: {
                                        fontSize: 18,
                                        fontWeight: 'bold',
                                        color: '#6B7280'
                                    }
                                },
                                graphic: {
                                    type: 'text',
                                    left: 'center',
                                    top: '45%',
                                    style: {
                                        text: 'There are currently no branches with profitable data to display.\n\nThis could mean:\n• No revenue data available\n• All branches are operating at a loss\n• Data is still being processed',
                                        fontSize: 14,
                                        fill: '#9CA3AF',
                                        textAlign: 'center',
                                        lineHeight: 24
                                    }
                                },
                                grid: {
                                    left: '10%',
                                    right: '10%',
                                    top: '10%',
                                    bottom: '10%'
                                }
                            });
                            return;
                        }
                        
                        const branchNames = data.branches.map(branch => branch.name);
                        const branchValues = data.branches.map(branch => branch.value);
                        
                        const branchesOption = {
                            title: {
                                text: 'Top 10 Profitable Branches',
                                left: 'center',
                                top: '5%',
                                textStyle: {
                                    fontSize: 18,
                                    fontWeight: 'bold',
                                    color: '#374151'
                                }
                            },
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'shadow'
                                },
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                borderColor: '#10B981',
                                borderWidth: 1,
                                textStyle: {
                                    color: '#fff',
                                    fontSize: 12
                                },
                                formatter: function(params) {
                                    const param = params[0];
                                    const branchData = data.branches.find(b => b.name === param.name);
                                    return `<div style="padding: 8px;">
                                                <strong>${param.name}</strong><br/>
                                                <span style="color: #10B981;">💰 Profit: PKR ${param.value.toLocaleString()}</span><br/>
                                                <span style="color: #3B82F6;">📈 Revenue: PKR ${branchData.revenue.toLocaleString()}</span><br/>
                                                <span style="color: #EF4444;">📉 Expenses: PKR ${branchData.expenses.toLocaleString()}</span>
                                            </div>`;
                                }
                            },
                            grid: {
                                left: '8%',
                                right: '8%',
                                bottom: '15%',
                                top: '20%',
                                containLabel: true
                            },
                            xAxis: {
                                type: 'category',
                                data: branchNames,
                                axisLabel: {
                                    rotate: 45,
                                    fontSize: 10,
                                    color: '#6B7280',
                                    interval: 0,
                                    margin: 15
                                },
                                axisLine: {
                                    lineStyle: {
                                        color: '#E5E7EB'
                                    }
                                },
                                axisTick: {
                                    lineStyle: {
                                        color: '#E5E7EB'
                                    }
                                }
                            },
                            yAxis: {
                                type: 'value',
                                name: 'Profit (PKR)',
                                nameTextStyle: {
                                    color: '#6B7280',
                                    fontSize: 12
                                },
                                axisLabel: {
                                    formatter: function(value) {
                                        return 'PKR ' + (value / 1000) + 'K';
                                    },
                                    color: '#6B7280',
                                    fontSize: 10
                                },
                                axisLine: {
                                    lineStyle: {
                                        color: '#E5E7EB'
                                    }
                                },
                                axisTick: {
                                    lineStyle: {
                                        color: '#E5E7EB'
                                    }
                                },
                                splitLine: {
                                    lineStyle: {
                                        color: '#F3F4F6',
                                        type: 'dashed'
                                    }
                                }
                            },
                            series: [{
                                name: 'Profit',
                                type: 'bar',
                                data: branchValues,
                                barWidth: '60%',
                                itemStyle: {
                                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                        {offset: 0, color: '#10B981'},
                                        {offset: 1, color: '#059669'}
                                    ]),
                                    borderRadius: [4, 4, 0, 0]
                                },
                                emphasis: {
                                    itemStyle: {
                                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                            {offset: 0, color: '#34D399'},
                                            {offset: 1, color: '#10B981'}
                                        ]),
                                        shadowBlur: 10,
                                        shadowColor: 'rgba(16, 185, 129, 0.3)'
                                    }
                                },
                                label: {
                                    show: true,
                                    position: 'top',
                                    formatter: function(params) {
                                        return 'PKR ' + (params.value / 1000) + 'K';
                                    },
                                    fontSize: 9,
                                    color: '#374151'
                                }
                            }]
                        };
                        
                        branchesChart.setOption(branchesOption);
                        
                        // Update last updated timestamp
                        const lastUpdatedElement = document.getElementById('branches-last-updated');
                        if (lastUpdatedElement) {
                            lastUpdatedElement.textContent = `Last updated: ${data.last_updated}`;
                        }
                    })
                    .catch(error => {
                        branchesChart.hideLoading();
                        console.error('Error fetching branches performance data:', error);
                        // Show error message
                        branchesChart.setOption({
                            title: {
                                text: 'Error loading data',
                                left: 'center',
                                textStyle: {
                                    color: '#dc3545',
                                    fontSize: 14
                                }
                            }
                        });
                    });
            };

            // Initialize Top Performing Branches Chart
            let branchesChart = null;
            if (document.getElementById('top-branches-chart')) {
                branchesChart = echarts.init(document.getElementById('top-branches-chart'));
                
                // Load initial data
                updateBranchesChart();
            }
            

            // Make charts responsive
            window.addEventListener('resize', function() {
                if (typeof studentChart !== 'undefined') studentChart.resize();
                if (typeof branchChart !== 'undefined') branchChart.resize();
                if (typeof trendsChart !== 'undefined') trendsChart.resize();
                if (typeof window.revenueChart !== 'undefined') window.revenueChart.resize();
                if (typeof growthChart !== 'undefined') growthChart.resize();
                if (typeof branchesChart !== 'undefined') branchesChart.resize();
            });
        });

        // Export Chart Function
        function exportChart(chartId) {
            const chartInstance = echarts.getInstanceByDom(document.getElementById(chartId));
            if (chartInstance) {
                const url = chartInstance.getDataURL({
                    pixelRatio: 2,
                    backgroundColor: '#fff'
                });
                const link = document.createElement('a');
                link.download = chartId + '-' + new Date().getTime() + '.png';
                link.href = url;
                link.click();
            }
        }

        // Update Revenue Chart based on branch and academic year selection
        function updateRevenueChart() {
            if (!window.revenueChart) return;
            
            const branchId = document.getElementById('revenue-branch-filter').value;
            const academicYearId = document.getElementById('revenue-academic-year-filter').value;
            
            console.log('Updating revenue chart for branch:', branchId, 'academic year:', academicYearId);
            
            // Show loading state
            window.revenueChart.showLoading();
            
            // Build URL with parameters
            const params = new URLSearchParams();
            if (branchId) params.append('branch_id', branchId);
            if (academicYearId) params.append('academic_year_id', academicYearId);
            
            const url = `{{ route('branch-revenue-data') }}?${params.toString()}`;
            console.log('Fetching data from URL:', url);
                
            fetch(url)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Received data:', data);
                    
                    // Update chart with new data
                    const option = {
                        xAxis: {
                            data: data.months
                        },
                        series: [
                            {
                                name: 'Revenue',
                                data: data.revenue
                            },
                            {
                                name: 'Expenses',
                                data: data.expenses
                            },
                            {
                                name: 'Profit',
                                data: data.profit
                            }
                        ]
                    };
                    
                    window.revenueChart.setOption(option);
                    window.revenueChart.hideLoading();
                })
                .catch(error => {
                    console.error('Error fetching revenue data:', error);
                    window.revenueChart.hideLoading();
                });
        }
        @endrole

        // Branch-wise Staff and Students Chart
        @role('super_admin')
        let branchChart = null;

        function initBranchChart() {
            if (document.getElementById('branch-staff-students-chart')) {
                branchChart = echarts.init(document.getElementById('branch-staff-students-chart'));
                updateBranchChart();
            }
        }

        function updateBranchChart() {
            if (!branchChart) return;

            const academicYearId = document.getElementById('branch-chart-academic-year').value;
            branchChart.showLoading();

            fetch(`{{ route('branch-wise-staff-students-data') }}?academic_year_id=${academicYearId}`)
                .then(response => response.json())
                .then(data => {
                    const branches = data.branches;
                    
                    // Prepare chart data
                    const branchNames = branches.map(branch => branch.branch_name);
                    const studentData = branches.map(branch => branch.total_students);
                    const staffData = branches.map(branch => branch.total_staff);
                    
                    
                    const option = {
                        title: {
                            text: 'Branch-wise Staff & Students Distribution',
                            left: 'center',
                            textStyle: {
                                fontSize: 16,
                                fontWeight: 'bold'
                            }
                        },
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            },
                            formatter: function(params) {
                                let result = params[0].name + '<br/>';
                                params.forEach(param => {
                                    result += param.marker + param.seriesName + ': ' + param.value + '<br/>';
                                });
                                return result;
                            }
                        },
                        legend: {
                            data: ['Students', 'Staff'],
                            top: 40
                        },
                        grid: {
                            left: '5%',
                            right: '5%',
                            bottom: '10%',
                            top: '15%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'category',
                            data: branchNames,
                            axisLabel: {
                                rotate: 45,
                                fontSize: 9,
                                interval: 0,
                                formatter: function(value) {
                                    return value.length > 20 ? value.substring(0, 20) + '...' : value;
                                }
                            }
                        },
                        yAxis: {
                            type: 'value',
                            name: 'Count',
                            nameLocation: 'middle',
                            nameGap: 50
                        },
                        series: [
                            {
                                name: 'Students',
                                type: 'bar',
                                data: studentData,
                                itemStyle: {
                                    color: '#4F80E1'
                                },
                                emphasis: {
                                    itemStyle: {
                                        color: '#3A6BC7'
                                    }
                                }
                            },
                            {
                                name: 'Staff',
                                type: 'bar',
                                data: staffData,
                                itemStyle: {
                                    color: '#28A745'
                                },
                                emphasis: {
                                    itemStyle: {
                                        color: '#1E7E34'
                                    }
                                }
                            }
                        ]
                    };
                    
                    branchChart.setOption(option);
                    branchChart.hideLoading();
                })
                .catch(error => {
                    console.error('Error fetching branch data:', error);
                    branchChart.hideLoading();
                });
        }


        function resetBranchChartFilters() {
            document.getElementById('branch-chart-academic-year').value = '';
            updateBranchChart();
        }

        // Initialize chart when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initBranchChart();
        });
        @endrole
    </script>
    
    <script src="{{ asset('theme/dist/default/assets/libs/dragula/dragula.min.js') }}"></script>
    <script src="{{ asset('theme/dist/default/assets/libs/dom-autoscroller/dom-autoscroller.min.js') }}"></script>

    <!--taks-kanban-->
    <!-- <script src="{{ asset('theme/dist/default/assets/js/pages/tasks-kanban.init.js') }}"></script> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css"
        rel="stylesheet">

    <script src="https://netdna.bootstrapcdn.com/bootstrap/2.3.2/js/bootstrap.min.js"></script>

    <!-- Financial Health Calculation Modal -->
    <div class="modal fade" id="financialHealthModal" tabindex="-1" aria-labelledby="financialHealthModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="financialHealthModalLabel">
                        <i class="fas fa-calculator me-2"></i>Financial Health Calculations
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-dollar-sign me-2"></i>Total Revenue
                            </h6>
                            <div class="bg-light p-3 rounded mb-3">
                                <strong>How it's calculated:</strong> Total amount of money collected from all paid student fees
                            </div>
                            <p class="text-muted small mb-4">
                                This shows the actual revenue your organization has received from student payments. It includes all successfully processed payments from students.
                            </p>

                            <h6 class="text-warning mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>Outstanding Payments
                            </h6>
                            <div class="bg-light p-3 rounded mb-3">
                                <strong>How it's calculated:</strong> Total amount of money still owed by students
                            </div>
                            <p class="text-muted small mb-4">
                                This represents the amount of money that students still need to pay. It helps you track pending payments and follow up with students who have outstanding balances.
                            </p>

                            <h6 class="text-info mb-3">
                                <i class="fas fa-chart-pie me-2"></i>Collection Rate
                            </h6>
                            <div class="bg-light p-3 rounded mb-3">
                                <strong>How it's calculated:</strong> Percentage of fees that have been successfully collected
                            </div>
                            <p class="text-muted small">
                                This shows how effective your fee collection process is. A higher percentage means more students are paying their fees on time. For example, 75% means 3 out of 4 students have paid their fees.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Growth Indicators Calculation Modal -->
    <div class="modal fade" id="growthIndicatorsModal" tabindex="-1" aria-labelledby="growthIndicatorsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="growthIndicatorsModalLabel">
                        <i class="fas fa-chart-line me-2"></i>Growth Indicators Calculations
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-trending-up me-2"></i>Monthly Growth
                            </h6>
                            <div class="bg-light p-3 rounded mb-3">
                                <strong>How it's calculated:</strong> Percentage change in student enrollment compared to last month
                            </div>
                            <p class="text-muted small mb-4">
                                This shows how much your student enrollment has grown compared to the previous month. A positive percentage means you have more students this month than last month. For example, +20% means you have 20% more students than last month.
                            </p>

                            <h6 class="text-success mb-3">
                                <i class="fas fa-users me-2"></i>New Admissions
                            </h6>
                            <div class="bg-light p-3 rounded mb-3">
                                <strong>How it's calculated:</strong> Number of new students enrolled this month
                            </div>
                            <p class="text-muted small mb-4">
                                This shows how many new students have joined your organization this month. It helps you track the success of your admission process and marketing efforts.
                            </p>

                            <h6 class="text-info mb-3">
                                <i class="fas fa-building me-2"></i>Branch Expansion
                            </h6>
                            <div class="bg-light p-3 rounded mb-3">
                                <strong>How it's calculated:</strong> Number of new branches opened this year
                            </div>
                            <p class="text-muted small">
                                This shows how many new branches your organization has opened this year. It indicates the growth and expansion of your educational network.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>




    <script type="text/javascript">
        var tasks_list = [

            document.getElementById("kanbanboard"),

            document.getElementById("unassigned-task"),
            document.getElementById("todo-task"),
            document.getElementById("inprogress-task"),
            document.getElementById("reviews-task"),
            document.getElementById("completed-task"),
            document.getElementById("new-task")
        ];

        $(document).ready(function() {




            var myModalEl = document.getElementById('deleteRecordModal');

            myModalEl.addEventListener('show.bs.modal', function(event) {

                document.getElementById('delete-record').addEventListener('click', function() {
                    event.relatedTarget.closest(".tasks-box").remove();
                    document.getElementById('btn-close').click();
                });
            });


            taskCounter();



            drake = dragula(tasks_list).on('drag', function(el) {

                el.className = el.className.replace('ex-moved', '');

            }).on('drop', function(el, container) {

                var task_id = el.getAttribute('data-task_id');
                var task_status = container.getAttribute('data-task_status');

                $.ajax({

                    url: "{{ route('change-task-status') }}",
                    type: "POST",
                    data: {
                        task_id,
                        task_status
                    },
                    headers: {
                        'X-CSRF-Token': '{{ csrf_token() }}',
                    },
                    success: function(data) {

                    },
                    error: function() {

                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    }
                });

                el.className += ' ex-moved';

            }).on('over', function(el, container) {
                console.log(el, container);

                container.className += ' ex-over';
            }).on('out', function(el, container) {
                // console.log(el, container);
                container.className = container.className.replace('ex-over', '');
                taskCounter();
            });


            var scroll = autoScroll([
                document.querySelector("#kanbanboard"),
            ], {
                margin: 20,
                maxSpeed: 100,
                scrollWhenOutside: true,
                autoScroll: function() {
                    return this.down && drake.dragging;
                }
            });

            //Create a new kanban board
            document
                .getElementById("addNewBoard")
                .addEventListener("click", newKanbanbaord);

            function newKanbanbaord() {
                var boardName = document.getElementById("boardName").value;

                var uniqueid = Math.floor(Math.random() * 100);

                var randomid = "remove_item_" + uniqueid;

                var dragullaid = "review_task_" + uniqueid;

                kanbanlisthtml =
                    '<div class="tasks-list" id=' +
                    randomid +
                    ">" +
                    '<div class="d-flex mb-3">' +
                    '<div class="flex-grow-1">' +
                    '<h6 class="fs-14 text-uppercase fw-semibold mb-0">' +
                    boardName +
                    '</h6>' +
                    '</div>' +
                    '<div class="flex-shrink-0">' +
                    '<div class="dropdown card-header-dropdown">' +
                    '<a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    '<span class="fw-medium text-muted fs-12">Priority<i class="mdi mdi-chevron-down ms-1"></i></span>' +
                    '</a>' +
                    '<div class="dropdown-menu dropdown-menu-end">' +
                    '<a class="dropdown-item" href="#">Priority</a>' +
                    '<a class="dropdown-item" href="#">Date Added</a>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div data-simplebar class="tasks-wrapper px-3 mx-n3">' +
                    '<div class="tasks" id="' + dragullaid + '" >' +
                    '</div>' +
                    '</div>' +
                    '<div class="my-3">' +
                    '<button class="btn btn-soft-info w-100" data-bs-toggle="modal" data-bs-target="#creatertaskModal">Add More</button>' +
                    '</div>' +
                    '</div>';

                var subTask = document.getElementById("kanbanboard");
                subTask.insertAdjacentHTML("beforeend", kanbanlisthtml);

                var link = document.getElementById("btn-close");
                link.click();

                drake.destroy();
                tasks_list.push(document.getElementById(dragullaid));
                drake = dragula(tasks_list);
                document.getElementById("boardName").value = "";
            }

            // Add Members
            document
                .getElementById("addMember")
                .addEventListener("click", newMemberAdd);

            //set membar profile
            var profileField = document.getElementById("profileimgInput");
            var reader = new FileReader();
            profileField.addEventListener("change", function(e) {
                reader.readAsDataURL(profileField.files[0]);
                reader.onload = function() {
                    var imgurl = reader.result;
                    var dataURL = '<img src="' + imgurl +
                        '" alt="profile" class="rounded-circle avatar-xs">';
                    localStorage.setItem('kanbanboard-member', dataURL);
                };
            });


        });


        $(document).on('click', '.add-task', function(e) {

            var task_status = $(this).data('task_status');

            $.ajax({

                url: "{{ route('add-task') }}",
                type: "POST",
                data: {},
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                success: function(data) {


                },
                error: function() {

                },
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                }
            });
        });

        $(document).on('click', '.add-project-member', function(e) {

            var task_status = $(this).data('task_status');

            $.ajax({

                url: "{{ route('add-project-member') }}",
                type: "POST",
                data: {},
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
                success: function(data) {
                    newMemberAdd(data);
                },
                error: function() {

                },
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                }
            });
        });


        function taskCounter() {

            task_lists = document.querySelectorAll("#kanbanboard .task-list");

            console.log($(".task-list").length);

            task_lists.forEach(function(element) {

                tasks = element.getElementsByClassName("task");

                tasks.forEach(function(ele) {
                    task_box = ele.getElementsByClassName("task-box");
                    task_counted = task_box.length;
                });

                badge = element.querySelector(".card-title .badge").innerText = "";
                badge = element.querySelector(".card-title .badge").innerText = task_counted;
            });
        }


        function newMemberAdd(member) {

            var newMembar = `

                <a href="javascript: void(0);" class="avatar-group-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Noman Ahmed">
                    <img src="{{ asset('theme/dist/default/assets/images/users/avatar-1.jpg') }}" alt="" class="rounded-circle avatar-xs">
                </a>
            `

            var subMemberAdd = document.getElementById("newMembar");
            subMemberAdd.insertAdjacentHTML("afterbegin", newMembar);

            // var link = document.getElementById("btn-close-member");
            // link.click();
        }

        @role('network_associate')
            $(document).ready(function() {
                var branch = {{ $nwa_branch_id }}
                if (branch < 1) {
                    $('#branchNotFound').modal('show', {
                        backdrop: 'static',
                        keyboard: false
                    }, );
                }

            });
        @endrole ()
        $(document).ready(function() {
            document.getElementById('academic_year_id').addEventListener('change', function() {
                var academic_year_id = this.value;
                //   var request =
                $.ajax({
                    type: "GET",
                    url: 'get-card-value?academic_year_id=' + academic_year_id,
                    success: function(response) {
                        // $("#test").html(html).show('slow');
                        console.log(response);
                        if (response.fee != null) {
                            $('#AF').html(response.fee.amount);
                            $('#TF').html(response.fee.amount);
                            $('#SF').html(response.fee.amount);
                        } else {
                            $('#AF').html('0');
                            $('#TF').html('0');
                            $('#SF').html('0');
                        }
                        $('#reg-student').html(response.register_students_count);
                        $('#reg-student-rev').html(response.register_students_revenue);
                        // $('#active-student-rev').html(response.active_students_count);

                    },
                });

            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });

            $('#system-notifications-data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                // ajax: "{{ route('system-notifications.index') }}",
                ajax: {
                    url: "{{ route('nwa-notifcation-card') }}",
                    dataType: "json",
                    method: 'GET',
                    // data: function(d) {
                    //     d.audience = $('#audience').val();
                    //     d.notification_type = $('#notification_type').val();
                    //     d.branch_id = $('#branch_id').val();
                    // }
                },
                columns: [

                    {
                        data: 'notification_type',
                        name: 'notification_type'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: "15%"
                    },
                    {
                        data: 'message',
                        name: 'message'
                    }

                ]
            });

            $("#notification_type").change(function() {
                var selected_option = $('#notification_type').val();
                if (selected_option == 'SMS' || selected_option == 'Push Notification') {
                    document.getElementById("message_div").style.display = "block";
                    document.getElementById('email_body_div').style.display = "none";
                }
                if (selected_option == 'Email') {
                    document.getElementById("message_div").style.display = "none";
                    document.getElementById('email_body_div').style.display = "block";
                }
                if (selected_option == 'SMS, Email, Push Notification') {
                    document.getElementById("message_div").style.display = "block";
                    document.getElementById('email_body_div').style.display = "block";
                }
            });
            $(document).on('change', '.filter', function() {
                $('#system-notifications-data-table').DataTable().ajax.reload(null, false);
            });
        });
    </script>
    <script>
        // var academic_year_id_graph = $('#academic_year_id_graph').val();
        $(document).ready(function() {
            ajaxcall(academic_year_id_graph = $('#academic_year_id_graph').val());
            $(document).on('change', '#academic_year_id_graph', function(e) {
                ajaxcall(academic_year_id_graph = $('#academic_year_id_graph').val());
            });
        });

        function getChartColorsArray(e) {
            if (null !== document.getElementById(e)) {
                var e = document.getElementById(e).getAttribute("data-colors");
                return (e = JSON.parse(e)).map(function(e) {
                    var t = e.replace(" ", "");
                    if (-1 === t.indexOf(",")) {
                        var r = getComputedStyle(document.documentElement).getPropertyValue(t);
                        return r || t
                    }
                    e = e.split(",");
                    return 2 != e.length ? t : "rgba(" + getComputedStyle(document.documentElement)
                        .getPropertyValue(e[0]) + "," + e[1] + ")"
                })
            }
        }
        //Line graph. to get the url parametes month, week, day


        function ajaxcall(academic_year_id_graph) {
            // var academic_year_id_graph = $('#academic_year_id_graph').val();
            $.ajax({
                type: "GET",
                //url: "/get-linegraph",
                url: '{{ route('graph-data') }}',
                data: {
                    academic_year_id_graph: academic_year_id_graph
                },
                dataType: 'json',
                success: function(res) {
                    console.log('the response', res);
                    $('#OR').html(res[0].onroll);
                    $('#R').html(res[0].register);
                    $('#L').html(res[0].left);
                    // $('#SF').html(response.fee.amount);
                    // Convert to ECharts
                    const chartElement = document.querySelector("#projects-overview-chart");
                    if (chartElement) {
                        const chart = echarts.init(chartElement);
                        
                        const option = {
                            tooltip: {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross'
                                }
                            },
                            legend: {
                                data: ['Register Students', 'On roll Students', 'Left students'],
                                top: 10
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: {
                                type: 'category',
                                data: ["Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"]
                            },
                            yAxis: {
                                type: 'value'
                            },
                            series: [
                                {
                                    name: 'Register Students',
                                    type: 'bar',
                                    data: [res[0].registered[0], res[0].registered[1], res[0].registered[2],
                                        res[0].registered[3], res[0].registered[4], res[0].registered[5], 
                                        res[0].registered[6], res[0].registered[7], res[0].registered[8], 
                                        res[0].registered[9], res[0].registered[10], res[0].registered[11]
                                    ],
                                    itemStyle: {
                                        color: '#4F80E1'
                                    }
                                },
                                {
                                    name: 'On roll Students',
                                    type: 'line',
                                    data: [res[1].on_roll[0], res[1].on_roll[1], res[1].on_roll[2], 
                                        res[1].on_roll[3], res[1].on_roll[4], res[1].on_roll[5], 
                                        res[1].on_roll[6], res[1].on_roll[7], res[1].on_roll[8], 
                                        res[1].on_roll[9], res[1].on_roll[10], res[1].on_roll[11]
                                    ],
                                    smooth: true,
                                    itemStyle: {
                                        color: '#28a745'
                                    },
                                    areaStyle: {
                                        opacity: 0.1
                                    }
                                },
                                {
                                    name: 'Left students',
                                    type: 'bar',
                                    data: [res[2].lefts[0], res[2].lefts[1], res[2].lefts[2], 
                                        res[2].lefts[3], res[2].lefts[4], res[2].lefts[5], 
                                        res[2].lefts[6], res[2].lefts[7], res[2].lefts[8], 
                                        res[2].lefts[9], res[2].lefts[10], res[2].lefts[11]
                                    ],
                                    itemStyle: {
                                        color: '#dc3545'
                                    }
                                }
                            ]
                        };
                        
                        chart.setOption(option);
                    }
                }
            });
        }
    </script>
    <script type="text/javascript">
        

        $(document).ready(function() {

            $.extend($.fn.dataTableExt.oStdClasses, {
                "sFilterInput": "form-control",
                "sLengthSelect": "form-control"
            });


            $('#visits-data-table').DataTable({
                searching: false,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                    searchPlaceholder: "Search..."
                },
                @if (isSuperAdmin())
                    ajax: {
                        url: "{{ route('visitDetail.index') }}",
                        data: function(d) {
                            d.user_id = $('#s_user_id').val();
                            d.campus_office_id = $('#s_campus_office_id').val();
                            d.branch_id = $('#s_branch_id').val();
                            d.from_city_id = $('#s_from_city_id').val();
                            d.to_city_id = $('#s_to_city_id').val();
                            d.total_duration = $('#s_total_duration').val();
                            d.approval_status = $('#s_approval_status').val();

                        }
                    },
                @else
                    ajax: {
                        url: "{{ route('visitDetail.index', ['req' => isset(request()->req) ? request()->req : 'my']) }}",
                        data: function(d) {
                            //d.s_user_id = $('#s_user_id').val();
                            d.campus_office_id = $('#s_campus_office_id').val();
                            d.branch_id = $('#s_branch_id').val();
                            d.from_city_id = $('#s_from_city_id').val();
                            d.to_city_id = $('#s_to_city_id').val();
                            d.total_duration = $('#s_total_duration').val();
                            d.approval_status = $('#s_approval_status').val();
                        }
                    },
                @endif

                columns: [
                    // {
                    //     data: 'id',
                    //     name: 'id',
                    //     width: "5%"
                    // },
                    {
                        data: 'full_name',
                        name: 'full_name'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'campus_office',
                        name: 'campus_office'
                    },
                    {
                        data: 'branch_name',
                        name: 'branch_name'
                    },
                    {
                        data: 'city_from',
                        name: 'city_from'
                    },
                    {
                        data: 'city_to',
                        name: 'city_to'
                    },
                    {
                        data: 'total_duration',
                        name: 'total_duration'
                    },
                    {
                        data: 'travel_on',
                        name: 'travel_on'
                    },
                    {
                        data: 'return_on',
                        name: 'return_on'
                    },
                    {
                        data: 'travel_mode',
                        name: 'travel_mode'
                    },
                    {
                        data: 'purpose',
                        name: 'purpose',
                        // render: function ( data, type, row ) {
                        //     return '<span style="white-space:normal; width: 150px;">' + data + "</span>";
                        // }
                    },
                    {
                        data: 'remarks',
                        name: 'remarks',
                        // render: function ( data, type, row ) {
                        //     return '<span style="white-space:normal; width: 40em;">' + data + "</span>";
                        // }
                    },
                    {
                        data: 'approval_status',
                        // name: 'approval_status',
                        render: function(data, type, row) {
                            if (data == 'pending') {
                                return '<span class="badge bg-warning text-bold">Pending</span>';
                            } else if (data == 'approve') {
                                return '<span class="badge bg-success text-bold">Approved</span>';
                            } else {
                                return '<span class="badge bg-danger text-bold">Cancelled</span>';
                            }

                        }
                    },
                    {
                        data: 'approval_auth',
                        name: 'approval_auth',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "5%",
                        sClass: "text-center"
                    },
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        });
        // @role('super_admin')
        // $(document).on("keyup", '#mySearch', function() {
        //     var value = $(this).val().toLowerCase();
        //     if (value.length > 0 || value.length == 0) {
        //         $('#visits-data-table').DataTable().ajax.reload(null, false).page('first');
        //     }
        // });
        // @endrole
    </script>
    <script type="text/javascript">
        // $("#start_date").datepicker({
        //     format: "mm",
        //     startView: "months",
        //     minViewMode: "months"
        // });
        // $("#end_date").datepicker({
        //     format: "mm-yyyy",
        //     startView: "months",
        //     minViewMode: "months"
        // });
        $(document).ready(function() {
            $('#onboarding-detail-table').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                responsive: true,
                bLengthChange: false,
                ordering: true,
                pageLength: 10,
                scrollX: true,
                language: {
                    search: "",
                    searchPlaceholder: "Search...",
                    processing: "<img class='ucs_loader' src='{{ asset('loader.gif') }}' />",
                },
                ajax: {
                    url: "{{ route('on-boarding-list') }}",
                    data: function(d) {
                        d.state_id = $('#franchise_state_id').val();
                        d.city_id = $('#city').val();
                        d.school_type = $('#school_type').val();
                        d.status = $('#status_list').val();
                    }
                },
                columns: [
                    /*{data: 'id', name: 'id', width: "5%",orderable: true},
                    {data: 'company.company_name', name: 'company.company_name'},*/
                    {
                        data: 'full_name',
                        name: 'full_name',
                        width: "10%",
                    },
                    {
                        data: 'purposed_school_name',
                        name: 'purposed_school_name',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'school_type',
                        name: 'school_type',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'agreement_type',
                        name: 'agreement_type',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'total_franchise_fee',
                        name: 'total_franchise_fee',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'amount_received',
                        name: 'amount_received',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'agreement_date',
                        name: 'agreement_date',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'operational_date',
                        name: 'operational_date',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'statuses',
                        name: 'statuses',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'franchise_application_dd_status',
                        name: 'franchise_application_dd_status',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "15%"
                    },
                    {
                        data: 'reports_action',
                        name: 'reports_action',
                        orderable: false,
                        searchable: false,
                        width: "5%"
                    }
                ],
                order: [
                    [0, "desc"]
                ]
            });
        });

        $(document).on('change', '.filter', function() {
            $('#onboarding-detail-table').DataTable().ajax.reload(null, false);
        });
        $(document).on('change', '#company_id', function(e) {

            $.ajax({

                url: "{{ route('list-network-associates') }}?id=" + $(this).val(),
                type: "GET",
                cache: false,
                success: function(data) {

                    var options = `<option value="">Please select</option>`;

                    if (data) {
                        console.log(data)
                        $.each(data, function(index, value) {
                            options += '<option value="' + value.id + '">' + value.user.name +
                                '</option>';
                        });
                    }

                    $('#nwa_id').html(options).attr('disabled', false);
                },
                error: function() {

                },
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    hideLoading();
                }
            });
        });
    </script>
@endpush

