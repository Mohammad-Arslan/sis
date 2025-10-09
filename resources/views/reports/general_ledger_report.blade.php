@extends('layouts.master')

@section('title', 'General Ledger Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">General Ledger Report</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">General Ledger Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title">General Ledger Report</h4>
                            <p class="card-title-desc">Comprehensive financial transactions organized by account categories</p>
                        </div>
                        <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#filterInfoModal">
                            <i class="fas fa-info-circle"></i> Filter Help
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="date" class="filter form-control" id="from_date" name="from_date" placeholder="Select from date">
                                <label for="from_date" class="form-label">From Date</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="date" class="filter form-control" id="to_date" name="to_date" placeholder="Select to date">
                                <label for="to_date" class="form-label">To Date</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="branch_id" name="branch_id">
                                    <option value="">All Branches</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->br_name }} ({{ $branch->branch_code }})</option>
                                    @endforeach
                                </select>
                                <label for="branch_id" class="form-label">Branch</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="account_type" name="account_type">
                                    <option value="">All Account Types</option>
                                    <option value="Revenue">Revenue</option>
                                    <option value="Expense">Expense</option>
                                </select>
                                <label for="account_type" class="form-label">Account Type</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-3 col-sm-12">
                            <div class="form-label-group in-border">
                                <select class="filter form-select" id="transaction_type" name="transaction_type">
                                    <option value="">All Transaction Types</option>
                                    <option value="Student Fee">Student Fee</option>
                                    <option value="Late Fee">Late Fee</option>
                                    <option value="Arrears Fine">Arrears Fine</option>
                                    <option value="Payroll">Payroll</option>
                                    <option value="Asset Purchase">Asset Purchase</option>
                                </select>
                                <label for="transaction_type" class="form-label">Transaction Type</label>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-label-group in-border">
                                <input type="text" class="form-control" id="searchTerm" name="searchTerm" placeholder="Search by account, description, reference...">
                                <label for="searchTerm" class="form-label">Search</label>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-12">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="exportReport()">
                                    <i class="fas fa-download"></i> Export Excel
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table id="general-ledger-data-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Account Type</th>
                                    <th>Description</th>
                                    <th>Reference</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Branch</th>
                                    <th>Transaction Type</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Date</th>
                                    <th>Account</th>
                                    <th>Account Type</th>
                                    <th>Description</th>
                                    <th>Reference</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Branch</th>
                                    <th>Transaction Type</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Information Modal -->
<div class="modal fade" id="filterInfoModal" tabindex="-1" aria-labelledby="filterInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterInfoModalLabel">
                    <i class="fas fa-filter text-primary"></i> General Ledger Report Filters Guide
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-calendar-alt"></i> Date Range Filters
                        </h6>
                        <ul class="list-unstyled mb-4">
                            <li><strong>From Date:</strong> Start date for transaction filtering</li>
                            <li><strong>To Date:</strong> End date for transaction filtering</li>
                            <li><em class="text-muted">Leave both empty to show all transactions</em></li>
                        </ul>

                        <h6 class="text-primary mb-3">
                            <i class="fas fa-building"></i> Branch Filter
                        </h6>
                        <ul class="list-unstyled mb-4">
                            <li><strong>All Branches:</strong> Shows transactions from all branches</li>
                            <li><strong>Specific Branch:</strong> Shows transactions only from selected branch</li>
                            <li><em class="text-muted">Non-admin users automatically see only their branch data</em></li>
                        </ul>

                        <h6 class="text-primary mb-3">
                            <i class="fas fa-chart-pie"></i> Account Type Filter
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-success me-2">Revenue</span> Student fees, late fees, arrears</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-danger me-2">Expense</span> Employee salaries, asset purchases</li>
                                </ul>
                            </div>
                        </div>

                        <h6 class="text-primary mb-3">
                            <i class="fas fa-exchange-alt"></i> Transaction Type Filter
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-success me-2">Student Fee</span> Main tuition fee transactions</li>
                                    <li><span class="badge bg-warning me-2">Late Fee</span> Late payment penalties</li>
                                    <li><span class="badge bg-orange me-2">Arrears Fine</span> Arrears penalty transactions</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-danger me-2">Payroll</span> Employee salary payments</li>
                                    <li><span class="badge bg-secondary me-2">Asset Purchase</span> Fixed asset acquisitions</li>
                                </ul>
                            </div>
                        </div>

                        <h6 class="text-primary mb-3">
                            <i class="fas fa-search"></i> Search Filter
                        </h6>
                        <ul class="list-unstyled mb-4">
                            <li><strong>Search Term:</strong> Searches across account names, descriptions, and references</li>
                            <li><em class="text-muted">Minimum 3 characters required, or leave empty to show all</em></li>
                        </ul>

                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb"></i> Pro Tips
                            </h6>
                            <ul class="mb-0">
                                <li>Use multiple filters together to narrow down results</li>
                                <li>Export function respects all current filter selections</li>
                                <li>All filters work independently - you can use any combination</li>
                                <li>Clear all filters to see complete transaction history</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('header_scripts')
<style>
    /* Custom styling for account types */
    .account-type-revenue {
        color: #28a745;
        font-weight: 600;
    }
    
    .account-type-expense {
        color: #dc3545;
        font-weight: 600;
    }
    
    .account-type-asset {
        color: #007bff;
        font-weight: 600;
    }
    
    .account-type-liability {
        color: #ffc107;
        font-weight: 600;
    }
    
    .account-type-equity {
        color: #6f42c1;
        font-weight: 600;
    }
    
    /* Transaction type badges */
    .transaction-badge {
        font-size: 0.75em;
        padding: 0.25em 0.5em;
        border-radius: 0.25rem;
        font-weight: 500;
    }
    
    .badge-student-fee { background-color: #28a745; color: white; }
    .badge-late-fee { background-color: #ffc107; color: black; }
    .badge-arrears-fine { background-color: #fd7e14; color: white; }
    .badge-payment-received { background-color: #20c997; color: white; }
    .badge-royalty { background-color: #6f42c1; color: white; }
    .badge-payroll { background-color: #dc3545; color: white; }
    .badge-asset-purchase { background-color: #17a2b8; color: white; }
    
    /* Amount formatting */
    .amount-debit {
        color: #dc3545;
        font-weight: 600;
    }
    
    .amount-credit {
        color: #28a745;
        font-weight: 600;
    }
    
    /* Custom badge for arrears fine */
    .bg-orange {
        background-color: #fd7e14 !important;
        color: white !important;
    }
    
    
    /* Modal styling */
    .modal-lg {
        max-width: 800px;
    }
    
    .modal-body h6 {
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 8px;
    }
    
    .modal-body .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }
</style>
@endpush

@push('footer_scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#general-ledger-data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('general-ledger-report') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.branch_id = $('#branch_id').val();
                        d.account_type = $('#account_type').val();
                        d.transaction_type = $('#transaction_type').val();
                        d.searchTerm = $('#searchTerm').val();
                    }
                },
                columns: [
                    {
                        data: 'date',
                        name: 'date',
                        width: "8%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'account',
                        name: 'account',
                        width: "15%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'account_type',
                        name: 'account_type',
                        width: "10%",
                        'defaultContent': '<i>-</i>',
                        render: function(data, type, row) {
                            if (data && data !== '-') {
                                var className = 'account-type-' + data.toLowerCase();
                                return '<span class="' + className + '">' + data + '</span>';
                            }
                            return '<i>-</i>';
                        }
                    },
                    {
                        data: 'description',
                        name: 'description',
                        width: "25%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'reference',
                        name: 'reference',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'debit',
                        name: 'debit',
                        width: "8%",
                        'defaultContent': '<i>-</i>',
                        render: function(data, type, row) {
                            if (data && data !== '-' && parseFloat(data) > 0) {
                                return '<span class="amount-debit">' + data + '</span>';
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'credit',
                        name: 'credit',
                        width: "8%",
                        'defaultContent': '<i>-</i>',
                        render: function(data, type, row) {
                            if (data && data !== '-' && parseFloat(data) > 0) {
                                return '<span class="amount-credit">' + data + '</span>';
                            }
                            return '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'branch',
                        name: 'branch',
                        width: "10%",
                        'defaultContent': '<i>-</i>'
                    },
                    {
                        data: 'transaction_type',
                        name: 'transaction_type',
                        width: "12%",
                        'defaultContent': '<i>-</i>',
                        render: function(data, type, row) {
                            if (data && data !== '-') {
                                var badgeClass = 'transaction-badge badge-' + data.toLowerCase().replace(/\s+/g, '-');
                                return '<span class="' + badgeClass + '">' + data + '</span>';
                            }
                            return '<i>-</i>';
                        }
                    }
                ],
                order: [[0, "desc"]],
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                language: {
                    processing: "Loading transactions...",
                    emptyTable: "No transactions found for the selected criteria",
                    zeroRecords: "No matching transactions found"
                }
            });
        });

        // Filter change handlers
        $(document).on('change', '.filter', function() {
            $('#general-ledger-data-table').DataTable().ajax.reload(null, false);
        });

        // Search keyup handler
        $(document).on("keyup", '#searchTerm', function() {
            var value = $(this).val().toLowerCase();
            if (value.length > 2 || value.length == 0) {
                $('#general-ledger-data-table').DataTable().ajax.reload(null, false);
            }
        });

        // Export function
        function exportReport() {
            var params = new URLSearchParams();
            
            // Get current filter values
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();
            var branchId = $('#branch_id').val();
            var accountType = $('#account_type').val();
            var transactionType = $('#transaction_type').val();
            var searchTerm = $('#searchTerm').val();
            
            // Add parameters if they have values
            if (fromDate) params.append('from_date', fromDate);
            if (toDate) params.append('to_date', toDate);
            if (branchId) params.append('branch_id', branchId);
            if (accountType) params.append('account_type', accountType);
            if (transactionType) params.append('transaction_type', transactionType);
            if (searchTerm) params.append('searchTerm', searchTerm);
            
            // Build URL with parameters
            var url = "{{ route('general-ledger-export') }}";
            if (params.toString()) {
                url += '?' + params.toString();
            }
            
            window.location.href = url;
        }
    </script>
@endpush
