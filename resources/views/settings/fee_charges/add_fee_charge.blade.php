<div class="col-lg-12">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Create Fee Charges (Bulk)</h4>
            <div class="flex-shrink-0">
                <button type="button" class="btn btn-info btn-sm me-2" id="duplicateRow" title="Duplicate with same settings">
                    <i class="fas fa-copy"></i> Duplicate
                </button>
                <button type="button" class="btn btn-success btn-sm" id="addChargeRow">
                    <i class="fas fa-plus"></i> Add Another Charge
                </button>
            </div>
        </div><!-- end card header -->

        <div class="card-body">
            <div class="live-preview">
                <form class="row g-3 needs-validation" novalidate action="{{ route('fee-charges.bulk-store') }}"
                    method="post" id="bulkFeeChargeForm">
                    @csrf
                    
                    <!-- Common Fields Section -->
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Common Settings</h6>
                    </div>
                    
                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="form-select mb-3" id="academic_year_id" name="academic_year_id" required>
                                <option value="" disabled selected>Academic Year</option>
                                @foreach ($academic_years as $academic_year)
                                    <option value="{{ $academic_year->id }}"
                                        @if (old('academic_year_id') == $academic_year->id) {{ 'selected' }} @else {{ $academic_year->active == 1 ? 'selected' : '' }} @endif>
                                        {{ $academic_year->title }}</option>
                                @endforeach
                            </select>
                            <label for="academic_year_id" class="form-label">Academic Year</label>
                            <div class="invalid-tooltip">Kindly select the academic year!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select mb-3" id="companyName" name="company_id"
                                data-target="branch_id" data-url="{{ route('list-branches') }}" required>
                                <option value="" disabled selected>Companies options</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"
                                        @if (old('company_id') == $company->id) {{ 'selected' }} @endif>
                                        {{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            <label for="companyName" class="form-label">Company</label>
                            <div class="invalid-tooltip">Kindly select the company name!</div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-12">
                        <div class="form-label-group in-border">
                            <select class="load-select form-select mb-3" id="branchName" name="branch_id"
                                aria-label="Branch select" required>
                                <option value="" disabled selected>Branches options</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        @if (old('branch_id') == $branch->id) {{ 'selected' }} @endif>
                                        {{ $branch->br_name }}</option>
                                @endforeach
                            </select>
                            <label for="branchName" class="form-label">Branch</label>
                            <div class="invalid-tooltip">Kindly select the branch name!</div>
                        </div>
                    </div>

                    <!-- Fee Charges Section -->
                    <div class="col-12">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-list-alt me-2"></i>Fee Charges
                        </h6>
                    </div>

                    <div id="feeChargesContainer">
                        <!-- First Charge Row -->
                        <div class="charge-row border rounded p-3 mb-3 bg-white shadow-sm" data-row="0">
                            <div class="row g-3">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="form-select mb-3 fee-charge-type" name="fee_charges[0][fee_charges_type_id]" required>
                                            <option value="" disabled selected>Fee Charges</option>
                                            @foreach ($fee_charges_type as $fee_charge_type)
                                                <option value="{{ $fee_charge_type->id }}">
                                                    {{ $fee_charge_type->name }} ({{ $fee_charge_type->frequency }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <label class="form-label">Fee Charge Type</label>
                                        <div class="invalid-tooltip">Kindly select the fee charge!</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <input type="number" class="form-control" name="fee_charges[0][amount]"
                                            placeholder="Amount" step="0.01" min="0" required>
                                        <label class="form-label">Amount</label>
                                        <div class="invalid-tooltip">Amount is required!</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="form-select mb-3" name="fee_charges[0][is_discountable]" required>
                                            <option value="" disabled selected>Discount</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <label class="form-label">Discount</label>
                                        <div class="invalid-tooltip">Kindly select the discount options!</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-2 col-sm-12">
                                    <div class="form-label-group in-border">
                                        <select class="form-select mb-3" name="fee_charges[0][is_refundable]" required>
                                            <option value="" disabled selected>Refundable</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <label class="form-label">Refundable</label>
                                        <div class="invalid-tooltip">Kindly select the refundable options!</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-1 col-sm-12 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger btn-sm remove-charge-row" style="display: none;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-3">Summary</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-calendar-alt text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">Academic Year</h6>
                                                <small class="text-muted" id="summaryAcademicYear">Not selected</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-building text-success"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">Company</h6>
                                                <small class="text-muted" id="summaryCompany">Not selected</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-map-marker-alt text-info"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">Branch</h6>
                                                <small class="text-muted" id="summaryBranch">Not selected</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-list text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">Total Charges</h6>
                                                <small class="text-muted" id="summaryTotalCharges">0</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-save"></i> Save All Charges
                        </button>
                        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowCounter = 1;
    
    // Duplicate row with same settings
    document.getElementById('duplicateRow').addEventListener('click', function() {
        const container = document.getElementById('feeChargesContainer');
        const firstRow = container.querySelector('.charge-row');
        
        if (!firstRow) return;
        
        const template = firstRow.cloneNode(true);
        
        // Update the row index
        template.dataset.row = rowCounter;
        
        // Update all form field names
        template.querySelectorAll('select, input').forEach(field => {
            if (field.name) {
                field.name = field.name.replace('[0]', `[${rowCounter}]`);
                // Keep the values for duplication
            }
        });
        
        // Show remove button for additional rows
        template.querySelector('.remove-charge-row').style.display = 'block';
        
        container.appendChild(template);
        rowCounter++;
        
        // Reinitialize form validation
        initializeFormValidation();
        
        // Update summary
        updateSummary();
    });
    
    // Add new charge row
    document.getElementById('addChargeRow').addEventListener('click', function() {
        const container = document.getElementById('feeChargesContainer');
        const template = container.querySelector('.charge-row').cloneNode(true);
        
        // Update the row index
        template.dataset.row = rowCounter;
        
        // Update all form field names
        template.querySelectorAll('select, input').forEach(field => {
            if (field.name) {
                field.name = field.name.replace('[0]', `[${rowCounter}]`);
                field.value = '';
            }
        });
        
        // Show remove button for additional rows
        template.querySelector('.remove-charge-row').style.display = 'block';
        
        container.appendChild(template);
        rowCounter++;
        
        // Reinitialize form validation
        initializeFormValidation();
        
        // Update summary
        updateSummary();
    });
    
    // Remove charge row
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-charge-row')) {
            const row = e.target.closest('.charge-row');
            row.remove();
            
            // Reindex remaining rows
            const rows = document.querySelectorAll('.charge-row');
            rows.forEach((row, index) => {
                row.dataset.row = index;
                row.querySelectorAll('select, input').forEach(field => {
                    if (field.name) {
                        const newName = field.name.replace(/\[\d+\]/, `[${index}]`);
                        field.name = newName;
                    }
                });
            });
            rowCounter = rows.length;
            
            // Update summary
            updateSummary();
        }
    });
    
    // Form validation
    function initializeFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }
    
    // Initialize validation
    initializeFormValidation();
    
    // Update summary
    function updateSummary() {
        const academicYearSelect = document.getElementById('academic_year_id');
        const companySelect = document.getElementById('companyName');
        const branchSelect = document.getElementById('branchName');
        const chargeRows = document.querySelectorAll('.charge-row');
        
        // Update academic year
        if (academicYearSelect.value) {
            const selectedOption = academicYearSelect.options[academicYearSelect.selectedIndex];
            document.getElementById('summaryAcademicYear').textContent = selectedOption.textContent;
        } else {
            document.getElementById('summaryAcademicYear').textContent = 'Not selected';
        }
        
        // Update company
        if (companySelect.value) {
            const selectedOption = companySelect.options[companySelect.selectedIndex];
            document.getElementById('summaryCompany').textContent = selectedOption.textContent;
        } else {
            document.getElementById('summaryCompany').textContent = 'Not selected';
        }
        
        // Update branch
        if (branchSelect.value) {
            const selectedOption = branchSelect.options[branchSelect.selectedIndex];
            document.getElementById('summaryBranch').textContent = selectedOption.textContent;
        } else {
            document.getElementById('summaryBranch').textContent = 'Not selected';
        }
        
        // Update total charges
        document.getElementById('summaryTotalCharges').textContent = chargeRows.length;
    }
    
    // Auto-select common values for new rows
    document.addEventListener('change', function(e) {
        if (e.target.matches('.fee-charge-type, select[name*="[is_discountable]"], select[name*="[is_refundable]"]')) {
            const row = e.target.closest('.charge-row');
            const rowIndex = row.dataset.row;
            
            // Auto-fill amount if it's empty and we have a fee charge type selected
            if (e.target.classList.contains('fee-charge-type') && e.target.value) {
                const amountField = row.querySelector('input[name*="[amount]"]');
                if (amountField && !amountField.value) {
                    // You can add default amounts based on fee charge type here
                    // amountField.value = getDefaultAmount(e.target.value);
                }
            }
        }
        
        // Update summary when common fields change
        if (e.target.matches('#academic_year_id, #companyName, #branchName')) {
            updateSummary();
        }
    });
    
    // Update summary on page load
    updateSummary();
    
    // Company change handler for branch loading
    document.getElementById('companyName').addEventListener('change', function() {
        const companyId = this.value;
        const branchSelect = document.getElementById('branchName');
        
        if (companyId) {
            fetch(`{{ route('list-branches') }}?company_id=${companyId}`)
                .then(response => response.json())
                .then(data => {
                    branchSelect.innerHTML = '<option value="" disabled selected>Branches options</option>';
                    data.forEach(branch => {
                        const option = document.createElement('option');
                        option.value = branch.id;
                        option.textContent = branch.br_name;
                        branchSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading branches:', error));
        }
    });
});
</script>
