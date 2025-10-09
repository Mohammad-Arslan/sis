<form class="row g-3 needs-validation" action="{{ route('update-employee',isset($employee[0]->id) ? $employee[0]->id : '') }}" method="POST" novalidate>
    @csrf
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('hiring_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('hiring_date', $employee[0]->hiring_date) }}" name="hiring_date" id="hiring_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="hiringDate" class="form-label">Hiring Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('hiring_date'))
                {{ $errors->first('hiring_date') }}
                @else
                Hiring date is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('confirm_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('confirm_date', $employee[0]->confirm_date) }}" name="confirm_date" id="confirm_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="confirmDate" class="form-label">Confirm Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('confirm_date'))
                {{ $errors->first('confirm_date') }}
                @else
                Confirmation date is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">

        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('job_status')) is-invalid @endif" id="jobStatus" name="job_status" aria-label="Job select">
                <option value="">Please select</option>
                <option value="Probation" {{ old('job_status', $employee[0]->job_status) == 'Probation' ? 'selected' : '' }}>Probation</option>
                <option value="Regular" {{ old('job_status', $employee[0]->job_status) == 'Regular' ? 'selected' : '' }}>Regular</option>
                <option value="Adhoc" {{ old('job_status', $employee[0]->job_status) == 'Adhoc' ? 'selected' : '' }}>Adhoc</option>
                <option value="Contractual" {{ old('job_status', $employee[0]->job_status) == 'Contractual' ? 'selected' : '' }}>Contractual</option>
                <option value="Left" {{ old('job_status', $employee[0]->job_status) == 'Left' ? 'selected' : '' }}>Left</option>
            </select>
            <label for="jobStatus" class="form-label">Job Status</label>
            <div class="invalid-tooltip">
                @if($errors->has('job_status'))
                {{ $errors->first('job_status') }}
                @else
                Job status is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('regular_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('regular_date', $employee[0]->regular_date) }}" name="regular_date" id="regular_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="regularDate" class="form-label">Regular Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('regular_date'))
                {{ $errors->first('regular_date') }}
                @else
                Regular date is required!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('left_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('left_date', $employee[0]->left_date) }}" name="left_date" id="left_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="leftDate" class="form-label">Left Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('left_date'))
                {{ $errors->first('left_date') }}
                @else
                Confirmation date is required!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-2 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('from_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('from_date', $employee[0]->from_date) }}" name="from_date" id="from_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="fromDate" class="form-label">From Date</label>
        </div>
    </div>

    <div class="col-md-2 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('to_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('to_date', $employee[0]->to_date) }}" name="to_date" id="to_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="toDate" class="form-label">To Date</label>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('probation_end_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('probation_end_date', $employee[0]->probation_end_date) }}" name="probation_end_date" id="probation_end_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="ProbationendDate" class="form-label">Probation End Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('probation_end_date'))
                {{ $errors->first('probation_end_date') }}
                @else
                Probation end date is required!
                @endif
            </div>
        </div>

    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control" id="ProbationExtended" name="probation_extended" value="{{ old('probation_extended', $employee[0]->probation_extended) }}" placeholder="Probation Extended">
            <label for="ProbationExtended" class="form-label">Probation Extended</label>
        </div>
        <div class="invalid-tooltip"> To is required! </div>
    </div>

    <div class="col-md-4 col-sm-12  mt-3">
        <div class="input-group form-label-group in-border">
            <div class="input-group-text ">
                Yes&nbsp;<input id="deathCase" class="form-check-input mt-0" type="checkbox" value="Y" name="death_case" {{ old('death_case', $employee[0]->death_case ?? '') == 'Y' ? 'checked' : '' }}>
            </div>
            <input type="text" class="form-control @if($errors->has('death_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('death_date', $employee[0]->death_date) }}" name="death_date" id="death_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="deathCase" class="form-label">Death Case</label>
        </div>
    </div>

    <div class="border mt-3 border-dashed"></div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('eobi_number')) is-invalid @endif" id="eobiNumber" name="eobi_number" placeholder="Please enter EOBI number (e.g., EOBI123456)" value="{{ old('eobi_number', $employee[0]->eobi_number) }}" maxlength="20" pattern="[A-Z0-9\-]+" title="EOBI number can only contain uppercase letters, numbers, and hyphens">
            <label for="eobiNumber" class="form-label">EOBI No.</label>
            <div class="invalid-tooltip">
                @if($errors->has('eobi_number'))
                {{ $errors->first('eobi_number') }}
                @else
                EOBI number can only contain uppercase letters, numbers, and hyphens (max 20 characters)!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('ni_number')) is-invalid @endif" id="NINumber" name="ni_number" placeholder="Please enter N.I number (e.g., NI123456)" value="{{ old('ni_number', $employee[0]->ni_number) }}" maxlength="20" pattern="[A-Z0-9\-]+" title="N.I number can only contain uppercase letters, numbers, and hyphens">
            <label for="NINumber" class="form-label">N.I.Number</label>
            <div class="invalid-tooltip">
                @if($errors->has('ni_number'))
                {{ $errors->first('ni_number') }}
                @else
                N.I number can only contain uppercase letters, numbers, and hyphens (max 20 characters)!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="tel" class="form-control @if($errors->has('mobile_number')) is-invalid @endif" id="mobileNumber" name="mobile_number" placeholder="Please enter mobile number (e.g., +92-300-1234567)" value="{{ old('mobile_number', $employee[0]->mobile_number) }}" maxlength="15" pattern="[0-9\+\-\(\)\s]+" title="Mobile number can only contain numbers, spaces, hyphens, and parentheses">
            <label for="mobileNumber" class="form-label">Mobile</label>
            <div class="invalid-tooltip">
                @if($errors->has('mobile_number'))
                {{ $errors->first('mobile_number') }}
                @else
                Mobile number can only contain numbers, spaces, hyphens, and parentheses (max 15 characters)!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('passport_number')) is-invalid @endif" id="passportNumber" name="passport_number" placeholder="Please enter passport number (e.g., AB1234567)" value="{{ old('passport_number', $employee[0]->passport_number) }}" maxlength="20" pattern="[A-Z0-9]+" title="Passport number can only contain uppercase letters and numbers">
            <label for="passportNumber" class="form-label">Passport #</label>
            <div class="invalid-tooltip">
                @if($errors->has('passport_number'))
                {{ $errors->first('passport_number') }}
                @else
                Passport number can only contain uppercase letters and numbers (max 20 characters)!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('expiry_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('expiry_date', $employee[0]->expiry_date) }}" name="expiry_date" id="expiry_date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="expiryDate" class="form-label">Passport Expiry Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('expiry_date'))
                {{ $errors->first('expiry_date') }}
                @else
                Passport expiry date must be in the future!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('crb')) is-invalid @endif" id="CRB" name="crb" value="{{ old('crb', $employee[0]->crb) }}" placeholder="CRB (e.g., CRB123456)" maxlength="20" pattern="[A-Z0-9\-]+" title="CRB can only contain uppercase letters, numbers, and hyphens">
            <label for="CRB" class="form-label">C.R.B</label>
            <div class="invalid-tooltip">
                @if($errors->has('crb'))
                {{ $errors->first('crb') }}
                @else
                CRB can only contain uppercase letters, numbers, and hyphens (max 20 characters)!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('issue_date')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="" value="{{ old('issue_date', $employee[0]->issue_date) }}" name="issue_date" id="IssueDate">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="IssueDate" class="form-label">Issue Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('issue_date'))
                {{ $errors->first('issue_date') }}
                @else
                Issue date cannot be in the future!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">

        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('ss_no')) is-invalid @endif" id="SSNo" name="ss_no" value="{{ old('ss_no', $employee[0]->ss_no) }}" placeholder="SS No. (e.g., SS123456)" maxlength="20" pattern="[A-Z0-9\-]+" title="Social Security number can only contain uppercase letters, numbers, and hyphens">
            <label for="SSNo" class="form-label">Social Security No.</label>
            <div class="invalid-tooltip">
                @if($errors->has('ss_no'))
                {{ $errors->first('ss_no') }}
                @else
                Social Security number can only contain uppercase letters, numbers, and hyphens (max 20 characters)!
                @endif
            </div>
        </div>

    </div>


    <div class="border mt-3 border-dashed"></div>


    <div class="col-12 text-end">
        @if (isset($employee[0]->id))
        <input type="hidden" class="form-control" id="form_info" name="form_info"  value="service">
        <button class="btn btn-primary" type="submit">Update</button>
        @endif
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time validation for alphanumeric fields
    const alphanumericFields = ['eobiNumber', 'NINumber', 'passportNumber', 'CRB', 'SSNo'];
    
    alphanumericFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                const value = this.value;
                const pattern = this.pattern;
                const regex = new RegExp(pattern);
                
                if (value && !regex.test(value)) {
                    this.classList.add('is-invalid');
                    this.setCustomValidity('Please enter a valid format');
                } else {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                }
            });
            
            // Convert to uppercase for consistency
            field.addEventListener('blur', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });
    
    // Mobile number validation
    const mobileField = document.getElementById('mobileNumber');
    if (mobileField) {
        mobileField.addEventListener('input', function() {
            const value = this.value;
            const pattern = this.pattern;
            const regex = new RegExp(pattern);
            
            if (value && !regex.test(value)) {
                this.classList.add('is-invalid');
                this.setCustomValidity('Please enter a valid mobile number format');
            } else {
                this.classList.remove('is-invalid');
                this.setCustomValidity('');
            }
        });
    }
    
    // Date validation logic
    const hiringDateField = document.getElementById('hiring_date');
    const confirmDateField = document.getElementById('confirm_date');
    const regularDateField = document.getElementById('regular_date');
    const leftDateField = document.getElementById('left_date');
    
    // Function to validate date relationships
    function validateDateRelationships() {
        const hiringDate = hiringDateField ? new Date(hiringDateField.value) : null;
        const confirmDate = confirmDateField ? new Date(confirmDateField.value) : null;
        const regularDate = regularDateField ? new Date(regularDateField.value) : null;
        const leftDate = leftDateField ? new Date(leftDateField.value) : null;
        
        // Validate confirm date is after hiring date
        if (hiringDate && confirmDate && confirmDate < hiringDate) {
            confirmDateField.classList.add('is-invalid');
            confirmDateField.setCustomValidity('Confirmation date cannot be before hiring date');
        } else if (confirmDateField) {
            confirmDateField.classList.remove('is-invalid');
            confirmDateField.setCustomValidity('');
        }
        
        // Validate regular date is after hiring date
        if (hiringDate && regularDate && regularDate < hiringDate) {
            regularDateField.classList.add('is-invalid');
            regularDateField.setCustomValidity('Regular date cannot be before hiring date');
        } else if (regularDateField) {
            regularDateField.classList.remove('is-invalid');
            regularDateField.setCustomValidity('');
        }
        
        // Validate left date is after hiring date
        if (hiringDate && leftDate && leftDate < hiringDate) {
            leftDateField.classList.add('is-invalid');
            leftDateField.setCustomValidity('Left date cannot be before hiring date');
        } else if (leftDateField) {
            leftDateField.classList.remove('is-invalid');
            leftDateField.setCustomValidity('');
        }
    }
    
    // Add event listeners for date validation
    [hiringDateField, confirmDateField, regularDateField, leftDateField].forEach(field => {
        if (field) {
            field.addEventListener('change', validateDateRelationships);
        }
    });
    
    // Form submission validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    }
});
</script>
