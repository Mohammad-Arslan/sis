<form class="row g-3 needs-validation" action="{{ route('update-employee',isset($employee[0]->id) ? $employee[0]->id : '') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('prefix')) is-invalid @endif" id="prefix" name="prefix" aria-label="Prefix select" required>
                <option value="">Please select</option>
                <option value="Mr" {{ old('prefix', $employee[0]->prefix) == 'Mr' ? 'selected' : '' }}>Mr.</option>
                <option value="Mrs" {{ old('prefix', $employee[0]->prefix) == 'Mrs' ? 'selected' : '' }}>Mrs.</option>
                <option value="Ms" {{ old('prefix', $employee[0]->prefix) == 'Ms' ? 'selected' : '' }}>Ms.</option>
            </select>
            <label for="firstName" class="form-label">Prefix *</label>

            <div class="invalid-tooltip">
                @if($errors->has('prefix'))
                {{ $errors->first('prefix') }}
                @else
                Prefix is required!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('first_name')) is-invalid @endif" id="firstName" name="first_name" placeholder="First Name" value="{{ old('first_name', $employee[0]->user->first_name) }}"  required>
            <label for="firstName" class="form-label">First Name *</label>
            <div class="invalid-tooltip">
                @if($errors->has('first_name'))
                {{ $errors->first('first_name') }}
                @else
                First name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('last_name')) is-invalid @endif" id="lastName" name="last_name" placeholder="Last Name" value="{{ old('last_name', $employee[0]->user->last_name) }}">
            <label for="lastName" class="form-label">Last Name</label>
            <div class="invalid-tooltip">
                @if($errors->has('last_name'))
                {{ $errors->first('last_name') }}
                @else
                Last name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('preferred_name')) is-invalid @endif" id="preferredName" name="preferred_name" placeholder="Preferred Name" value="{{ old('preferred_name', $employee[0]->preferred_name) }}">
            <label for="preferredName" class="form-label">Preferred Name</label>
            <div class="invalid-tooltip">
                @if($errors->has('preferred_name'))
                {{ $errors->first('preferred_name') }}
                @else
                Preffered name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('father_name')) is-invalid @endif" id="fatherName" name="father_name" placeholder="Father Name" value="{{ old('father_name', $employee[0]->father_name) }}">
            <label for="fatherName" class="form-label">Father Name</label>
            <div class="invalid-tooltip">
                @if($errors->has('father_name'))
                {{ $errors->first('father_name') }}
                @else
                Father name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('spouse_name')) is-invalid @endif" id="spouseName" name="spouse_name" placeholder="Spouse/Partner Name" value="{{ old('spouse_name', $employee[0]->spouse_name) }}">
            <label for="spouseName" class="form-label">Spouse/Partner Name</label>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif" id="EmpEmail" name="email" placeholder="email@domain.com" value="{{ old('email', $employee[0]->user->email) }}">
            <label for="EmpEmail" class="form-label">Email *</label>
            <div class="invalid-tooltip">
                @if($errors->has('email'))
                {{ $errors->first('email') }}
                @else
                Email is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('date_of_birth')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="Y-m-d" data-deafult-date="" value="{{ old('date_of_birth', $employee[0]->user->date_of_birth) }}" name="date_of_birth" id="date_of_birth">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="dateOfBirth" class="form-label">Date of Birth</label>

        </div>
        <div class="invalid-tooltip">
            @if($errors->has('date_of_birth'))
            {{ $errors->first('date_of_birth') }}
            @else
            Date of birth is required!
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('nationality_id')) is-invalid @endif" id="nationality" name="nationality_id" aria-label="Nationality select" required>
                <option value="">Please select</option>
                @foreach ($nationalities as $nationality)
                <option value="{{ $nationality->id }}" {{ old('nationality_id', $employee[0]->nationality_id) == $nationality->id ? 'selected' : '' }}>{{ $nationality->nationality_name }}</option>
                @endforeach
            </select>
            <label for="nationality" class="form-label">Nationality *</label>
            <div class="invalid-tooltip">
                @if($errors->has('nationality_id'))
                {{ $errors->first('nationality_id') }}
                @else
                Nationality is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('gender')) is-invalid @endif" id="gender" name="gender" aria-label="Gender select" required>
                <option value="">Please select</option>
                <option value="Male" {{ old('gender', $employee[0]->user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $employee[0]->user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            <label for="gender" class="form-label">Gender *</label>

            <div class="invalid-tooltip">
                @if($errors->has('gender'))
                {{ $errors->first('gender') }}
                @else
                Gender is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('religion_id')) is-invalid @endif" id="religion" name="religion_id" aria-label="Religion select" required>
                <option value="">Please select</option>
                @foreach ($religions as $religion)
                <option value="{{ $religion->id }}" {{ old('religion_id', $employee[0]->religion_id) == $religion->id ? 'selected' : '' }}>{{ $religion->religion_name }}</option>
                @endforeach
            </select>
            <label for="religion" class="form-label">Religion *</label>
            <div class="invalid-tooltip">
                @if($errors->has('religion_id'))
                {{ $errors->first('religion_id') }}
                @else
                Religion is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('CNIC')) is-invalid @endif" id="CNIC" name="CNIC" placeholder="CNIC" value="{{ old('CNIC', $employee[0]->user->CNIC) }}" required>
            <label for="CNIC" class="form-label">CNIC *</label>
            <div class="invalid-tooltip">
                @if($errors->has('CNIC'))
                {{ $errors->first('CNIC') }}
                @else
                CNIC is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('cnic_expiry')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="Y-m-d" value="{{ old('cnic_expiry', $employee[0]->cnic_expiry) }}" name="cnic_expiry" id="cnic_expiry">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="cnicexpiryDate" class="form-label">CNIC Expiry Date</label>
            <div class="invalid-tooltip">
                @if($errors->has('cnic_expiry'))
                {{ $errors->first('cnic_expiry') }}
                @else
                Last name is required!
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('pin_code')) is-invalid @endif" id="pinCode" name="pin_code" placeholder="Enter PIN Code" value="{{ old('pin_code', $employee[0]->pin_code) }}">
            <label for="pinCode" class="form-label">PIN Code</label>
            <div class="invalid-tooltip">
                @if($errors->has('pin_code'))
                {{ $errors->first('pin_code') }}
                @else
                PIN Code is required!
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('card_no')) is-invalid @endif" id="cardNo" name="card_no" placeholder="Enter Card Number" value="{{ old('card_no', $employee[0]->card_no) }}">
            <label for="cardNo" class="form-label">Card Number</label>
            <div class="invalid-tooltip">
                @if($errors->has('card_no'))
                {{ $errors->first('card_no') }}
                @else
                Card Number is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            {{-- <input type="text" class="form-control @if($errors->has('marital_status')) is-invalid @endif" id="maritalStatus" name="marital_status" placeholder="Marital Status" value="{{ $employee[0]->marital_status }}" required> --}}
            <select class="form-select @if($errors->has('marital_status')) is-invalid @endif" id="maritalStatus" name="marital_status" aria-label="Marital Status">
                <option value="">Please select</option>
                <option value="Single" {{ old('marital_status', $employee[0]->marital_status) == 'Single' ? 'selected' : '' }}>Single</option>
                <option value="Married" {{ old('marital_status', $employee[0]->marital_status) == 'Married' ? 'selected' : '' }}>Married</option>
            </select>
            <label for="maritalStatus" class="form-label">Marital Status</label>
            <div class="invalid-tooltip">
                @if($errors->has('marital_status'))
                {{ $errors->first('marital_status') }}
                @else
                Marital status is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12" id="marriage_date_container" style="display: {{ old('marital_status', $employee[0]->marital_status) == 'Married' ? 'block' : 'none' }};">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('date_of_marriage')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="Y-m-d" data-deafult-date="" value="{{ old('date_of_marriage', $employee[0]->date_of_marriage) }}" name="date_of_marriage" id="date_of_marriage">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="marriageDate" class="form-label">Date of Marriage</label>
        </div>
    </div>
    <div class="col-md-4 col-sm-12" id="no_children_container" style="display: {{ old('marital_status', $employee[0]->marital_status) == 'Married' ? 'block' : 'none' }};">
        <div class="form-label-group in-border">
            <input type="number" class="form-control @if($errors->has('no_of_children')) is-invalid @endif" id="no_of_children" name="no_of_children" placeholder="No. of children" value="{{ old('no_of_children', $employee[0]->no_of_children) }}" min="0" step="1">
            <label for="no_of_children" class="form-label">No. of children</label>
        </div>
    </div>
    <div class="col-md-4 col-sm-12" id="ucs_children_container" style="display: {{ old('marital_status', $employee[0]->marital_status) == 'Married' ? 'block' : 'none' }};">
        <div class="form-label-group in-border">
            <input type="number" class="form-control @if($errors->has('children_in_ucs')) is-invalid @endif" id="children_in_ucs" name="children_in_ucs" placeholder="Children in UCS" value="{{ old('children_in_ucs', $employee[0]->children_in_ucs) }}" min="0" step="1">
            <label for="ucsChildren" class="form-label">Children in UCS</label>
            <div class="invalid-tooltip">
                @if($errors->has('children_in_ucs'))
                {{ $errors->first('children_in_ucs') }}
                @else
                Marital status is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="file" class="form-control @if($errors->has('emp_image')) is-invalid @endif" id="EmpImage" name="emp_image" accept="image/*">
            <label for="EmpImage" class="form-label">Employee Image</label>
            {{-- <div class="invalid-tooltip">
                @if($errors->has('emp_image'))
                {{ $errors->first('emp_image') }}
                @else
                Employee image is required!
                @endif
            </div> --}}
        </div>
    </div>
    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('wing_id')) is-invalid @endif" id="wing_id" aria-label="Wing select">
                <option value="">Please select</option>
            </select>
            <label for="wing_id" class="form-label">Wing</label>
        </div>
    </div> --}}






    <div class="border mt-3 border-dashed"></div>
    <h5 class="text-muted d-flex align-items-center"><i class="ri-building-fill me-1"></i>Birth Place</h5>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="load-select form-select" id="country" name="country_id" data-target="state_id"
                data-url="{{ route('list-states') }}" aria-label="Country select">
                <option value="">Please select</option>
                @foreach ($countries as $country)
                <option value="{{ $country->id }}" {{ old('country_id', $employee[0]->country_id) == $country->id ? 'selected' : '' }} >{{ $country->country_name }}</option>
                @endforeach
            </select>
            <label for="country" class="form-label">Country</label>
            {{-- <div class="invalid-tooltip">
                @if($errors->has('country_id'))
                {{ $errors->first('country_id') }}
                @else
                Country is required!
                @endif
            </div> --}}
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="load-select form-select" id="state_id" name="state_id" data-target="city_id"
                data-url="{{ route('list-cities') }}" aria-label="State select">
                <option value="">Please select</option>
                @foreach ($states as $state)
                <option value="{{ $state->id }}" {{ old('state_id', $employee[0]->state_id) == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                @endforeach
            </select>
            <label for="state_id" class="form-label">State/Province</label>
            {{-- <div class="invalid-tooltip">
                @if($errors->has('state_id'))
                {{ $errors->first('state_id') }}
                @else
                State/Province is required!
                @endif
            </div> --}}
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select" id="city_id" name="city_id" aria-label="City select">
                <option value="">Please select</option>
                @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ old('city_id', $employee[0]->city_id) == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                @endforeach
            </select>
            <label for="city_id" class="form-label">City</label>
            {{-- <div class="invalid-tooltip">
                @if($errors->has('city_id'))
                {{ $errors->first('city_id') }}
                @else
                City is required!
                @endif
            </div> --}}
        </div>
    </div>

    <div class="border mt-3 border-dashed"></div>

    <div class="col-12 text-end">
        @if (isset($employee[0]->id))
        <input type="hidden" class="form-control" id="form_info" name="form_info"  value="basic">
        <button class="btn btn-primary" type="submit">Update</button>
        @endif
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>

@push('footer_scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function toggleMarriageFields() {
            var maritalStatus = document.getElementById('maritalStatus').value;
            var marriageDateContainer = document.getElementById('marriage_date_container');
            var noChildrenContainer = document.getElementById('no_children_container');
            var ucsChildrenContainer = document.getElementById('ucs_children_container');
            
            if (maritalStatus === 'Married') {
                marriageDateContainer.style.display = 'block';
                noChildrenContainer.style.display = 'block';
                ucsChildrenContainer.style.display = 'block';
            } else {
                marriageDateContainer.style.display = 'none';
                noChildrenContainer.style.display = 'none';
                ucsChildrenContainer.style.display = 'none';
                
                // Clear marriage-related field values when changing to Single
                var dateOfMarriageInput = document.getElementById('date_of_marriage');
                var noOfChildrenInput = document.getElementById('no_of_children');
                var childrenInUcsInput = document.getElementById('children_in_ucs');
                
                if (dateOfMarriageInput) dateOfMarriageInput.value = '';
                if (noOfChildrenInput) noOfChildrenInput.value = '';
                if (childrenInUcsInput) childrenInUcsInput.value = '';
            }
        }

        // Function to validate number inputs and prevent negative values
        function validateNumberInput(input) {
            var value = parseInt(input.value);
            if (value < 0) {
                input.value = 0;
            }
        }

        var maritalStatusSelect = document.getElementById('maritalStatus');
        if (maritalStatusSelect) {
            maritalStatusSelect.addEventListener('change', toggleMarriageFields);
            
            // Check if there are existing values and show fields accordingly
            var existingMaritalStatus = '{{ old('marital_status', $employee[0]->marital_status) }}';
            var existingDateOfMarriage = '{{ old('date_of_marriage', $employee[0]->date_of_marriage) }}';
            var existingNoOfChildren = '{{ old('no_of_children', $employee[0]->no_of_children) }}';
            var existingChildrenInUcs = '{{ old('children_in_ucs', $employee[0]->children_in_ucs) }}';
            
            // Only show marriage fields if marital status is currently Married
            if (existingMaritalStatus === 'Married') {
                var marriageDateContainer = document.getElementById('marriage_date_container');
                var noChildrenContainer = document.getElementById('no_children_container');
                var ucsChildrenContainer = document.getElementById('ucs_children_container');
                
                marriageDateContainer.style.display = 'block';
                noChildrenContainer.style.display = 'block';
                ucsChildrenContainer.style.display = 'block';
            }
            
            // Call toggleMarriageFields to set initial state
            toggleMarriageFields();
        }

        // Add event listeners for number input validation
        var noOfChildrenInput = document.getElementById('no_of_children');
        var childrenInUcsInput = document.getElementById('children_in_ucs');
        
        if (noOfChildrenInput) {
            noOfChildrenInput.addEventListener('input', function() {
                validateNumberInput(this);
            });
            noOfChildrenInput.addEventListener('blur', function() {
                validateNumberInput(this);
            });
        }
        
        if (childrenInUcsInput) {
            childrenInUcsInput.addEventListener('input', function() {
                validateNumberInput(this);
            });
            childrenInUcsInput.addEventListener('blur', function() {
                validateNumberInput(this);
            });
        }
    });
</script>
@endpush
