@extends('layouts.master')

@section('title', 'Bulk Salary Assignment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-group-line"></i> Bulk Salary Assignment
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payrolls.salary.bulk.store') }}" method="POST" id="bulk-salary-form">
                        @csrf
                        
                        <!-- Salary Structure -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6><i class="ri-money-dollar-circle-line"></i> Salary Structure</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Basic Salary <span class="text-danger">*</span></label>
                                        <input type="number" name="basic_salary" id="basic_salary" 
                                               class="form-control @error('basic_salary') is-invalid @enderror" 
                                               step="0.01" min="0" required>
                                        @error('basic_salary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">House Rent Allowance</label>
                                        <input type="number" name="house_rent_allowance" id="house_rent_allowance" 
                                               class="form-control @error('house_rent_allowance') is-invalid @enderror" 
                                               step="0.01" min="0" value="0">
                                        @error('house_rent_allowance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Medical Allowance</label>
                                        <input type="number" name="medical_allowance" id="medical_allowance" 
                                               class="form-control @error('medical_allowance') is-invalid @enderror" 
                                               step="0.01" min="0" value="0">
                                        @error('medical_allowance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Transport Allowance</label>
                                        <input type="number" name="transport_allowance" id="transport_allowance" 
                                               class="form-control @error('transport_allowance') is-invalid @enderror" 
                                               step="0.01" min="0" value="0">
                                        @error('transport_allowance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Other Allowances</label>
                                        <input type="number" name="other_allowances" id="other_allowances" 
                                               class="form-control @error('other_allowances') is-invalid @enderror" 
                                               step="0.01" min="0" value="0">
                                        @error('other_allowances')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Gross Salary</label>
                                        <input type="number" name="gross_salary" id="gross_salary" 
                                               class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Effective Date -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Effective From <span class="text-danger">*</span></label>
                                <input type="date" name="effective_from" id="effective_from" 
                                       class="form-control @error('effective_from') is-invalid @enderror" 
                                       value="{{ date('Y-m-d') }}" required>
                                @error('effective_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Notes</label>
                                <input type="text" name="notes" id="notes" class="form-control" 
                                       placeholder="Bulk salary assignment notes...">
                            </div>
                        </div>

                        <!-- Tax Preferences -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6><i class="ri-percent-line"></i> Tax Preferences</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Tax Slab</label>
                                        <select name="tax_slab_id" id="tax_slab_id" class="form-control">
                                            <option value="">Select Tax Slab</option>
                                            @foreach($taxSlabs as $slab)
                                                <option value="{{ $slab->id }}">
                                                    {{ $slab->min_salary }} - {{ $slab->max_salary }} ({{ $slab->tax_percent }}%)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tax Exemption Amount</label>
                                        <input type="number" name="tax_exemption_amount" id="tax_exemption_amount" 
                                               class="form-control" step="0.01" min="0" value="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Calculated Tax Amount</label>
                                        <input type="number" name="calculated_tax_amount" id="calculated_tax_amount" 
                                               class="form-control" step="0.01" min="0" value="0" readonly>
                                        <small class="text-muted">This amount will be automatically calculated</small>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Apply Tax</label>
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="apply_tax" id="apply_tax" 
                                                   class="form-check-input">
                                            <label class="form-check-label" for="apply_tax">
                                                Apply tax to selected employees
                                            </label>
                                        </div>
                                        <div id="tax-calculation-info" class="mt-2" style="display: none;">
                                            <small class="text-muted">
                                                <span id="tax-slab-info"></span><br>
                                                <span id="tax-amount-info"></span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deduction Preferences -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6><i class="ri-subtract-line"></i> Deduction Preferences</h6>
                            </div>
                            <div class="card-body">
                                <div id="deductions-container">
                                    @php
                                        $oldDeductions = old('deductions', []);
                                        $deductionCount = count($oldDeductions) > 0 ? count($oldDeductions) : 1;
                                    @endphp
                                    
                                    @for($i = 0; $i < $deductionCount; $i++)
                                        <div class="row mb-2 deduction-row">
                                            <div class="col-md-4">
                                                <label class="form-label">Deduction Type</label>
                                                <select name="deductions[{{ $i }}][deduction_type_id]" class="form-control deduction-type">
                                                    <option value="">Select Deduction Type</option>
                                                    @foreach($deductionTypes as $type)
                                                        <option value="{{ $type->id }}" 
                                                                {{ old("deductions.{$i}.deduction_type_id") == $type->id ? 'selected' : '' }}>
                                                            {{ $type->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Amount</label>
                                                <input type="number" name="deductions[{{ $i }}][amount]" class="form-control deduction-amount" 
                                                       step="0.01" min="0" value="{{ old("deductions.{$i}.amount") }}">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Type</label>
                                                <select name="deductions[{{ $i }}][type]" class="form-control deduction-type-select">
                                                    <option value="fixed" {{ old("deductions.{$i}.type", 'fixed') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                    <option value="percentage" {{ old("deductions.{$i}.type") == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Active</label>
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" name="deductions[{{ $i }}][is_active]" class="form-check-input" 
                                                           {{ old("deductions.{$i}.is_active", 'on') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Active</label>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <label class="form-label">&nbsp;</label>
                                                <button type="button" class="btn btn-danger btn-sm remove-deduction" style="display: none;">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-deduction">
                                    <i class="ri-add-line"></i> Add Deduction
                                </button>
                            </div>
                        </div>

                        <!-- Employee Selection -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6><i class="ri-user-line"></i> Select Employees</h6>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary" onclick="selectAll()">
                                            <i class="ri-checkbox-line"></i> Select All
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary" onclick="selectNone()">
                                            <i class="ri-checkbox-blank-line"></i> Select None
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Filters -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Filter by Department</label>
                                        <select id="department-filter" class="form-control">
                                            <option value="">All Departments</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Filter by Designation</label>
                                        <select id="designation-filter" class="form-control">
                                            <option value="">All Designations</option>
                                            @foreach($designations as $designation)
                                                <option value="{{ $designation->id }}">{{ $designation->designation_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Search Employee</label>
                                        <input type="text" id="employee-search" class="form-control" placeholder="Search by name or ID...">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="button" id="clear-filters" class="btn btn-outline-secondary btn-sm w-100">
                                            <i class="ri-refresh-line"></i> Clear
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-default sticky-top">
                                            <tr>
                                                <th width="5%">
                                                    <input type="checkbox" id="select-all" class="form-check-input">
                                                </th>
                                                <th>Employee</th>
                                                <th>Department</th>
                                                <th>Designation</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($employees as $employee)
                                                <tr data-department="{{ $employee->department_id ?? '' }}" 
                                                    data-designation="{{ $employee->designation_id ?? '' }}"
                                                    data-employee-name="{{ strtolower($employee->full_name ?? '') }}"
                                                    data-employee-id="{{ strtolower($employee->employee_id ?? '') }}">
                                                    <td>
                                                        <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}" 
                                                               class="form-check-input employee-checkbox">
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <span class="text-white fw-bold">{{ substr($employee->full_name, 0, 1) }}</span>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                                                <small class="text-muted">{{ $employee->employee_id ?? 'N/A' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $employee->department->department_name ?? 'N/A' }}</td>
                                                    <td>{{ $employee->designation->designation_name ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-warning">
                                                            <i class="ri-alert-line"></i> No Salary Set
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                @if($employees->isEmpty())
                                    <div class="text-center py-4">
                                        <i class="ri-user-check-line text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="text-muted mt-2">All employees already have salary structures</h5>
                                        <p class="text-muted">No employees available for bulk salary assignment.</p>
                                        <a href="{{ route('payrolls.salary.index') }}" class="btn btn-primary">
                                            <i class="ri-arrow-left-line"></i> Back to Salary Management
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="card mb-4" id="summary-card" style="display: none;">
                            <div class="card-header">
                                <h6><i class="ri-information-line"></i> Assignment Summary</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Selected Employees:</strong> <span id="selected-count">0</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Gross Salary per Employee:</strong> <span id="gross-amount">0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                                    <i class="ri-save-line"></i> Assign Salary to Selected Employees
                                </button>
                                <a href="{{ route('payrolls.salary.index') }}" class="btn btn-secondary">
                                    <i class="ri-arrow-left-line"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer_scripts')
<script>
$(document).ready(function() {
    // Calculate gross salary
    function calculateGrossSalary() {
        const basic = parseFloat($('#basic_salary').val()) || 0;
        const hra = parseFloat($('#house_rent_allowance').val()) || 0;
        const medical = parseFloat($('#medical_allowance').val()) || 0;
        const transport = parseFloat($('#transport_allowance').val()) || 0;
        const other = parseFloat($('#other_allowances').val()) || 0;
        
        const gross = basic + hra + medical + transport + other;
        $('#gross_salary').val(gross.toFixed(2));
        $('#gross-amount').text(gross.toFixed(2));
        updateSummary();
    }

    // Tax calculation function
    function calculateTaxSlab() {
        const monthlyGrossSalary = parseFloat($('#gross_salary').val()) || 0;
        const monthlyTaxExemption = parseFloat($('#tax_exemption_amount').val()) || 0;
        const annualTaxExemption = monthlyTaxExemption * 12;
        
        // Calculate taxable income (gross - exemption)
        const monthlyTaxableIncome = Math.max(0, monthlyGrossSalary - monthlyTaxExemption);
        const annualTaxableIncome = monthlyTaxableIncome * 12;
        
        // Check if taxable income is below threshold
        if (annualTaxableIncome < 600000) {
            $('#apply_tax').prop('checked', false);
            return;
        } else {
            $('#apply_tax').prop('checked', true);
        }
        
        // Fetch tax slabs from database via AJAX
        $.ajax({
            url: '{{ route("payrolls.salary.tax-slabs") }}',
            method: 'GET',
            success: function(taxSlabs) {
                // Find applicable tax slab based on ANNUAL TAXABLE INCOME (not gross salary)
                let applicableSlab = null;
                for (let slab of taxSlabs) {
                    if (annualTaxableIncome >= parseFloat(slab.min_salary) && annualTaxableIncome <= parseFloat(slab.max_salary)) {
                        applicableSlab = slab;
                        break;
                    }
                }
                
                // Update tax slab dropdown
                if (applicableSlab) {
                    // Find the corresponding option in the dropdown
                    const taxSlabSelect = $('#tax_slab_id');
                    const options = taxSlabSelect.find('option');
                    let selectedOption = null;
                    
                    options.each(function() {
                        const optionText = $(this).text();
                        if (optionText.includes(applicableSlab.tax_percent + '%')) {
                            selectedOption = $(this);
                            return false; // break
                        }
                    });
                    
                    if (selectedOption && selectedOption.val() !== '') {
                        taxSlabSelect.val(selectedOption.val());
                    } else {
                        taxSlabSelect.val('');
                    }
                }
                
                // Auto-uncheck "Apply Tax" if annual taxable income is below taxable threshold
                const firstSlab = taxSlabs.find(slab => parseFloat(slab.tax_percent) > 0);
                const taxableThreshold = firstSlab ? parseFloat(firstSlab.min_salary) : 600000;
                
                if (annualTaxableIncome < taxableThreshold) {
                    $('#apply_tax').prop('checked', false);
                    $('#calculated_tax_amount').val(0);
                    $('#tax-calculation-info').hide();
                } else {
                    $('#apply_tax').prop('checked', true);
                    
                    // Show tax calculation info
                    if (applicableSlab) {
                        // Read current exemption amount fresh for display
                        const currentExemption = parseFloat($('#tax_exemption_amount').val()) || 0;
                        
                        // Calculate MONTHLY tax based on TAXABLE INCOME: (Annual taxable income * tax rate / 12)
                        const annualTaxAmount = (annualTaxableIncome * parseFloat(applicableSlab.tax_percent) / 100) + parseFloat(applicableSlab.fixed_amount || 0);
                        const monthlyTaxAmount = annualTaxAmount / 12; // Convert annual tax to monthly
                        
                        // Populate the tax amount field (no additional exemption needed as it's already applied to taxable income)
                        $('#calculated_tax_amount').val(monthlyTaxAmount.toFixed(2));
                        
                        $('#tax-slab-info').text(`Tax Slab: ${applicableSlab.tax_percent}% + PKR ${parseFloat(applicableSlab.fixed_amount || 0).toLocaleString()}`);
                        $('#tax-amount-info').text(`Estimated Monthly Tax: PKR ${monthlyTaxAmount.toLocaleString()}${currentExemption > 0 ? ` (based on taxable income after ${currentExemption.toLocaleString()} exemption)` : ''}`);
                        $('#tax-calculation-info').show();
                    } else {
                        // No applicable slab found, clear tax amount
                        $('#calculated_tax_amount').val(0);
                        $('#tax-calculation-info').hide();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Failed to fetch tax slabs:', error);
            }
        });
    }

    // Debounce function to limit AJAX calls
    let taxCalculationTimeout;
    function debouncedCalculateTaxSlab() {
        clearTimeout(taxCalculationTimeout);
        taxCalculationTimeout = setTimeout(function() {
            calculateTaxSlab();
        }, 500); // 500ms delay
    }

    // Bind calculation to all salary inputs
    $('#basic_salary, #house_rent_allowance, #medical_allowance, #transport_allowance, #other_allowances').on('input', function() {
        calculateGrossSalary();
        debouncedCalculateTaxSlab();
    });

    // Bind calculation to tax exemption amount field
    $('#tax_exemption_amount').on('input', function() {
        debouncedCalculateTaxSlab();
    });

    // Select all employees (only visible/filtered ones)
    window.selectAll = function() {
        $('tbody tr:visible .employee-checkbox').prop('checked', true);
        $('#select-all').prop('checked', true);
        updateSummary();
    };

    // Select none (only visible/filtered ones)
    window.selectNone = function() {
        $('tbody tr:visible .employee-checkbox').prop('checked', false);
        $('#select-all').prop('checked', false);
        updateSummary();
    };

    // Select all checkbox
    $('#select-all').change(function() {
        $('tbody tr:visible .employee-checkbox').prop('checked', this.checked);
        updateSummary();
    });

    // Individual employee checkbox
    $(document).on('change', '.employee-checkbox', function() {
        const visibleCheckboxes = $('tbody tr:visible .employee-checkbox').length;
        const checkedVisibleCheckboxes = $('tbody tr:visible .employee-checkbox:checked').length;
        $('#select-all').prop('checked', visibleCheckboxes > 0 && visibleCheckboxes === checkedVisibleCheckboxes);
        updateSummary();
    });

    // Deduction management
    // Initialize deduction index based on existing rows
    let deductionIndex = $('.deduction-row').length - 1;

    // Add deduction row
    $('#add-deduction').click(function() {
        deductionIndex++;
        const deductionRow = `
            <div class="row mb-2 deduction-row">
                <div class="col-md-4">
                    <label class="form-label">Deduction Type</label>
                    <select name="deductions[${deductionIndex}][deduction_type_id]" class="form-control deduction-type">
                        <option value="">Select Deduction Type</option>
                        @foreach($deductionTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Amount</label>
                    <input type="number" name="deductions[${deductionIndex}][amount]" class="form-control deduction-amount" 
                           step="0.01" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Type</label>
                    <select name="deductions[${deductionIndex}][type]" class="form-control deduction-type-select">
                        <option value="fixed">Fixed Amount</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Active</label>
                    <div class="form-check mt-2">
                        <input type="checkbox" name="deductions[${deductionIndex}][is_active]" class="form-check-input" checked>
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-sm remove-deduction">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        `;
        $('#deductions-container').append(deductionRow);
        updateRemoveButtons();
    });

    // Remove deduction row
    $(document).on('click', '.remove-deduction', function() {
        $(this).closest('.deduction-row').remove();
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        const rows = $('.deduction-row').length;
        
        // Hide delete button on the first row (index 0) always
        $('.deduction-row').eq(0).find('.remove-deduction').hide();
        
        // Show delete buttons on all other rows (dynamically added rows)
        if (rows > 1) {
            $('.deduction-row').slice(1).find('.remove-deduction').show();
        } else {
            $('.deduction-row').slice(1).find('.remove-deduction').hide();
        }
    }

    // Update summary
    function updateSummary() {
        const selectedCount = $('tbody tr:visible .employee-checkbox:checked').length;
        $('#selected-count').text(selectedCount);
        
        if (selectedCount > 0) {
            $('#summary-card').show();
            $('#submit-btn').prop('disabled', false);
        } else {
            $('#summary-card').hide();
            $('#submit-btn').prop('disabled', true);
        }
    }

    // Filter employees
    function filterEmployees() {
        try {
            const departmentFilter = $('#department-filter').val();
            const designationFilter = $('#designation-filter').val();
            const searchTerm = String($('#employee-search').val() || '').toLowerCase();
            
            $('tbody tr').each(function() {
                const $row = $(this);
                const department = $row.data('department');
                const designation = $row.data('designation');
                const employeeName = $row.data('employee-name');
                const employeeId = $row.data('employee-id');
                
                // Ensure both are strings
                const safeEmployeeId = String(employeeId || '');
                const safeEmployeeName = String(employeeName || '');
                
                let showRow = true;
                
                // Filter by department
                if (departmentFilter && String(department) !== String(departmentFilter)) {
                    showRow = false;
                }
                
                // Filter by designation
                if (designationFilter && String(designation) !== String(designationFilter)) {
                    showRow = false;
                }
                
                // Filter by search term
                if (searchTerm && !safeEmployeeName.includes(searchTerm) && !safeEmployeeId.includes(searchTerm)) {
                    showRow = false;
                }
                
                if (showRow) {
                    $row.show();
                } else {
                    $row.hide();
                    // Uncheck hidden rows
                    $row.find('.employee-checkbox').prop('checked', false);
                }
            });
            
            // Update select all checkbox
            const visibleCheckboxes = $('tbody tr:visible .employee-checkbox');
            const checkedVisibleCheckboxes = $('tbody tr:visible .employee-checkbox:checked');
            $('#select-all').prop('checked', visibleCheckboxes.length > 0 && visibleCheckboxes.length === checkedVisibleCheckboxes.length);
            
            updateSummary();
        } catch (error) {
            console.error('Error in filterEmployees:', error);
        }
    }

    // Bind filter events (with safety check)
    $(document).ready(function() {
        $('#department-filter, #designation-filter, #employee-search').on('change input', filterEmployees);
        
        // Clear filters
        $('#clear-filters').click(function() {
            $('#department-filter').val('');
            $('#designation-filter').val('');
            $('#employee-search').val('');
            filterEmployees();
        });
    });

    // Form validation
    $('#bulk-salary-form').submit(function(e) {
        e.preventDefault();
        const selectedCount = $('tbody tr:visible .employee-checkbox:checked').length;
        const basicSalary = $('#basic_salary').val();
        
        if (selectedCount === 0) {
            Swal.fire({
                title: 'No Employees Selected',
                text: 'Please select at least one employee to assign salary.',
                icon: 'warning',
                confirmButtonText: 'Okay',
                confirmButtonClass: 'btn btn-primary w-xs mb-1',
                buttonsStyling: false
            });
            return false;
        }
        
        if (!basicSalary || parseFloat(basicSalary) <= 0) {
            Swal.fire({
                title: 'Invalid Basic Salary',
                text: 'Please enter a valid basic salary amount.',
                icon: 'warning',
                confirmButtonText: 'Okay',
                confirmButtonClass: 'btn btn-primary w-xs mb-1',
                buttonsStyling: false
            });
            return false;
        }
        
        // Show confirmation dialog
        Swal.fire({
            html: '<div class="mt-3">' +
                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                '<div class="pt-2 mx-5 mt-4 fs-15">' +
                '<h4>Confirm Salary Assignment</h4>' +
                '<p class="mx-4 mb-0 text-muted">Are you sure you want to assign salary to <strong>' + selectedCount + '</strong> employees?</p>' +
                '</div>' +
                '</div>',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary w-xs me-2 mb-1',
            confirmButtonText: 'Yes, Assign Salary',
            cancelButtonClass: 'btn btn-secondary w-xs mb-1',
            cancelButtonText: 'Cancel',
            buttonsStyling: false,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show processing modal
                Swal.fire({
                    html: '<div class="mt-3">' +
                        '<lord-icon src="https://cdn.lordicon.com/kthelypq.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:100px;height:100px"></lord-icon>' +
                        '<div class="pt-2 mx-5 mt-4 fs-15">' +
                        '<h4>Processing...</h4>' +
                        '<p class="mx-4 mb-0 text-muted">Please wait while we assign salaries to the selected employees.</p>' +
                        '</div>' +
                        '</div>',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form via AJAX
                const form = this;
                const formData = new FormData(form);
                
                $.ajax({
                    url: form.action,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Hide processing modal
                        Swal.close();
                        
                        // Show success message
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/lupuorrc.json" trigger="loop" colors="primary:#0ab39c,secondary:#405189" style="width:120px;height:120px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Success!</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + response.message + '</p>' +
                                '<p class="text-success mx-4 mb-0"><strong>Processed: ' + response.processed_count + ' out of ' + response.total_count + ' employees</strong></p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "View Salary Management",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.cancel) {
                                window.location.href = '{{ route("payrolls.salary.index") }}';
                            }
                        });
                    },
                    error: function(xhr) {
                        // Hide processing modal
                        Swal.close();
                        
                        let errorMessage = 'An error occurred while processing the salary assignments.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('<br>');
                        }
                        
                        // Show error message
                        Swal.fire({
                            html: '<div class="mt-3">' +
                                '<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>' +
                                '<div class="mt-4 pt-2 fs-15">' +
                                '<h4>Error!</h4>' +
                                '<p class="text-muted mx-4 mb-0">' + errorMessage + '</p>' +
                                '</div></div>',
                            showCancelButton: !0,
                            showConfirmButton: !1,
                            cancelButtonClass: "btn btn-primary w-xs mb-1",
                            cancelButtonText: "Okay",
                            buttonsStyling: !1,
                            showCloseButton: !0
                        });
                    }
                });
            }
        });
    });

    // Initial calculation
    calculateGrossSalary();
    calculateTaxSlab();
    
    // Initialize deduction buttons properly
    setTimeout(function() {
        updateRemoveButtons();
    }, 100);
});
</script>
@endpush
