@extends('layouts.master')
@section('content')
<div class="container-fluid py-4" style="background: #f8f9fb; min-height: 90vh;">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header align-items-center d-flex justify-content-between">
                    <h4 class="card-title mb-0 flex-grow-1">
                         Payroll List
                    </h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('payrolls.create') }}" class="btn btn-primary me-2">
                            <i class="ri-add-line me-1"></i> Create Payroll
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="alert-area"></div>
                    <div class="table-responsive">
                        <table id="payrolls-table" class="table table-bordered table-striped align-middle table-nowrap mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee</th>
                                    <th>Period</th>
                                    <th>Gross Salary</th>
                                    <th>Attendance</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Payroll View Modal -->
<div class="modal fade" id="payrollViewModal" tabindex="-1" aria-labelledby="payrollViewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-default text-white">
        <h5 class="modal-title" id="payrollViewModalLabel">
            <i class="ri-file-text-line me-2"></i>Payroll Details
        </h5>
        <button type="button" class="btn-close btn-close-red" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="payroll-modal-body">
        <div class="text-center py-5"><span class="spinner-border text-primary"></span></div>
      </div>
    </div>
  </div>
</div>

@push('footer_scripts')
<script type="text/javascript">
$(document).ready(function() {
    var table = $('#payrolls-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        bLengthChange: false,
        pageLength: 10,
        scrollX: true,
        language: {
            search: "",
            processing: "<img class='ucs_loader' src='{{asset('loader.gif')}}' />",
            searchPlaceholder: "Search..."
        },
        ajax: "{{ route('payrolls.index') }}",
        columns: [
            { data: 'id', name: 'id', width: "5%" },
            { data: 'employee', name: 'employee', width: "15%" },
            { data: 'period', name: 'period', width: "10%" },
            { data: 'salary_breakdown', name: 'salary_breakdown', width: "12%" },
            { data: 'attendance', name: 'attendance', width: "12%" },
            { data: 'deductions', name: 'deductions', width: "12%" },
            { data: 'net_salary', name: 'net_salary', width: "10%" },
            { data: 'status', name: 'status', width: "8%", orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, width: "10%", sClass: "text-center" },
        ]
    });

    // View payroll details
    $(document).on('click', '.view-payroll', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        $('#payroll-modal-body').html('<div class="text-center py-5"><span class="spinner-border text-primary"></span></div>');
        $('#payrollViewModal').modal('show');
        
        $.get('/payrolls/' + id, function(res) {
            if (res.success && res.data) {
                var p = res.data;
                var html = buildPayrollModal(p);
                $('#payroll-modal-body').html(html);
            } else {
                $('#payroll-modal-body').html('<div class="alert alert-danger">Could not load payroll details.</div>');
            }
        }).fail(function() {
            $('#payroll-modal-body').html('<div class="alert alert-danger">Error loading payroll details.</div>');
        });
    });

    function buildPayrollModal(p) {
        var html = '';
        
        // Check if this is an old payroll without enhanced fields
        var isOldPayroll = !p.basic_salary || parseFloat(p.basic_salary.replace(/,/g, '')) === 0;
        
        if (isOldPayroll) {
            html += '<div class="alert alert-info alert-dismissible fade show" role="alert">';
            html += '<i class="ri-information-line me-2"></i>';
            html += '<strong>Legacy Payroll:</strong> This payroll was created before the system upgrade. Some details may not be available.';
            html += '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            html += '</div>';
        }
        
        // Employee Information Header
        html += '<div class="card mb-3 border-0 shadow-sm">';
        html += '<div class="card-body bg-light">';
        html += '<div class="row">';
        html += '<div class="col-md-6">';
        html += '<h6 class="text-primary mb-3"><i class="ri-user-line me-2"></i>Employee Information</h6>';
        html += '<div class="mb-2"><strong>Employee ID:</strong> ' + p.employee_id + '</div>';
        html += '<div class="mb-2"><strong>Name:</strong> ' + p.employee_name + '</div>';
        html += '<div class="mb-2"><strong>Department:</strong> ' + p.department + '</div>';
        html += '<div class="mb-2"><strong>Designation:</strong> ' + p.designation + '</div>';
        html += '</div>';
        html += '<div class="col-md-6">';
        html += '<h6 class="text-primary mb-3"><i class="ri-calendar-line me-2"></i>Payroll Information</h6>';
        html += '<div class="mb-2"><strong>Period:</strong> ' + p.period + '</div>';
        html += '<div class="mb-2"><strong>Status:</strong> <span class="badge bg-info">' + p.status + '</span></div>';
        html += '<div class="mb-2"><strong>Processed By:</strong> ' + p.processed_by + '</div>';
        html += '<div class="mb-2"><strong>Processed At:</strong> ' + p.processed_at + '</div>';
        html += '</div>';
        html += '</div>';
        html += '</div></div>';

        // Salary Breakdown
        html += '<div class="row mb-3">';
        html += '<div class="col-md-6">';
        html += '<div class="card border shadow-sm h-100">';
        html += '<div class="card-header bg-white border-bottom"><h6 class="mb-0 text-dark"><i class="ri-money-dollar-circle-line me-2"></i>Salary Breakdown</h6></div>';
        html += '<div class="card-body">';
        html += '<table class="table table-sm mb-0">';
        html += '<tr><td>Basic Salary</td><td class="text-end fw-bold">' + p.basic_salary + '</td></tr>';
        html += '<tr><td>Permanent Allowances</td><td class="text-end text-success">+' + p.permanent_allowances_total + '</td></tr>';
        if (parseFloat(p.temporary_allowances_total.replace(/,/g, '')) > 0) {
            html += '<tr><td>Temporary Allowances <span class="badge bg-success" style="font-size: 0.7rem;">Non-Taxable</span></td><td class="text-end text-success">+' + p.temporary_allowances_total + '</td></tr>';
        }
        html += '<tr class="table-light"><td class="fw-bold">Gross Salary <small class="text-muted fw-normal">(Attendance Adjusted)</small></td><td class="text-end fw-bold text-primary">' + p.gross_salary + '</td></tr>';
        html += '<tr><td><small class="text-muted">Taxable Amount (for tax calc)</small></td><td class="text-end"><small class="text-muted">' + p.taxable_gross_salary + '</small></td></tr>';
        html += '</table>';
        html += '</div></div>';
        html += '</div>';

        // Deductions Summary - SIMPLIFIED: Show all deductions directly from payroll_details
        html += '<div class="col-md-6">';
        html += '<div class="card border shadow-sm h-100">';
        html += '<div class="card-header bg-white border-bottom"><h6 class="mb-0 text-dark"><i class="ri-subtract-line me-2"></i>Deductions Summary</h6></div>';
        html += '<div class="card-body">';
        html += '<table class="table table-sm mb-0">';
        
        // Display all deductions directly from payroll_details table
        if (p.all_deductions && p.all_deductions.length > 0) {
            p.all_deductions.forEach(function(d) {
                if (parseFloat(d.amount.replace(/,/g, '')) > 0) {
                    html += '<tr><td>' + d.label + '</td><td class="text-end text-danger">-' + d.amount + '</td></tr>';
                }
            });
        }
        
        html += '<tr class="table-light"><td class="fw-bold">Total Deductions</td><td class="text-end fw-bold text-danger">' + (p.total_deductions || '0.00') + '</td></tr>';
        html += '<tr class="table-success"><td class="fw-bold">Net Salary</td><td class="text-end fw-bold fs-5 text-success">' + p.net_salary + '</td></tr>';
        html += '</table>';
        html += '</div></div>';
        html += '</div>';
        html += '</div>';

        // Attendance & Leave Summary
        if (!isOldPayroll) {
            html += '<div class="card mb-3 border shadow-sm">';
            html += '<div class="card-header bg-white border-bottom"><h6 class="mb-0 text-dark"><i class="ri-calendar-check-line me-2"></i>Attendance & Leave Summary</h6></div>';
            html += '<div class="card-body">';
            html += '<div class="row text-center">';
            html += '<div class="col"><div class="fw-bold fs-4 text-success">' + (p.present_days || 0) + '</div><small class="text-muted">Present Days</small></div>';
            html += '<div class="col"><div class="fw-bold fs-4 text-danger">' + (p.absent_days || 0) + '</div><small class="text-muted">Absent Days</small></div>';
            html += '<div class="col"><div class="fw-bold fs-4 text-warning">' + (p.late_minutes || 0) + '</div><small class="text-muted">Late Minutes</small></div>';
            html += '<div class="col"><div class="fw-bold fs-4 text-primary">' + (p.extra_minutes || 0) + '</div><small class="text-muted">Extra Minutes</small></div>';
            html += '<div class="col"><div class="fw-bold fs-4 text-info">' + (p.approved_leaves || 0) + '</div><small class="text-muted">Approved Leaves</small></div>';
            html += '<div class="col"><div class="fw-bold fs-4 text-secondary">' + (p.total_working_days || 30) + '</div><small class="text-muted">Working Days</small></div>';
            html += '</div>';
            html += '</div></div>';
        }

        // Allowances Details Row
        html += '<div class="row mb-3">';
        
        // Permanent Allowances Column
        html += '<div class="col-md-6">';
        html += '<div class="card border shadow-sm h-100">';
        html += '<div class="card-header bg-white border-bottom"><h6 class="mb-0 text-dark"><i class="ri-lock-line me-2"></i>Permanent Allowances</h6></div>';
        html += '<div class="card-body">';
        
        if (p.permanent_allowances && p.permanent_allowances.length > 0) {
            html += '<table class="table table-sm table-hover mb-0">';
            html += '<thead class="table-light"><tr><th>Type</th><th class="text-end">Amount</th></tr></thead><tbody>';
            p.permanent_allowances.forEach(function(a) {
                html += '<tr><td><i class="ri-check-line me-1 text-primary"></i>' + a.label + '</td><td class="text-end fw-semibold">' + a.amount + '</td></tr>';
            });
            html += '</tbody></table>';
        } else {
            html += '<div class="text-muted text-center py-3"><i class="ri-information-line me-1"></i>No permanent allowances</div>';
        }
        
        html += '</div></div>';
        html += '</div>';
        
        // Temporary Allowances Column
        html += '<div class="col-md-6">';
        html += '<div class="card border shadow-sm h-100">';
        html += '<div class="card-header bg-white border-bottom"><h6 class="mb-0 text-dark"><i class="ri-gift-line me-2"></i>Temporary Allowances <span class="badge bg-success" style="font-size: 0.7rem;">Non-Taxable</span></h6></div>';
        html += '<div class="card-body">';
        
        if (p.temporary_allowances && p.temporary_allowances.length > 0) {
            html += '<table class="table table-sm table-hover mb-0">';
            html += '<thead class="table-light"><tr><th>Type</th><th class="text-end">Amount</th></tr></thead><tbody>';
            p.temporary_allowances.forEach(function(a) {
                html += '<tr><td><i class="ri-arrow-right-line me-1 text-success"></i>' + a.label + '</td><td class="text-end fw-semibold">' + a.amount + '</td></tr>';
            });
            html += '</tbody></table>';
        } else {
            html += '<div class="text-muted text-center py-3"><i class="ri-information-line me-1"></i>No temporary allowances for this month</div>';
        }
        
        html += '</div></div>';
        html += '</div>';
        html += '</div>';

        // Tax & PF Information
        if (!isOldPayroll) {
            html += '<div class="card border shadow-sm">';
            html += '<div class="card-header bg-white border-bottom"><h6 class="mb-0 text-dark"><i class="ri-percent-line me-2"></i>Tax & PF Information</h6></div>';
            html += '<div class="card-body">';
            html += '<div class="row">';
            html += '<div class="col-md-6">';
            html += '<div class="mb-3"><strong>Tax Slab Applied:</strong><br><small class="text-muted">' + (p.tax_slab_applied || 'N/A') + '</small></div>';
            html += '<div class="mb-3"><strong>Tax Exemption Applied:</strong> <span class="badge bg-secondary">' + (p.tax_exemption_applied || '0.00') + '</span></div>';
            html += '<div class="mb-3"><strong>Taxable Gross Salary:</strong> <span class="text-primary fw-bold">' + (p.taxable_gross_salary || 'N/A') + '</span></div>';
            html += '</div>';
            html += '<div class="col-md-6">';
            html += '<div class="mb-3"><strong>Employee PF Contribution:</strong> <span class="text-warning fw-bold">' + (p.provident_fund_employee || '0.00') + '</span></div>';
            html += '<div class="mb-3"><strong>Employer PF Contribution:</strong> <span class="text-warning fw-bold">' + (p.provident_fund_employer || '0.00') + '</span></div>';
            var empPF = parseFloat((p.provident_fund_employee || '0').replace(/,/g, ''));
            var empRPF = parseFloat((p.provident_fund_employer || '0').replace(/,/g, ''));
            html += '<div class="mb-3"><strong>Total PF for Month:</strong> <span class="badge bg-warning text-dark">' + (empPF + empRPF).toFixed(2) + '</span></div>';
            html += '</div>';
            html += '</div>';
            html += '</div></div>';
        }

        return html;
    }

    // Delete payroll
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/payrolls/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        if (res.success) {
                            table.ajax.reload(null, false);
                            Swal.fire('Deleted!', 'Payroll deleted successfully.', 'success');
                        } else {
                            Swal.fire('Error!', 'Failed to delete payroll.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Failed to delete payroll.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
@endsection
