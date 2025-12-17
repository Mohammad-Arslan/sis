@extends('layouts.master')

@section('title', 'Edit Employee Salary')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ri-edit-line"></i> Edit Employee Salary - {{ $employee->full_name }}
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-line"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line"></i> Please correct the following errors:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('payrolls.salary.update', $employee->id) }}" method="POST" id="salary-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Employee Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Employee</label>
                                <input type="text" class="form-control" value="{{ $employee->full_name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Employee Details</label>
                                <div class="form-control-plaintext">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <strong>Department:</strong> {{ $employee->department->department_name ?? 'N/A' }}
                                        </div>
                                        <div>
                                            <strong>Designation:</strong> {{ $employee->designation->designation_name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                               step="0.01" min="0" 
                                               value="{{ old('basic_salary', $employee->currentSalaryStructure->basic_salary ?? '') }}" required>
                                        @error('basic_salary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">House Rent Allowance</label>
                                        <input type="number" name="house_rent_allowance" id="house_rent_allowance" 
                                               class="form-control @error('house_rent_allowance') is-invalid @enderror" 
                                               step="0.01" min="0" 
                                               value="{{ old('house_rent_allowance', $employee->currentSalaryStructure->house_rent_allowance ?? 0) }}">
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
                                               step="0.01" min="0" 
                                               value="{{ old('medical_allowance', $employee->currentSalaryStructure->medical_allowance ?? 0) }}">
                                        @error('medical_allowance')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Transport Allowance</label>
                                        <input type="number" name="transport_allowance" id="transport_allowance" 
                                               class="form-control @error('transport_allowance') is-invalid @enderror" 
                                               step="0.01" min="0" 
                                               value="{{ old('transport_allowance', $employee->currentSalaryStructure->transport_allowance ?? 0) }}">
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
                                               step="0.01" min="0" 
                                               value="{{ old('other_allowances', $employee->currentSalaryStructure->other_allowances ?? 0) }}">
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
                                                <option value="{{ $slab->id }}" 
                                                        {{ old('tax_slab_id', $employee->taxPreferences && $employee->taxPreferences->tax_slab_id == $slab->id ? $slab->id : '') == $slab->id ? 'selected' : '' }}>
                                                    {{ $slab->min_salary }} - {{ $slab->max_salary }} ({{ $slab->tax_percent }}%)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tax Exemption Amount</label>
                                        <input type="number" name="tax_exemption_amount" id="tax_exemption_amount" 
                                               class="form-control" step="0.01" min="0" 
                                               value="{{ old('tax_exemption_amount', $employee->taxPreferences->tax_exemption_amount ?? 0) }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Calculated Tax Amount</label>
                                        <input type="number" name="calculated_tax_amount" id="calculated_tax_amount" 
                                               class="form-control" step="0.01" min="0" value="0" readonly>
                                        <small class="text-muted">This amount will be automatically calculated</small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Apply Tax</label>
                                        <div class="form-check mt-2">
                                            <input type="checkbox" name="apply_tax" id="apply_tax" 
                                                   class="form-check-input" 
                                                   {{ old('apply_tax', $employee->taxPreferences ? $employee->taxPreferences->apply_tax : false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="apply_tax">
                                                Apply tax to this employee
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
                                    @if($employee->deductionPreferences->count() > 0)
                                        @foreach($employee->deductionPreferences as $index => $deduction)
                                            <div class="row mb-2 deduction-row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Deduction Type</label>
                                                    <select name="deductions[{{ $index }}][deduction_type_id]" class="form-control deduction-type">
                                                        <option value="">Select Deduction Type</option>
                                                        @foreach($deductionTypes as $type)
                                                            <option value="{{ $type->id }}" 
                                                                    {{ $deduction->deduction_type_id == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Amount</label>
                                                    <input type="number" name="deductions[{{ $index }}][amount]" 
                                                           class="form-control deduction-amount" 
                                                           step="0.01" min="0" value="{{ $deduction->amount }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Type</label>
                                                    <select name="deductions[{{ $index }}][type]" class="form-control deduction-type-select">
                                                        <option value="fixed" {{ $deduction->type == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                                                        <option value="percentage" {{ $deduction->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Active</label>
                                                    <div class="form-check mt-2">
                                                        <input type="checkbox" name="deductions[{{ $index }}][is_active]" 
                                                               class="form-check-input" 
                                                               {{ $deduction->is_active ? 'checked' : '' }}>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="row mb-2 deduction-row">
                                            <div class="col-md-4">
                                                <label class="form-label">Deduction Type</label>
                                                <select name="deductions[0][deduction_type_id]" class="form-control deduction-type">
                                                    <option value="">Select Deduction Type</option>
                                                    @foreach($deductionTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Amount</label>
                                                <input type="number" name="deductions[0][amount]" class="form-control deduction-amount" 
                                                       step="0.01" min="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Type</label>
                                                <select name="deductions[0][type]" class="form-control deduction-type-select">
                                                    <option value="fixed">Fixed Amount</option>
                                                    <option value="percentage">Percentage</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Active</label>
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" name="deductions[0][is_active]" class="form-check-input" checked>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-deduction">
                                    <i class="ri-add-line"></i> Add Deduction
                                </button>
                            </div>
                        </div>

                        <!-- Effective Dates -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Effective From <span class="text-danger">*</span></label>
                                <input type="date" name="effective_from" id="effective_from" 
                                       class="form-control @error('effective_from') is-invalid @enderror" 
                                       value="{{ old('effective_from', $employee->currentSalaryStructure->effective_from->format('Y-m-d') ?? date('Y-m-d')) }}" required>
                                @error('effective_from')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Effective To</label>
                                <input type="date" name="effective_to" id="effective_to" 
                                       class="form-control @error('effective_to') is-invalid @enderror"
                                       value="{{ old('effective_to', $employee->currentSalaryStructure->effective_to ? $employee->currentSalaryStructure->effective_to->format('Y-m-d') : '') }}">
                                @error('effective_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty for indefinite period</small>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                          placeholder="Additional notes about this salary structure...">{{ old('notes', $employee->currentSalaryStructure->notes ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line"></i> Update Salary Structure
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
    let deductionIndex = {{ $employee->deductionPreferences->count() }};

    // Calculate gross salary
    function calculateGrossSalary() {
        const basic = parseFloat($('#basic_salary').val()) || 0;
        const hra = parseFloat($('#house_rent_allowance').val()) || 0;
        const medical = parseFloat($('#medical_allowance').val()) || 0;
        const transport = parseFloat($('#transport_allowance').val()) || 0;
        const other = parseFloat($('#other_allowances').val()) || 0;
        
        const gross = basic + hra + medical + transport + other;
        $('#gross_salary').val(gross.toFixed(2));
    }

    function calculateTaxSlab() {
        const monthlyGrossSalary = parseFloat($('#gross_salary').val()) || 0;
        const monthlyTaxExemption = parseFloat($('#tax_exemption_amount').val()) || 0;
        const annualTaxExemption = monthlyTaxExemption * 12;
        
        // Calculate taxable income (gross - exemption)
        const monthlyTaxableIncome = Math.max(0, monthlyGrossSalary - monthlyTaxExemption);
        const annualTaxableIncome = monthlyTaxableIncome * 12;
        
        console.log('Monthly gross salary:', monthlyGrossSalary);
        console.log('Monthly tax exemption:', monthlyTaxExemption);
        console.log('Monthly taxable income:', monthlyTaxableIncome);
        console.log('Annual taxable income:', annualTaxableIncome);
        
        // Check if taxable income is below threshold
        if (annualTaxableIncome < 600000) {
            $('#apply_tax').prop('checked', false);
            $('#calculated_tax_amount').val(0);
            $('#tax-calculation-info').hide();
            console.log('Taxable income below 600k threshold, unchecking Apply Tax');
            return;
        } else {
            $('#apply_tax').prop('checked', true);
            console.log('Taxable income above 600k threshold, checking Apply Tax');
        }
        
        // Fetch tax slabs from database via AJAX
        $.ajax({
            url: '{{ route("payrolls.salary.tax-slabs") }}',
            method: 'GET',
            success: function(taxSlabs) {
                console.log('Tax slabs fetched:', taxSlabs);
                
                // Find applicable tax slab based on ANNUAL TAXABLE INCOME (not gross salary)
                let applicableSlab = null;
                for (let slab of taxSlabs) {
                    if (annualTaxableIncome >= parseFloat(slab.min_salary) && annualTaxableIncome <= parseFloat(slab.max_salary)) {
                        applicableSlab = slab;
                        break;
                    }
                }
                
                console.log('Applicable slab (based on annual taxable income):', applicableSlab);
                
                // Update tax slab dropdown
                if (applicableSlab) {
                    // Find the corresponding option in the dropdown
                    const taxSlabSelect = $('#tax_slab_id');
                    const options = taxSlabSelect.find('option');
                    let selectedOption = null;
                    
                    options.each(function() {
                        const optionText = $(this).text();
                        console.log('Checking option:', optionText, 'against slab:', applicableSlab.tax_percent + '%');
                        if (optionText.includes(applicableSlab.tax_percent + '%')) {
                            selectedOption = $(this);
                            return false; // break
                        }
                    });
                    
                    if (selectedOption && selectedOption.val() !== '') {
                        taxSlabSelect.val(selectedOption.val());
                        console.log('Selected tax slab option:', selectedOption.val());
                    } else {
                        // If no exact match, keep "Auto" selected
                        taxSlabSelect.val('');
                        console.log('No matching option found, keeping Auto selected');
                    }
                }
                
                // Auto-uncheck "Apply Tax" if annual taxable income is below taxable threshold
                const firstSlab = taxSlabs.find(slab => parseFloat(slab.tax_percent) > 0);
                const taxableThreshold = firstSlab ? parseFloat(firstSlab.min_salary) : 600000;
                
                console.log('Annual taxable threshold:', taxableThreshold);
                
                if (annualTaxableIncome < taxableThreshold) {
                    $('#apply_tax').prop('checked', false);
                    $('#calculated_tax_amount').val(0);
                    $('#tax-calculation-info').hide();
                    console.log('Annual taxable income below threshold, unchecking Apply Tax');
                } else {
                    // If annual taxable income is above taxable threshold, check the box
                    $('#apply_tax').prop('checked', true);
                    console.log('Annual taxable income above threshold, checking Apply Tax');
                    
                    // Show tax calculation info
                    if (applicableSlab) {
                        // Read current exemption amount fresh for display
                        const currentExemption = parseFloat($('#tax_exemption_amount').val()) || 0;
                        
                        // Calculate MONTHLY tax based on TAXABLE INCOME: (Annual taxable income * tax rate / 12)
                        const annualTaxAmount = (annualTaxableIncome * parseFloat(applicableSlab.tax_percent) / 100) + parseFloat(applicableSlab.fixed_amount || 0);
                        const monthlyTaxAmount = annualTaxAmount / 12; // Convert annual tax to monthly
                        
                        console.log('Annual taxable income:', annualTaxableIncome);
                        console.log('Annual tax amount (based on taxable income):', annualTaxAmount);
                        console.log('Monthly tax amount:', monthlyTaxAmount);
                        console.log('Current exemption amount for display:', currentExemption);
                        
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
                console.error('Response:', xhr.responseText);
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

    // Bind calculation to all salary inputs with debouncing
    $('#basic_salary, #house_rent_allowance, #medical_allowance, #transport_allowance, #other_allowances').on('input', function() {
        calculateGrossSalary();
        debouncedCalculateTaxSlab();
    });

    // Bind calculation to tax exemption amount field
    $('#tax_exemption_amount').on('input', function() {
        debouncedCalculateTaxSlab();
    });

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
                <div class="col-md-3">
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
                    </div>
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
        $('.remove-deduction').toggle(rows > 1);
    }

    // Form validation
    $('#salary-form').submit(function(e) {
        const basicSalary = $('#basic_salary').val();
        
        if (!basicSalary || parseFloat(basicSalary) <= 0) {
            e.preventDefault();
            alert('Please enter a valid basic salary');
            return false;
        }
    });

    // Initial calculation
    calculateGrossSalary();
    calculateTaxSlab();
});
</script>
@endpush
