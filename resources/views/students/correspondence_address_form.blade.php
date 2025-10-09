<form class="row g-3 needs-validation" method="POST" action="{{ route('student-addresses.store') }}" novalidate>
    <h5 class="text-muted d-flex align-items-center"><i class="ri-building-fill me-1"></i>Residence</h5>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="load-select form-select @if($errors->has('country_id')) is-invalid @endif" id="countryID" name="country_id" data-target="state_id" data-url="{{ route('list-states') }}" aria-label="Country select" required>
                <option value="">Please select a country</option>
                @foreach ($countries as $country)
                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                @endforeach
            </select>
            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('country_id'))
                {{ $errors->first('country_id') }}
                @else
                Country is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="load-select form-select @if($errors->has('state_id')) is-invalid @endif" id="stateID" name="state_id" data-target="city_id" data-url="{{ route('list-cities') }}" aria-label="State select" required>
                <option value="">Please select a state/province</option>
                @if (old('state_id'))
                @foreach ($states as $state)
                <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                @endforeach
                @endif
            </select>
            <label for="state" class="form-label">State/Province <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('state_id'))
                {{ $errors->first('state_id') }}
                @else
                State/Province is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="load-select form-select @if($errors->has('city_id')) is-invalid @endif" id="cityID" name="city_id" data-target="town_id" data-url="{{ route('list-towns') }}" aria-label="City select" required>
                <option value="">Please select a city</option>
                @if (old('city_id'))
                @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                @endforeach
                @endif
            </select>
            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('city_id'))
                {{ $errors->first('city_id') }}
                @else
                City is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('town_id')) is-invalid @endif" id="townID" name="town_id" aria-label="Town select" required>
                <option value="">Please select a town</option>
                @if (old('city_id'))
                @foreach ($towns as $town)
                <option value="{{ $town->id }}" {{ old('town_id') == $town->id ? 'selected' : '' }}>{{ $town->town_name }}</option>
                @endforeach
                @endif
            </select>
            <label for="town" class="form-label">Town <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('town_id'))
                {{ $errors->first('town_id') }}
                @else
                Town is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('postal_code')) is-invalid @endif" id="postalCode" name="postal_code" placeholder="Please enter postal code" value="{{ old('postal_code') }}" required>
            <label for="postalCode" class="form-label">Postal Code <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('postal_code'))
                {{ $errors->first('postal_code') }}
                @else
                Postal Code is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('contact_person')) is-invalid @endif" id="contactPerson" name="contact_person" placeholder="Please enter contact person" value="{{ old('contact_person') }}" required>
            <label for="contactPerson" class="form-label">Contact Person <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('contact_person'))
                {{ $errors->first('contact_person') }}
                @else
                Contact person is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control mobile-mask @if($errors->has('phone')) is-invalid @endif" id="phone" name="phone" placeholder="Please enter phone" value="{{ old('phone') }}" required>
            <label for="phone" class="form-label">Mobile <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('phone'))
                {{ $errors->first('phone') }}
                @else
                Mobile is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control mobile-mask @if($errors->has('sms_number')) is-invalid @endif" id="SMSNumber" name="sms_number" placeholder="Please enter sms number" value="{{ old('sms_number') }}" required>
            <label for="SMSNumber" class="form-label">SMS Number <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('sms_number'))
                {{ $errors->first('sms_number') }}
                @else
                SMS Number is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control mobile-mask @if($errors->has('residential_mobile')) is-invalid @endif" id="residentialMobile" name="residential_mobile" placeholder="Please enter residential mobile" value="{{ old('residential_mobile') }}" required>
            <label for="residentialMobile" class="form-label">Residential Mobile <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('residential_mobile'))
                {{ $errors->first('residential_mobile') }}
                @else
                Residential Mobile is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-label-group in-border">
            <textarea class="form-control" id="streetAddress" name="street_address" rows="2" placeholder="Please enter your street address" required>{{ old('street_address') }}</textarea>
            <label for="streetAddress" class="form-label">Street Address <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('street_address'))
                {{ $errors->first('street_address') }}
                @else
                Street Address is required!
                @endif
            </div>
        </div>
    </div>
    <div class="border mt-3 border-dashed"></div>
    <h5 class="text-muted d-flex align-items-center"><i class="ri-home-5-fill me-1"></i>Permanent</h5>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('per_city_id')) is-invalid @endif" id="perCity" name="per_city_id" aria-label="City select" required>
                <option value="">Please select a city</option>
                @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                @endforeach
            </select>
            <label for="perCity" class="form-label">City <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('per_city_id'))
                {{ $errors->first('per_city_id') }}
                @else
                Permanent city is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control mobile-mask @if($errors->has('per_phone')) is-invalid @endif" id="perPhone" name="per_phone" placeholder="Please enter phone" value="{{ old('per_phone') }}" required>
            <label for="perPhone" class="form-label">Mobile <span class="text-danger">*</span><label>
            <div class="invalid-tooltip">
                @if($errors->has('per_phone'))
                {{ $errors->first('per_phone') }}
                @else
                Mobile is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('per_postal_code')) is-invalid @endif" id="perPostal" name="per_postal_code" placeholder="Please enter postal code" value="{{ old('per_postal_code') }}" required>
            <label for="perPostal" class="form-label">Postal Code <span class="text-danger">*</span><label>
            <div class="invalid-tooltip">
                @if($errors->has('per_postal_code'))
                {{ $errors->first('per_postal_code') }}
                @else
                Postal Code is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div style="text-align: end">
            <input class="form-check-input" type="checkbox" id="same_as_above"> Same as above
        </div>
        <div class="form-label-group in-border">
            <textarea class="form-control" id="perAddress" name="per_address" rows="2" placeholder="Please enter your street address" required>{{ old('per_address') }}</textarea>
            <label for="perAddress" class="form-label">Permanent Address <span class="text-danger">*</span></label>
            <div class="invalid-tooltip">
                @if($errors->has('per_address'))
                {{ $errors->first('per_address') }}
                @else
                Permanent Address is required!
                @endif
            </div>
        </div>
    </div>

    <input type="hidden" id="studentID" name="student_id" value="{{ isset($student) ? $student->id : 0 }}" />

    @csrf
    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>

@push('footer_scripts')
    <script type="text/javascript">

        $('#studentAddressForm').submit(function(e) {
            e.preventDefault();
            if (!e.target.checkValidity()) return console.log('not validated')

            let data = {
                "country_id": $('#countryID').val(),
                "state_id": $('#stateID').val(),
                "city_id": $('#cityID').val(),
                "town_id": $('#townID').val(),
                "postal_code": $('#perPostal').val(),
                "contact_person": $('#contactPerson').val(),
                "phone": $('#perPhone').val(),
                "sms_number": $('#SMSNumber').val(),
                "residential_mobile": $('#residentialMobile').val(),
                "street_address": $('#streetAddress').val(),
                "per_city_id": $('#perCity').val(),
                "per_phone": $('#perPhone').val(),
                "per_postal_code": $('#perPostal').val(),
                "per_address": $('#perAddress').val(),
                "student_id": $('#studentID').val(),
                "_token": "{{ csrf_token() }}"
            }

            $.ajax({
                url: "{{ route('student-addresses.store') }}",
                type: "POST",
                data,
                success: function(response) {
                    $('#studentAddressForm').removeClass('was-validated')
                    $('#studentAlert').removeClass('alert-danger').addClass('alert alert-success').text(response.success);
                    // $('.is-invalid').removeClass('is-invalid')
                    // $("#studentAddressForm")[0].reset();

                    let url = `/students/${data.student_id}/edit?tab=correspondence`;
                    window.location = url;
                },
                error: function(response) {
                    $('#studentAlert').addClass('alert alert-danger').text(response.responseJSON.message)
                    setInputErrors('studentAddressForm', response.responseJSON.errors)
                },
            });
        })

        $(document).on('click', '#same_as_above', function(e) {
            $('#perAddress').val($('#streetAddress').val());
        });

    </script>
@endpush
