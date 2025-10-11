@extends('layouts.master')
@section('content')
<div class="container-fluid py-4" style="background: #f8f9fb; min-height: 90vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ route('payrolls.index') }}" class="btn btn-outline-dark btn-sm me-3">
                            <i class="ri-arrow-left-line me-1"></i> Back
                        </a>
                        <div>
                            <h5 class="mb-0">Create Payroll</h5>
                            <small class="text-muted">Process employee payroll for the selected period</small>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('payrolls.store') }}" id="payrollForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="year" class="form-label">Year</label>
                                <select class="form-select" name="year" id="year" required>
                                    @for ($y = date('Y')-2; $y <= date('Y'); $y++)
                                        <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="month" class="form-label">Month</label>
                                <select class="form-select" name="month" id="month" required>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $currentMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="employee_id" class="form-label">Employee</label>
                                <select class="form-select select2" name="employee_id" id="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" data-probation="{{ $emp->probation_end_date }}">
                                            {{ $emp->user->name ?? $emp->preferred_name }} ({{ $emp->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted" id="employee-filter-info">Select year and month first to see available employees</small>
                            </div>
                        </div>
                        
                        <div id="payroll-summary-card" class="mb-4" style="display:none;">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div id="payroll-summary-loading" style="display:none;">
                                        <div class="text-center"><span class="spinner-border spinner-border-sm"></span> Loading...</div>
                                    </div>
                                    <div id="payroll-summary-content">
                                        <div class="row text-center mb-3">
                                            <div class="col">
                                                <div class="fw-bold fs-4 text-success" id="summary-presents">-</div>
                                                <div class="small">Present Days</div>
                                            </div>
                                            <div class="col">
                                                <div class="fw-bold fs-4 text-danger" id="summary-absents">-</div>
                                                <div class="small">Absent Days</div>
                                                <div class="small text-muted" id="absent-breakdown" style="display: none;"></div>
                                            </div>
                                            <div class="col">
                                                <div class="fw-bold fs-4 text-warning" id="summary-late">-</div>
                                                <div class="small">Late Minutes</div>
                                            </div>
                                            <div class="col">
                                                <div class="fw-bold fs-4 text-primary" id="summary-extra">-</div>
                                                <div class="small">Extra Minutes</div>
                                            </div>
                                            <div class="col">
                                                <div class="fw-bold fs-4 text-purple" id="summary-leaves">-</div>
                                                <div class="small">Approved Leaves</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="basic_salary" class="form-label">Basic Salary</label>
                            <input type="number" step="0.01" min="0" class="form-control" name="basic_salary" id="basic_salary" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-white border-0 pb-1">
                                        <strong>Allowances</strong>
                                        <div class="small text-muted mt-1">
                                            <i class="ri-lock-line text-primary"></i> = Permanent (from salary structure) | 
                                            <i class="ri-add-circle-line text-success"></i> = Temporary (current month only)
                                        </div>
                                    </div>
                                    <div class="card-body pt-2 pb-2">
                                        <div id="allowances-list">
                                            <!-- Empty container - permanent allowances load automatically when employee is selected -->
                                            <!-- Temporary allowances are added via "Add Temporary Allowance" button -->
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-success mt-2" id="add-allowance">
                                            <i class="ri-add-circle-line"></i> Add Temporary Allowance
                                        </button>
                                        <div class="small text-muted mt-1">
                                            Add one-time allowances for this month only (e.g., Travel, Bonus)
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(count($deductionTypes) > 0)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-header bg-white border-0 pb-1">
                                        <strong>Additional Deductions</strong>
                                    </div>
                                    <div class="card-body pt-2 pb-2">
                                        <div id="deductions-list">
                                            @foreach ($deductionTypes as $dt)
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <label class="form-label mb-0">{{ $dt->name }}</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="number" step="0.01" min="0"
                                                            class="form-control form-control-sm deduction-amount"
                                                            name="deductions[{{ $dt->id }}][amount]" placeholder="Amount">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Gross Salary</label>
                                <input type="text" class="form-control" id="gross_salary" name="gross_salary" readonly>
                                <small class="text-muted">Total = Basic + All Allowances</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Income Tax <i class="ri-information-line text-info" title="Tax calculated on Basic + Permanent allowances only. Temporary allowances are non-taxable."></i></label>
                                <input type="text" class="form-control" id="income_tax" name="income_tax" readonly>
                                <small id="tax-slab-info" class="text-muted"></small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Provident Fund</label>
                                <input type="text" class="form-control" id="pf_total" name="pf_total" readonly>
                                <small id="pf-breakdown" class="text-muted"></small>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark py-2 fs-5">Calculate & Preview Payroll</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('footer_scripts')
<script>
$(document).ready(function() {
    // Initialize select2 for employee dropdown if select2 is available
    if ($.fn.select2) {
        $('#employee_id').select2({
            width: '100%',
            placeholder: 'Select Employee',
            allowClear: true
        });
    }

    // Dynamic allowances
    // Start from a high index to avoid conflicts with permanent allowances (which use 0-10)
    let allowanceIndex = 1000;
    $('#add-allowance').click(function() {
        let row = `<div class="row allowance-row mb-2" data-permanent="false">
            <div class="col-5">
                <input type="text" class="form-control form-control-sm" name="allowances[${allowanceIndex}][label]" placeholder="Temporary Allowance (e.g., Travel Allowance)">
                <input type="hidden" name="allowances[${allowanceIndex}][is_permanent]" value="0">
            </div>
            <div class="col-5">
                <input type="number" step="0.01" min="0" class="form-control form-control-sm allowance-amount" name="allowances[${allowanceIndex}][amount]" placeholder="Amount">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-sm btn-danger remove-allowance w-100" title="Remove temporary allowance"><i class="ri-delete-bin-line"></i></button>
            </div>
        </div>`;
        $('#allowances-list').append(row);
        allowanceIndex++;
        // New allowances always have delete buttons
    });
    $(document).on('click', '.remove-allowance', function() {
        $(this).closest('.allowance-row').remove();
        // No need to hide/show buttons since first row doesn't have delete button
    });

    // Payroll summary AJAX
    function fetchPayrollSummary() {
        let empId = $('#employee_id').val();
        let year = $('#year').val();
        let month = $('#month').val();
        if (!empId || !year || !month) {
            $('#payroll-summary-card').hide();
            return;
        }
        $('#payroll-summary-card').show();
        $('#payroll-summary-loading').show();
        $('#payroll-summary-content').hide();
        $.ajax({
            url: "{{ route('payrolls.summary') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                employee_id: empId,
                month: month,
                year: year
            },
            success: function(res) {
                $('#payroll-summary-loading').hide();
                $('#payroll-summary-content').show();
                $('#summary-presents').text(res.presents);
                $('#summary-absents').text(res.absents);
                $('#summary-late').text(res.late_minutes);
                $('#summary-extra').text(res.extra_hours);
                $('#summary-leaves').text(res.approved_leaves);
                
                // Display absent days breakdown
                if (res.absent_breakdown && res.absent_breakdown.length > 0) {
                    let breakdownHtml = '<div class="mt-2"><strong>Absent Dates:</strong><br>';
                    res.absent_breakdown.forEach(function(absent) {
                        breakdownHtml += `<span class="badge bg-danger me-1 mb-1">${absent.date} (${absent.day})</span>`;
                    });
                    breakdownHtml += '</div>';
                    $('#absent-breakdown').html(breakdownHtml).show();
                } else {
                    $('#absent-breakdown').hide();
                }

                // Check if this is a future period
                let selectedYear = parseInt($('#year').val());
                let selectedMonth = parseInt($('#month').val());
                let currentDate = new Date();
                let isFuturePeriod = selectedYear > currentDate.getFullYear() || 
                                   (selectedYear == currentDate.getFullYear() && selectedMonth > currentDate.getMonth() + 1);
                
                // Disable preview/process button if no present days and no approved paid leaves
                let hasAttendance = parseInt(res.presents) > 0;
                let hasPaidLeave = false;
                if (res.leave_breakdown && Array.isArray(res.leave_breakdown)) {
                    hasPaidLeave = res.leave_breakdown.some(l => l.with_pay && l.days > 0);
                }
                
                if (isFuturePeriod) {
                    $('#payrollForm button[type="submit"]').prop('disabled', true);
                    if ($('#no-attendance-msg').length === 0) {
                        $('#payrollForm').prepend('<div id="no-attendance-msg" class="alert alert-info">Cannot process payroll: Selected period is in the future. Please select a current or past month.</div>');
                    }
                } else if (!hasAttendance && !hasPaidLeave) {
                    $('#payrollForm button[type="submit"]').prop('disabled', true);
                    if ($('#no-attendance-msg').length === 0) {
                        $('#payrollForm').prepend('<div id="no-attendance-msg" class="alert alert-warning">Cannot process payroll: Employee has no attendance or approved paid leave for this month.</div>');
                    }
                } else {
                    $('#payrollForm button[type="submit"]').prop('disabled', false);
                    $('#no-attendance-msg').remove();
                }
            },
            error: function(xhr, status, error) {
                $('#payroll-summary-loading').hide();
                $('#payroll-summary-content').show();
                $('#summary-presents, #summary-absents, #summary-late, #summary-extra, #summary-leaves').text('-');
            }
        });
    }
    $('#employee_id, #month, #year').change(fetchPayrollSummary);
    
    // Filter employees based on year/month selection (exclude already processed)
    $('#year, #month').change(function() {
        const year = $('#year').val();
        const month = $('#month').val();
        
        if (year && month) {
            filterAvailableEmployees(year, month);
        } else {
            // Clear employee dropdown if year/month not selected
            $('#employee_id').empty().append('<option value="">Select Employee</option>');
            $('#employee-filter-info').text('Select year and month first to see available employees');
        }
        
        // Clear temporary allowances when month/year changes (they are month-specific)
        $('#allowances-list .allowance-row[data-permanent="false"]').remove();
        
        // Clear current employee selection and salary data
        $('#employee_id').val('').trigger('change');
        clearSalaryData();
    });
    
    // Auto-fetch employee salary data when employee is selected
    $('#employee_id').change(function() {
        const employeeId = $(this).val();
        if (employeeId) {
            fetchEmployeeSalaryData(employeeId);
        } else {
            clearSalaryData();
        }
    });

    // Function to filter available employees
    function filterAvailableEmployees(year, month) {
        $('#employee-filter-info').text('Loading available employees...');
        
        $.ajax({
            url: "{{ route('payrolls.available-employees') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                year: year,
                month: month
            },
            success: function(response) {
                console.log('Response:', response); // Debug log
                
                if (response.success && response.employees) {
                    const select = $('#employee_id');
                    select.empty().append('<option value="">Select Employee</option>');
                    
                    // Handle both array and object with numeric keys
                    let employeesArray;
                    if (Array.isArray(response.employees)) {
                        employeesArray = response.employees;
                    } else if (typeof response.employees === 'object') {
                        // Convert object with numeric keys to array
                        employeesArray = Object.values(response.employees);
                    } else {
                        throw new Error('Invalid employees data format');
                    }
                    
                    employeesArray.forEach(function(employee) {
                        const option = $('<option></option>')
                            .attr('value', employee.id)
                            .attr('data-probation', employee.probation_end_date)
                            .text(employee.name + ' (' + employee.employee_id + ')');
                        select.append(option);
                    });
                    
                    // Update info text
                    const infoText = `${response.available_count} available employees (${response.processed_count} already processed for ${month}/${year})`;
                    $('#employee-filter-info').text(infoText);
                    
                    // Initialize Select2 if not already done
                    if (!select.hasClass('select2-hidden-accessible')) {
                        select.select2({
                            placeholder: 'Select Employee',
                            allowClear: true
                        });
                    }
                } else {
                    console.error('Invalid response format:', response);
                    let errorMsg = 'Error loading employees';
                    if (response.message) {
                        errorMsg += ': ' + response.message;
                    } else if (!response.employees) {
                        errorMsg += ': No employees data received';
                    } else {
                        errorMsg += ': Invalid employees data format';
                    }
                    $('#employee-filter-info').text(errorMsg);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText, status, error);
                let errorMsg = 'Error loading available employees';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg += ': ' + xhr.responseJSON.message;
                }
                $('#employee-filter-info').text(errorMsg);
            }
        });
    }

    // Function to fetch employee salary data
    function fetchEmployeeSalaryData(employeeId) {
        $.ajax({
            url: "{{ route('payrolls.salary.employee-salary-data') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                employee_id: employeeId
            },
            success: function(response) {
                if (response.success && response.data) {
                    populateSalaryData(response.data);
                } else {
                    clearSalaryData();
                }
            },
            error: function(xhr, status, error) {
                clearSalaryData();
            }
        });
    }

    // Function to populate salary data
    function populateSalaryData(data) {
        // Set basic salary
        $('#basic_salary').val(data.basic_salary || 0);
        
        // Clear only permanent allowances (those from salary structure)
        // Keep temporary/additional allowances that were manually added
        $('#allowances-list .allowance-row[data-permanent="true"]').remove();
        
        // Add allowances from employee's salary structure (permanent allowances)
        if (data.allowances && data.allowances.length > 0) {
            // Insert permanent allowances at the top of the list
            let permanentAllowancesHtml = '';
            data.allowances.forEach(function(allowance, index) {
                permanentAllowancesHtml += `<div class="row allowance-row mb-2" data-permanent="true">
                    <div class="col-5">
                        <input type="text" class="form-control form-control-sm bg-light" name="allowances[${index}][label]" placeholder="Label" value="${allowance.label}" readonly>
                        <input type="hidden" name="allowances[${index}][is_permanent]" value="1">
                    </div>
                    <div class="col-6">
                        <input type="number" step="0.01" min="0" class="form-control form-control-sm allowance-amount" name="allowances[${index}][amount]" placeholder="Amount" value="${allowance.amount}" readonly>
                    </div>
                    <div class="col-1">
                        <span class="badge bg-primary" title="From Salary Structure">
                            <i class="ri-lock-line"></i>
                        </span>
                    </div>
                </div>`;
            });
            $('#allowances-list').prepend(permanentAllowancesHtml);
        }
        
        // Populate deductions
        if (data.deductions && data.deductions.length > 0) {
            data.deductions.forEach(function(deduction) {
                const deductionInput = $(`input[name="deductions[${deduction.deduction_type_id}][amount]"]`);
                if (deductionInput.length) {
                    deductionInput.val(deduction.amount);
                }
            });
        }
        
        // Store tax preferences for tax calculation
        if (data.tax_preferences) {
            employeeTaxPreferences = data.tax_preferences;
        } else {
            employeeTaxPreferences = null;
        }
        
        // Recalculate totals
        calculateGrossAndTax();
        calculatePF();
    }

    // Function to clear salary data
    function clearSalaryData() {
        $('#basic_salary').val('');
        $('#gross_salary').val('');
        $('#income_tax').val('');
        $('#pf_total').val('');
        $('#tax-slab-info').text('');
        $('#pf-breakdown').text('');
        
        // Clear only permanent allowances from salary structure
        // Keep temporary allowances that were manually added
        $('#allowances-list .allowance-row[data-permanent="true"]').remove();
        
        // Clear deductions
        $('.deduction-amount').val('');
        
        // Clear tax preferences
        employeeTaxPreferences = null;
    }

    // Store employee tax preferences globally
    let employeeTaxPreferences = null;

    // Function to calculate tax using employee preferences
    function calculateTaxFromEmployeeData(grossSalary) {
        let tax = 0;
        let slabInfo = '';
        
        if (employeeTaxPreferences && employeeTaxPreferences.apply_tax) {
            // Calculate taxable income (gross - exemption) - MONTHLY
            const taxExemption = parseFloat(employeeTaxPreferences.tax_exemption_amount) || 0;
            const monthlyTaxableIncome = Math.max(0, grossSalary - taxExemption);
            const annualTaxableIncome = monthlyTaxableIncome * 12; // Convert to annual for slab determination
            
            // Get tax slabs via AJAX
            $.ajax({
                url: "{{ route('payrolls.salary.tax-slabs') }}",
                method: 'GET',
                success: function(taxSlabs) {
                    // Find applicable tax slab based on ANNUAL taxable income
                    let applicableSlab = null;
                    for (let slab of taxSlabs) {
                        if (annualTaxableIncome >= parseFloat(slab.min_salary) && annualTaxableIncome <= parseFloat(slab.max_salary)) {
                            applicableSlab = slab;
                            break;
                        }
                    }
                    
                    if (applicableSlab) {
                        // Calculate ANNUAL tax based on annual taxable income
                        const annualTaxAmount = (annualTaxableIncome * parseFloat(applicableSlab.tax_percent) / 100) + parseFloat(applicableSlab.fixed_amount || 0);
                        // Convert back to monthly tax
                        tax = annualTaxAmount / 12;
                        slabInfo = `Slab: ${applicableSlab.min_salary} - ${applicableSlab.max_salary}, Rate: ${applicableSlab.tax_percent}%`;
                        if (taxExemption > 0) {
                            slabInfo += ` (Taxable: ${monthlyTaxableIncome.toFixed(2)} after ${taxExemption} exemption)`;
                        }
                        slabInfo += ` - Tax on Basic + Permanent allowances only`;
                    } else {
                        slabInfo = 'No applicable tax slab';
                    }
                    
                    $('#income_tax').val(tax.toFixed(2));
                    $('#tax-slab-info').text(slabInfo);
                },
                error: function() {
                    $('#income_tax').val('0.00');
                    $('#tax-slab-info').text('Error loading tax slabs');
                }
            });
        } else {
            $('#income_tax').val('0.00');
            $('#tax-slab-info').text('Tax not applicable');
        }
    }

    function calculateGrossAndTax() {
        let basic = parseFloat($('#basic_salary').val()) || 0;
        let totalGross = basic;
        let taxableGross = basic; // For tax calculation (Basic + Permanent allowances only)
        
        // Calculate total gross (all allowances) and taxable gross (permanent allowances only)
        $('.allowance-amount').each(function() {
            let val = parseFloat($(this).val()) || 0;
            totalGross += val;
            
            // Check if this is a permanent allowance
            let row = $(this).closest('.allowance-row');
            if (row.attr('data-permanent') === 'true') {
                taxableGross += val;
            }
        });
        
        $('#gross_salary').val(totalGross.toFixed(2));
        
        // IMPORTANT: Tax calculation using ONLY taxable gross (Basic + Permanent allowances)
        // Temporary allowances are NOT taxed
        calculateTaxFromEmployeeData(taxableGross);
    }
    
    function calculatePF() {
        let basic = parseFloat($('#basic_salary').val()) || 0;
        let pfEmpPercent = {{ $providentFund ? $providentFund->employee_contribution_percent : 0 }};
        let pfErPercent = {{ $providentFund ? $providentFund->employer_contribution_percent : 0 }};
        let empId = $('#employee_id').val();
        let year = $('#year').val();
        let month = $('#month').val();
        let probationEnd = $('#employee_id option:selected').data('probation');
        
        if (!empId || !year || !month) {
            $('#pf_total').val('0.00');
            $('#pf-breakdown').text('');
            return;
        }
        
        let periodEnd = year + '-' + (month.length === 1 ? '0' + month : month) + '-28';
        let pfApplicable = !probationEnd || (probationEnd && periodEnd > probationEnd);
        
        if (pfApplicable) {
            let pfEmp = basic * pfEmpPercent / 100;
            let pfEr = basic * pfErPercent / 100;
            let pfTotal = pfEmp + pfEr;
            $('#pf_total').val(pfTotal.toFixed(2));
            $('#pf-breakdown').text(`Employee: ${pfEmp.toFixed(2)} (${pfEmpPercent}%) | Employer: ${pfEr.toFixed(2)} (${pfErPercent}%)`);
        } else {
            $('#pf_total').val('0.00');
            $('#pf-breakdown').text('Not applicable (Employee on probation)');
        }
    }
    
    // Event listeners for basic salary, employee, month, year
    $('#basic_salary, #employee_id, #month, #year').on('input change', function() {
        calculateGrossAndTax();
        calculatePF();
    });
    
    // Event delegation for allowance amounts (works with dynamically added permanent allowances)
    $(document).on('input change', '.allowance-amount', function() {
        calculateGrossAndTax();
        calculatePF();
    });
    $('#add-allowance').on('click', function() {
        setTimeout(function() {
            calculateGrossAndTax();
            calculatePF();
        }, 100);
    });
    $(document).on('input', '.allowance-amount', function() {
        calculateGrossAndTax();
        calculatePF();
    });
    $(document).on('input', '.deduction-amount', function() {
        calculateGrossAndTax();
        calculatePF();
    });
    
    // Initialize employee filtering on page load
    $(document).ready(function() {
        const year = $('#year').val();
        const month = $('#month').val();
        if (year && month) {
            filterAvailableEmployees(year, month);
        }
    });
});
</script>
@endpush
@endsection
