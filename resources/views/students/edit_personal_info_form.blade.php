<form class="row g-3 needs-validation" action="{{ route('students.update', $student->id) }}" method="POST" novalidate>
    @method('PUT')
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <input type="text" class="form-control @if ($errors->has('first_name')) is-invalid @endif" id="firstName"
                name="first_name" placeholder="Please enter first name" value="{{ old('first_name', $student->first_name) }}" required>
            <label for="firstName" class="form-label">First Name <span class="text-danger">*</span></label>
            @if ($errors->has('first_name'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('first_name') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <input type="text" class="form-control @if ($errors->has('middle_name')) is-invalid @endif" id="middleName"
                name="middle_name" placeholder="Please enter middle name" value="{{ old('middle_name', $student->middle_name ?? '') }}">
            <label for="middleName" class="form-label">Middle Name</label>
            @if ($errors->has('middle_name'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('middle_name') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <input type="text" class="form-control @if ($errors->has('last_name')) is-invalid @endif"
                id="lastName" name="last_name" placeholder="Please enter last name" value="{{ old('last_name', $student->last_name) }}"
                required>
            <label for="lastName" class="form-label">Last Name <span class="text-danger">*</span></label>
            @if ($errors->has('last_name'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('last_name') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <select class="form-select @if ($errors->has('gender')) is-invalid @endif" id="gender"
                name="gender" aria-label="Gender select" required>
                <option value="">Please select a gender</option>
                <option value="male" {{ old('gender', strtolower($student->gender)) == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', strtolower($student->gender)) == 'female' ? 'selected' : '' }}>Female</option>
                <option value="other" {{ old('gender', strtolower($student->gender)) == 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
            @if ($errors->has('gender'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('gender') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <div class="input-group">
                <input type="text"
                    class="form-control disable-max-date @if ($errors->has('date_of_birth')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                    value="{{ old('date_of_birth', parse_date($student->date_of_birth, 'd-m-Y')) }}" name="date_of_birth" id="dateOfBirth"
                    required>
                <label for="dateOfBirth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
            </div>
            @if ($errors->has('date_of_birth'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('date_of_birth') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif"
                id="email" name="email" placeholder="Please enter email" value="{{ old('email', $student->email) }}">
            <label for="email" class="form-label">Student Email</label>
            @if ($errors->has('email'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('email') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <select class="form-select @if ($errors->has('language_id')) is-invalid @endif" id="language"
                name="language_id" aria-label="Language select" required>
                <option value="">Please select a language</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}"
                        {{ old('language_id', $student->language_id) == $language->id ? 'selected' : '' }}>
                        {{ $language->language_name }}</option>
                @endforeach
            </select>
            <label for="language" class="form-label">Language <span class="text-danger">*</span></label>
            @if ($errors->has('language_id'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('language_id') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <select class="form-select @if ($errors->has('nationality_id')) is-invalid @endif" id="nationality"
                name="nationality_id" aria-label="Nationality select" required>
                <option value="">Please select a nationality</option>
                @foreach ($nationalities as $nationality)
                    <option value="{{ $nationality->id }}"
                        {{ old('nationality_id', $student->nationality_id) == $nationality->id ? 'selected' : '' }}>
                        {{ $nationality->nationality_name }}</option>
                @endforeach
            </select>
            <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
            @if ($errors->has('nationality_id'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('nationality_id') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <select class="form-select @if ($errors->has('religion_id')) is-invalid @endif" id="religion"
                name="religion_id" aria-label="Religion select" required>
                <option value="">Please select a religion</option>
                @foreach ($religions as $religion)
                    <option value="{{ $religion->id }}"
                        {{ old('religion_id', $student->religion_id) == $religion->id ? 'selected' : '' }}>
                        {{ $religion->religion_name }}</option>
                @endforeach
            </select>
            <label for="religion" class="form-label">Religion <span class="text-danger">*</span></label>
            @if ($errors->has('religion_id'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('religion_id') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <div class="input-group">
                <input type="text" class="form-control @if ($errors->has('admission_wef')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y" name="admission_wef"
                    id="admissionWEF" value="{{ old('admission_wef', parse_date($student->admission_wef, 'd-m-Y')) }}" required
                    {{ !isSuperAdmin() && !isHeadOfficeEmp() && $student['student_invoices']->isNotEmpty() ? 'disabled' : '' }} />
                <label for="admissionWEF" class="form-label">Admission w.e.f <span
                        class="text-danger">*</span></label>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
            </div>
            @if ($errors->has('admission_wef'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('admission_wef') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <div class="input-group">
                <input type="text"
                    class="form-control disable-max-date @if ($errors->has('registration_date')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="d-m-Y" data-altFormat="d-m-Y"
                    value="{{ old('registration_date', parse_date($student->registration_date, 'd-m-Y')) }}" name="registration_date"
                    id="registrationDate" required>
                <label for="registrationDate" class="form-label">Registration Date <span
                        class="text-danger">*</span></label>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
            </div>
            @if ($errors->has('registration_date'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('registration_date') }}
                </div>
            @endif
        </div>
    </div>
    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="number" class="form-control @if ($errors->has('registration_fee')) is-invalid @endif"
                id="registration_fee" name="registration_fee" placeholder="Please enter Entry Fee"
                value="{{ old('registration_fee', $student->registration_fee) }}" required>
            <label for="registration_fee" class="form-label">Entry Fee <span class="text-danger">*</span></label>
            @if ($errors->has('registration_fee'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('registration_fee') }}
                </div>
            @endif
        </div>
    </div> --}}
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <div class="input-group">
                <input type="text" class="form-control @if ($errors->has('test_date_time')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y H:i"
                    value="{{ old('test_date_time', $student->test_date_time) }}" name="test_date_time" id="test_date_time"
                    data-enable-time required>
                <label for="registrationDate" class="form-label">Test Date Time <span
                        class="text-danger">*</span></label>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                @if ($errors->has('test_date_time'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('test_date_time') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <div class="input-group">
                <input type="text" class="form-control @if ($errors->has('interview_date_time')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d M, Y H:i"
                    value="{{ old('interview_date_time', $student->interview_date_time) }}" name="interview_date_time" id="interview_date_time"
                    data-enable-time required>
                <label for="registrationDate" class="form-label">Interview Date Time <span
                        class="text-danger">*</span></label>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                @if ($errors->has('interview_date_time'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('interview_date_time') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <input type="text" class="form-control @if ($errors->has('passport_number')) is-invalid @endif"
                id="passportNumber" name="passport_number" placeholder="Please enter passport number"
                value="{{ $student->passport_number }}">
            <label for="passportNumber" class="form-label">Passport Number</label>
            <div class="invalid-tooltip">
                @if ($errors->has('passport_number'))
                    {{ $errors->first('passport_number') }}
                    --}}{{-- @else
                    Passport Number is required! --}}{{--
                @endif
            </div>
        </div>
    </div> --}}
    @if (auth()->user()->hasRole('super_admin'))
        <div class="col-md-4 col-sm-12 mb-2">
            <div class="form-label-group in-border mb-1">
                <select class="form-select @if ($errors->has('branch_id')) is-invalid @endif" id="branch"
                    name="branch_id" aria-label="Branch select" required>
                    <option value="">Please select a branch</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ old('branch_id', $student->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}
                        </option>
                    @endforeach
                </select>
                <label for="branch" class="form-label">Branch <span class="text-danger">*</span></label>
                @if ($errors->has('branch_id'))
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('branch_id') }}
                    </div>
                @endif
            </div>
        </div>
        {{-- @else
        <input type="hidden" name="branch_id" value="{{ get_branch_id() }}"> --}}
    @endif

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if ($errors->has('cnic')) is-invalid @endif"
                id="CNIC" name="cnic" placeholder="CNIC" value="{{ old('cnic', $student->cnic ?? $student->passport_number) }}" required>
            <label for="CNIC" class="form-label">Smart Card/ CNIC/ Passport No. <span
                    class="text-danger">*</span></label>
            @if ($errors->has('cnic'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('cnic') }}
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <input type="text" class="form-control @if ($errors->has('pin_code')) is-invalid @endif"
                id="pin_code" name="pin_code" placeholder="Pin Code" value="{{ old('pin_code', $student->pin_code) }}">
            <label for="pin_code" class="form-label">Pin Code</label>
        </div>
        @if ($errors->has('pin_code'))
            <div class="invalid-feedback d-block">
                {{ $errors->first('pin_code') }}
            </div>
        @endif
    </div>

    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <input type="text" class="form-control @if ($errors->has('card_no')) is-invalid @endif"
                id="card_no" name="card_no" placeholder="Card No" value="{{ old('card_no', $student->card_no) }}">
            <label for="card_no" class="form-label">Card No</label>
        </div>
        @if ($errors->has('card_no'))
            <div class="invalid-feedback d-block">
                {{ $errors->first('card_no') }}
            </div>
        @endif
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if ($errors->has('emergency_phone_number')) is-invalid @endif"
                id="emergency_phone_number" name="emergency_phone_number" placeholder="Emergency Phone Number"
                value="{{ old('emergency_phone_number', $student->emergency_phone_number) }}" required>
            <label for="emergency_phone_number" class="form-label">Emergency Phone Number <span
                    class="text-danger">*</span></label>
            @if ($errors->has('emergency_phone_number'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('emergency_phone_number') }}
                </div>
            @endif
        </div>
    </div>
    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-check form-check-success mb-3 mt-2">
            <input type="checkbox" class="form-check-input" id="security_deposit" name="security_deposit"
                value="1" {{ $student->security_deposit == 1 ? 'checked' : '' }}>
            <label for="security_deposit" class="form-check-label">Security Deposit
                {{ $student->security_deposit == 1 ? '(' . number_format($student->amount) . ' PKR)' : '' }}</label>
        </div>
    </div> --}}
    {{-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="number" class="form-control" id="security_amount" name="security_amount" placeholder="Security amount" value="{{ $student->security_amount }}">
            <label for="security_amount" class="form-label">Security Amount</label>
        </div>
    </div> --}}

    <!-- <div class="col-md-4 col-sm-12 mb-2">
        <label for="invoGenerated" class="form-label">Invo Generated</label>
        <input type="number" class="form-control @if ($errors->has('invo_generated')) is-invalid @endif" id="invoGenerated" name="invo_generated" placeholder="Please enter age" value="{{ old('invo_generated') }}">
        <div class="invalid-tooltip">
            @if ($errors->has('invo_generated'))
{{ $errors->first('invo_generated') }}
            {{-- @else
            Last name is required! --}}
@endif
        </div>
    </div> -->
    <!-- <div class="col-md-2 col-sm-12">
        <div class="form-check m-2 mt-md-4">
            <input class="form-check-input" type="checkbox" id="formCheck1">
            <label class="form-check-label" for="formCheck1">
                Email List
            </label>
        </div>
    </div>
    <div class="col-md-2 col-sm-12">
        <div class="form-check m-2 mt-md-4">
            <input class="form-check-input" type="checkbox" id="formCheck1">
            <label class="form-check-label" for="formCheck1">
                Credit Card
            </label>
        </div>
    </div> -->

    <div class="border mt-3 border-dashed"></div>
    <h5 class="text-muted d-flex align-items-center"><i class="ri-building-fill me-1"></i>Birth Information</h5>

    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <select class="load-select form-select @if ($errors->has('country')) is-invalid @endif"
                id="country" name="country_id" data-target="state_id" data-url="{{ route('list-states') }}"
                aria-label="Country select" required>
                <option value="">Please select a country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ old('country_id', $student->country_id) == $country->id ? 'selected' : '' }}>
                        {{ $country->country_name }}
                    </option>
                @endforeach
            </select>
            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
            @if ($errors->has('country_id'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('country_id') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <select class="load-select form-select @if ($errors->has('state_id')) is-invalid @endif"
                id="state" name="state_id" data-target="city_id" data-url="{{ route('list-cities') }}"
                aria-label="State select" required>
                <option value="">Please select a state/province</option>
                @if ($student->state_id)
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" {{ old('state_id', $student->state_id) == $state->id ? 'selected' : '' }}>
                            {{ $state->state_name }}
                        </option>
                    @endforeach
                @endif
            </select>
            <label for="state" class="form-label">State/Province <span class="text-danger">*</span></label>
            @if ($errors->has('state_id'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('state_id') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <select class="form-select @if ($errors->has('city')) is-invalid @endif" id="city"
                name="city_id" aria-label="City select" required>
                <option value="">Please select a city</option>
                @if ($student->city_id)
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" {{ old('city_id', $student->city_id) == $city->id ? 'selected' : '' }}>
                            {{ $city->city_name }}</option>
                    @endforeach
                @endif
            </select>
            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
            @if ($errors->has('city_id'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('city_id') }}
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-2">
        <div class="form-label-group in-border mb-1">
            <input type="text" class="form-control @if ($errors->has('birth_place')) is-invalid @endif"
                id="birthPlace" name="birth_place" placeholder="Please enter birth place"
                value="{{ old('birth_place', $student->birth_place) }}" required>
            <label for="birthPlace" class="form-label">Birth Place <span class="text-danger">*</span></label>
            @if ($errors->has('birth_place'))
                <div class="invalid-feedback d-block">
                    {{ $errors->first('birth_place') }}
                </div>
            @endif
        </div>
    </div>

    @csrf

    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <a href="{{ route('students.index') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
    </div>
</form>

@push('footer_scripts')
    <script type="text/javascript">
        $('#studentEditForm').submit(function(e) {
            e.preventDefault();


            if (!e.target.checkValidity()) return console.log('not validated')

            let data = {
                'first_name': $('#firstName').val(),
                'middle_name': $('#middleName').val(),
                'last_name': $('#lastName').val(),
                'gender': $('#gender').val(),
                'date_of_birth': $('#dateOfBirth').val(),
                'registration_date': $('#registrationDate').val(),
                'language_id': $('#language').val(),
                'nationality_id': $('#nationality').val(),
                'religion_id': $('#religion').val(),
                'admission_wef': $('#admissionWEF').val(),
                'passport_number': $('#passportNumber').val(),
                'branch_id': $('#branch').val(),
                'country_id': $('#country').val(),
                'state_id': $('#state').val(),
                'city_id': $('#city').val(),
                'birth_place': $('#birthPlace').val(),
                'cnic': $('#cnic').val(),
                "_token": "{{ csrf_token() }}"
            }

            $.ajax({
                url: "{{ route('students.update', $student->id) }}",
                type: "PATCH",
                data,
                success: function(response) {
                    $('#studentEditForm').removeClass('was-validated')
                    $('#studentAlert').removeClass('alert-danger').addClass('alert alert-success').text(
                        response.success);
                    $("#studentEditForm")[0].reset();

                    for (const key in data) {
                        let input = $(`[name="${key}"]`);
                        input.val(data[key])
                    }
                },
                error: function(response) {
                    $('#studentAlert').addClass('alert alert-danger').text(response.responseJSON
                        .message)
                    setInputErrors('studentEditForm', response.responseJSON.errors)
                },
            });
        })

        $('#admissionWEF').flatpickr({
            dateFormat: "d-m-Y" // Display format: day-month-year
        });
    </script>
@endpush
