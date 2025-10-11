@extends('layouts.master')

@section('title', 'Bulk Mark as Paid')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('student-invoices.index') }}">Student Invoices</a>
                            </li>
                            <li class="breadcrumb-item active">Bulk Mark as Paid</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="header-title">Filter Invoices</h4>
                    </div>
                    <div class="card-body">
                        <form id="filterForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="branch_id" class="form-label">Branch <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="branch_id" name="branch_id" required>
                                            <option value="">Select Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->br_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="academic_year_id" class="form-label">Academic Year</label>
                                        <select class="form-control" id="academic_year_id" name="academic_year_id">
                                            <option value="">All Academic Years</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="fee_period_id" class="form-label">Fee Period</label>
                                        <select class="form-control" id="fee_period_id" name="fee_period_id">
                                            <option value="">All Fee Periods</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">&nbsp;</label>
                                        <div>
                                            <button type="button" class="btn btn-primary" id="loadInvoices">
                                                <i class="ri-search-line"></i> Load Invoices
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="clearFilters">
                                                <i class="ri-refresh-line"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="invoicesSection" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="header-title">Unpaid Invoices</h4>
                        <div>
                            <button type="button" class="btn btn-success" id="markAllAsPaid" disabled>
                                <i class="ri-check-double-line"></i> Mark All as Paid
                            </button>
                            <button type="button" class="btn btn-info" id="markSelectedAsPaid" disabled>
                                <i class="ri-check-line"></i> Mark Selected as Paid
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-centered table-striped dt-responsive nowrap w-100" id="invoicesTable">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                                <label class="form-check-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th>Student Name</th>
                                        <th>Registration No</th>
                                        <th>System ID</th>
                                        <th>Invoice Type</th>
                                        <th>Fee Period</th>
                                        <th>Total Payable</th>
                                        <th>Total Paid</th>
                                        <th>Remaining Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5>Processing Payments...</h5>
                    <p class="text-muted">Please wait while we mark the invoices as paid. Do not close this window.</p>
                    <div class="progress mt-3" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                    </div>
                    <small class="text-muted" id="progressText">Processing batch 1 of 1...</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Mark Invoices as Paid</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="paid_date" class="form-label">Payment Date <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="paid_date" name="paid_date"
                                        data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                                        value="{{ date('d-m-Y') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method" class="form-label">Payment Method <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="cheque">Cheque</option>
                                        <option value="online">Online</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top: 5px">
                                    <label for="payment_reference" class="form-label">Payment Reference</label>
                                    <input type="text" class="form-control" id="payment_reference"
                                        name="payment_reference" placeholder="e.g., Receipt No, Transaction ID">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="margin-top: 5px">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <input type="text" class="form-control" id="remarks" name="remarks"
                                        placeholder="Additional notes">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info" style="margin-top: 5px">
                                    <strong>Note:</strong> This will mark all selected invoices as paid with their full
                                    remaining amounts.
                                    The system will automatically handle arrears, advance payments, and admission logic as
                                    per the single payment process.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="confirmPayment">
                            <i class="ri-check-line"></i> Confirm Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function() {
            let selectedInvoices = [];
            let allInvoices = [];
            
            // Prevent loading modal from being closed during processing
            $('#loadingModal').on('hide.bs.modal', function (e) {
                if (typeof isProcessing !== 'undefined' && isProcessing) {
                    e.preventDefault();
                    return false;
                }
            });

            // Load academic years when branch changes
            $('#branch_id').change(function() {
                const branchId = $(this).val();
                console.log({
                    branchId: branchId
                })
                if (branchId) {
                    console.log({
                        branchId: branchId
                    })
                    loadAcademicYears(branchId);
                } else {
                    $('#academic_year_id').html('<option value="">All Academic Years</option>');
                    $('#fee_period_id').html('<option value="">All Fee Periods</option>');
                }
            });

            // Load fee periods when academic year changes
            $('#academic_year_id').change(function() {
                const academicYearId = $(this).val();
                const branchId = $('#branch_id').val();

                if (academicYearId && branchId) {
                    loadFeePeriods(academicYearId, branchId);
                } else {
                    $('#fee_period_id').html('<option value="">All Fee Periods</option>');
                }
            });

            // Load academic years
            function loadAcademicYears(branchId) {
                $.ajax({
                    url: '{{ route('list-academic-years') }}',
                    method: 'GET',
                    data: {
                        id: branchId
                    },
                    success: function(response) {
                        let options = '<option value="">All Academic Years</option>';
                        if (response && response.length > 0) {
                            response.forEach(function(item) {
                                options +=
                                    `<option value="${item.academic_year.id}">${item.academic_year.title}</option>`;
                            });
                        }
                        $('#academic_year_id').html(options);
                        $('#fee_period_id').html('<option value="">All Fee Periods</option>');
                    },
                    error: function() {
                        $('#academic_year_id').html('<option value="">All Academic Years</option>');
                        $('#fee_period_id').html('<option value="">All Fee Periods</option>');
                    }
                });
            }

            // Load fee periods
            function loadFeePeriods(academicYearId, branchId) {
                $.ajax({
                    url: '{{ route('get-fee-periods-by-branch') }}',
                    method: 'GET',
                    data: {
                        academic_year_id: academicYearId,
                        branch_id: branchId
                    },
                    success: function(response) {
                        let options = '<option value="">All Fee Periods</option>';
                        if (response.fee_periods) {
                            console.log({
                                fee: response.fee_periods
                            })
                            response.fee_periods.forEach(function(period) {
                                options +=
                                    `<option value="${period.id}">${period.period_name + ' - ' + period.from_date + ' to ' + period.to_date}</option>`;
                            });
                        }
                        $('#fee_period_id').html(options);
                    },
                    error: function() {
                        $('#fee_period_id').html('<option value="">All Fee Periods</option>');
                    }
                });
            }

            // Load invoices
            $('#loadInvoices').click(function() {
                const branchId = $('#branch_id').val();
                if (!branchId) {
                    Swal.fire({
                        html: '<div class="mt-3">' +
                            '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                            '<div class="mt-4 pt-2 fs-15">' +
                            '<h4>Warning !</h4>' +
                            '<p class="text-muted mx-4 mb-0">Please select a branch to continue.</p>' +
                            '</div></div>',
                        showCancelButton: !0,
                        showConfirmButton: !1,
                        cancelButtonClass: "btn btn-primary w-xs mb-1",
                        cancelButtonText: "Okay",
                        buttonsStyling: !1,
                        showCloseButton: !0
                    });
                    return;
                }

                const data = {
                    branch_id: branchId,
                    academic_year_id: $('#academic_year_id').val(),
                    fee_period_id: $('#fee_period_id').val()
                };

                $.ajax({
                    url: '{{ route('get-unpaid-invoices') }}',
                    method: 'GET',
                    data: data,
                    beforeSend: function() {
                        $('#loadInvoices').prop('disabled', true).html(
                            '<i class="ri-loader-4-line"></i> Loading...');
                    },
                    success: function(response) {
                        if (response.success) {
                            allInvoices = response.invoices;
                            displayInvoices(response.invoices);
                            $('#invoicesSection').show();
                        } else {
                            Swal.fire({
                                html: '<div class="mt-3">' +
                                    '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                    '<div class="mt-4 pt-2 fs-15">' +
                                    '<h4>Error !</h4>' +
                                    '<p class="text-muted mx-4 mb-0">Error loading invoices: ' +
                                    response.message + '</p>' +
                                    '</div></div>',
                                showCancelButton: !0,
                                showConfirmButton: !1,
                                cancelButtonClass: "btn btn-primary w-xs mb-1",
                                cancelButtonText: "Okay",
                                buttonsStyling: !1,
                                showCloseButton: !0
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Error !</h4>' +
                                '<p class="text-muted mx-4 mb-0">Error loading invoices. Please try again.</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    },
                    complete: function() {
                        $('#loadInvoices').prop('disabled', false).html(
                            '<i class="ri-search-line"></i> Load Invoices');
                    }
                });
            });

            // Display invoices in table
            function displayInvoices(invoices) {
                let tbody = '';

                if (invoices.length === 0) {
                    tbody = '<tr><td colspan="10" class="text-center">No unpaid invoices found</td></tr>';
                } else {
                    invoices.forEach(function(invoice) {
                        const statusClass = invoice.bank_payment_status === 'unpaid' ? 'danger' : 'warning';
                        const statusText = invoice.bank_payment_status === 'unpaid' ? 'Unpaid' : 'Paid';

                        tbody += `
                    <tr>
                        <td>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input invoice-checkbox" 
                                       value="${invoice.id}" data-amount="${invoice.remaining_amount}">
                            </div>
                        </td>
                        <td>${invoice.student?.first_name + ' ' + invoice.student?.last_name}</td>
                        <td>${invoice.student.registration_no || 'N/A'}</td>
                        <td>${invoice.student.system_id || 'N/A'}</td>
                        <td>${invoice.invoice_frequency}</td>
                        <td>${invoice.fee_period ? invoice.fee_period.period_name + ' - ' + invoice.fee_period.from_date + ' to ' + invoice.fee_period.to_date : 'N/A'}</td>
                        <td>${parseFloat(invoice.total_payable).toFixed(2)}</td>
                        <td>${parseFloat(invoice.total_paid).toFixed(2)}</td>
                        <td>${parseFloat(invoice.remaining_amount).toFixed(2)}</td>
                        <td><span class="badge bg-${statusClass}">${statusText}</span></td>
                    </tr>
                `;
                    });
                }

                $('#invoicesTable tbody').html(tbody);
                updateButtonStates();
            }

            // Select all functionality
            $('#selectAll').change(function() {
                const isChecked = $(this).is(':checked');
                $('.invoice-checkbox').prop('checked', isChecked);
                updateSelectedInvoices();
            });

            // Individual checkbox change
            $(document).on('change', '.invoice-checkbox', function() {
                updateSelectedInvoices();

                // Update select all checkbox
                const totalCheckboxes = $('.invoice-checkbox').length;
                const checkedCheckboxes = $('.invoice-checkbox:checked').length;

                if (checkedCheckboxes === 0) {
                    $('#selectAll').prop('indeterminate', false).prop('checked', false);
                } else if (checkedCheckboxes === totalCheckboxes) {
                    $('#selectAll').prop('indeterminate', false).prop('checked', true);
                } else {
                    $('#selectAll').prop('indeterminate', true);
                }
            });

            // Update selected invoices array
            function updateSelectedInvoices() {
                selectedInvoices = [];
                $('.invoice-checkbox:checked').each(function() {
                    selectedInvoices.push($(this).val());
                });
                updateButtonStates();
            }

            // Update button states
            function updateButtonStates() {
                const hasInvoices = allInvoices.length > 0;
                const hasSelection = selectedInvoices.length > 0;

                $('#markAllAsPaid').prop('disabled', !hasInvoices);
                $('#markSelectedAsPaid').prop('disabled', !hasSelection);
            }

            // Mark all as paid
            $('#markAllAsPaid').click(function() {
                selectedInvoices = allInvoices.map(invoice => invoice.id);
                showPaymentModal();
            });

            // Mark selected as paid
            $('#markSelectedAsPaid').click(function() {
                showPaymentModal();
            });

            // Show payment modal
            function showPaymentModal() {
                const totalAmount = selectedInvoices.reduce((sum, invoiceId) => {
                    const invoice = allInvoices.find(inv => inv.id == invoiceId);
                    return sum + parseFloat(invoice.remaining_amount);
                }, 0);

                $('#paymentModalLabel').html(
                    `Mark ${selectedInvoices.length} Invoice(s) as Paid - Total: ${totalAmount.toFixed(2)}`);
                $('#paymentModal').modal('show');
            }

            // Handle payment form submission
            // Modify this function to send requests in batches
            $('#paymentForm').submit(function(e) {
                e.preventDefault();

                // Prevent multiple submissions
                if ($('#confirmPayment').prop('disabled')) {
                    return;
                }

                if (selectedInvoices.length === 0) {
                    Swal.fire({
                        html: '<div class="mt-3">' +
                            '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                            '<div class="mt-4 pt-2 fs-15">' +
                            '<h4>Warning !</h4>' +
                            '<p class="text-muted mx-4 mb-0">Please select at least one invoice to mark as paid.</p>' +
                            '</div></div>',
                        showCancelButton: !0,
                        showConfirmButton: !1,
                        cancelButtonClass: "btn btn-primary w-xs mb-1",
                        cancelButtonText: "Okay",
                        buttonsStyling: !1,
                        showCloseButton: !0
                    });
                    return;
                }

                // Batch the invoices into smaller chunks (e.g., 100 invoices per batch)
                const batchSize = 100;
                let batches = [];
                for (let i = 0; i < selectedInvoices.length; i += batchSize) {
                    batches.push(selectedInvoices.slice(i, i + batchSize));
                }

                let currentBatch = 0;
                let totalBatches = batches.length;
                let processedInvoices = 0;
                let failedInvoices = 0;
                let isProcessing = true;

                // Disable the confirm button to prevent multiple submissions
                $('#confirmPayment').prop('disabled', true).html('<i class="ri-loader-4-line"></i> Processing...');

                // Close payment modal and show loading modal
                $('#paymentModal').modal('hide');
                $('#loadingModal').modal('show');
                updateProgress(0, totalBatches);

                // Start processing batches
                processBatch(currentBatch);

                function processBatch(batchIndex) {
                    // Check if all batches are processed at the beginning
                    if (batchIndex >= batches.length) {
                        // All batches processed
                        isProcessing = false;
                        $('#loadingModal').modal('hide');
                        
                        // Show success message
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success !</h4>' +
                                '<p class="text-muted mx-4 mb-0">Successfully processed ' + processedInvoices + ' invoices as paid.' + 
                                (failedInvoices > 0 ? ' ' + failedInvoices + ' invoices failed.' : '') + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        }).then(function() {
                            $('#paymentModal').modal('hide');
                            // Reset the confirm button
                            $('#confirmPayment').prop('disabled', false).html('<i class="ri-check-line"></i> Confirm Payment');
                            $('#loadInvoices').click(); // Reload invoices
                        });
                        return;
                    }

                    const batch = batches[batchIndex];
                    const formData = {
                        invoice_ids: batch,
                        paid_date: $('#paid_date').val(),
                        payment_method: $('#payment_method').val(),
                        payment_reference: $('#payment_reference').val(),
                        remarks: $('#remarks').val()
                    };

                    // Update progress
                    updateProgress(batchIndex, totalBatches);

                    $.ajax({
                        url: '{{ route('bulk-mark-as-paid-process') }}',
                        method: 'POST',
                        data: formData,
                        timeout: 30000, // 30 second timeout
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                processedInvoices += batch.length;
                            } else {
                                failedInvoices += batch.length;
                            }
                            currentBatch++;
                            // Add a small delay before processing next batch to prevent overwhelming the server
                            setTimeout(function() {
                                if (isProcessing) {
                                    processBatch(currentBatch);
                                }
                            }, 100);
                        },
                        error: function(xhr) {
                            failedInvoices += batch.length;
                            currentBatch++;
                            // Add a small delay before processing next batch to prevent overwhelming the server
                            setTimeout(function() {
                                if (isProcessing) {
                                    processBatch(currentBatch);
                                }
                            }, 100);
                        },
                        timeout: function() {
                            failedInvoices += batch.length;
                            currentBatch++;
                            // Add a small delay before processing next batch to prevent overwhelming the server
                            setTimeout(function() {
                                if (isProcessing) {
                                    processBatch(currentBatch);
                                }
                            }, 100);
                        }
                    });
                }

                function updateProgress(currentBatch, totalBatches) {
                    const progress = Math.round((currentBatch / totalBatches) * 100);
                    $('#progressBar').css('width', progress + '%');
                    $('#progressText').text('Processing batch ' + (currentBatch + 1) + ' of ' + totalBatches + '...');
                }
            });

            // Clear filters
            $('#clearFilters').click(function() {
                $('#filterForm')[0].reset();
                $('#invoicesSection').hide();
                selectedInvoices = [];
                allInvoices = [];
                updateButtonStates();
            });
        });
    </script>
@endpush
