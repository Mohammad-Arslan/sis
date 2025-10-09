<form action="{{ route('contact-information.update', $contact_information->id) }}" class="row g-3 needs-validation" method="POST" novalidate>
    @method('PATCH')
    <div class="col-md-12">
        <div class="form-label-group in-border">
            <textarea class="form-control @if($errors->has('address')) is-invalid @endif" id="address" name="address" rows="2" placeholder="Please enter your address">{{ $contact_information->address }}</textarea>
            <label for="address" class="form-label">Address</label>
            <div class="invalid-tooltip">
                @if($errors->has('address'))
                    {{ $errors->first('address') }}
                @else
                    Address is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('phone')) is-invalid @endif" name="phone"
            id="phone" placeholder="Please enter your phone number" value="{{ $contact_information->phone }}">
            <label for="phone" class="form-label">Phone Number</label>
            <div class="invalid-tooltip">
                @if($errors->has('phone'))
                    {{ $errors->first('phone') }}
                @else
                    Phone is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('mobile')) is-invalid @endif" name="mobile"
            id="mobile" placeholder="Please enter your mobile number" value="{{ $contact_information->mobile }}">
            <label for="mobile" class="form-label">Mobile</label>
            <div class="invalid-tooltip">
                @if($errors->has('mobile'))
                    {{ $errors->first('mobile') }}
                @else
                    Mobile is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif" name="email"
            id="email" placeholder="Please enter your email" value="{{ $contact_information->email }}">
            <label for="email" class="form-label">Email</label>
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
        <div class="form-label-group in-border">
            <input type="number" class="form-control @if($errors->has('fax')) is-invalid @endif" name="fax"
            id="fax" placeholder="Please enter your fax number" value="{{ $contact_information->fax }}">
            <label for="fax" class="form-label">Fax</label>
            <div class="invalid-tooltip">
                @if($errors->has('fax'))
                    {{ $errors->first('fax') }}
                @else
                    Fax is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="load-select form-select @if($errors->has('country_id')) is-invalid @endif" id="country" name="country_id" data-target="state_id"
            data-url="{{ route('list-states') }}" aria-label="Country select">
            <option value="">Please select</option>
            @if ($countries)
                @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ $contact_information->country_id == $country->id ? 'selected' : '' }}>{{ $country->country_name }}</option>
                @endforeach
            @endif
        </select>
        <label for="country" class="form-label">Country</label>
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
            <select class="load-select form-select @if($errors->has('state_id')) is-invalid @endif" id="state" name="state_id" data-target="city_id"
            data-url="{{ route('list-cities') }}" aria-label="State select">
                <option value="">Please select</option>
                @foreach ($states as $state)
                    <option value="{{ $state->id }}" {{ $contact_information->state_id == $state->id ? 'selected' : '' }}>{{ $state->state_name }}</option>
                @endforeach
            </select>
            <label for="state" class="form-label">State</label>
            <div class="invalid-tooltip">
                @if($errors->has('state_id'))
                    {{ $errors->first('state_id') }}
                @else
                    State is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="load-select form-select @if($errors->has('city_id')) is-invalid @endif" id="city" name="city_id" data-target="town_id"
                data-url="{{ route('list-towns') }}" aria-label="City select">
                <option value="">Please select</option>
                @foreach ($cities as $city)
                    <option value="{{ $city->id }}" {{ $contact_information->city_id == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                @endforeach
            </select>
            <label for="city" class="form-label">City</label>
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
            <select class="form-select @if($errors->has('town_id')) is-invalid @endif" id="town" name="town_id" aria-label="Town select">
                <option value="">Please select</option>
                @foreach ($towns as $town)
                    <option value="{{ $town->id }}" {{ $contact_information->town_id == $town->id ? 'selected' : '' }}>{{ $town->town_name }}</option>
                @endforeach
            </select>
            <label for="town" class="form-label">Town</label>
            <div class="invalid-tooltip">
                @if($errors->has('town_id'))
                    {{ $errors->first('town_id') }}
                @else
                    Town is required!
                @endif
            </div>
        </div>
    </div>

    @if ($network_associate ?? '')
        <input type="hidden" name="nwa_id" value="{{ $network_associate->id }}" />

    @elseif ($branch_associate ?? '')
        <input type="hidden" name="branch_id" value="{{ $branch->id }}" />
    @endif

    @csrf
    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>
</form>
