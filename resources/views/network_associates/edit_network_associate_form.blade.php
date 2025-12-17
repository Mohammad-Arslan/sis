{{-- {{ $network_associate }} --}}

<form action="{{ route('network-associates.update', $network_associate->id) }}" method="POST" class="row g-3 needs-validation" novalidate>
    @method('PATCH')

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('nwa_name')) is-invalid @endif" id="nwa_name" name="nwa_name" placeholder="Please enter NWA name" value="{{ $network_associate->user->name }}">
            <label for="nwa_name" class="form-label">NWA name</label>
            <div class="invalid-tooltip">
                @if($errors->has('nwa_name'))
                    {{ $errors->first('nwa_name') }}
                @else
                    Network associate name is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('company')) is-invalid @endif" id="company" name="company" aria-label="Company select">
                <option value="">Please select a company</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ $network_associate->company->id == $company->id ? 'selected' : '' }} >{{ $company->company_name }}</option>
                @endforeach
            </select>
            <label for="company" class="form-label">Company</label>
            <div class="invalid-tooltip">
                @if($errors->has('company'))
                    {{ $errors->first('company') }}
                @else
                    Company is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif"
            id="email" name="email" placeholder="Please enter your email" value="{{ $network_associate->user->email }}">
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
            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif"
            id="password" name="password" placeholder="Please enter your password">
            <label for="password" class="form-label">Password</label>
            <div class="invalid-tooltip">
                @if($errors->has('password'))
                    {{ $errors->first('password') }}
                @else
                    Password is required!
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <div class="input-group form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('date_of_birth')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" value="{{ $network_associate->user->date_of_birth }}" name="date_of_birth" id="cleave-date">
            <div class="input-group-text bg-primary border-primary text-white">
                <i class="ri-calendar-2-line"></i>
            </div>
            <label for="cleave-date" class="form-label">Date of Birth</label>

            <div class="invalid-tooltip">
                @if($errors->has('date_of_birth'))
                    {{ $errors->first('date_of_birth') }}
                @else
                    Date of birth is required!
                @endif
            </div>
        </div>
    </div>

    {{--<div class="col-md-4 col-sm-12">
        <label for="dateOfBirth" class="form-label">Date of Birth</label>
        <input type="date" class="form-control @if($errors->has('date_of_birth')) is-invalid @endif"
               id="dateOfBirth" name="date_of_birth" placeholder="Please add your date of birth" value="{{ old('date_of_birth') }}invalid-tooltip@if($errors->has('date_of_birth'))
                {{ $errors->first('date_of_birth') }}
            @else
                Date of birth is required!
            @endif
        </div>
    </div>--}}
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('gender')) is-invalid @endif" id="gender" name="gender" aria-label="Gender select">
                <option value="">Please select a gender</option>
                <option value="male" {{ $network_associate->user->gender == 'male' ? 'selected' : '' }} >Male</option>
                <option value="female" {{ $network_associate->user->gender == 'female' ? 'selected' : '' }} >Female</option>
                {{--<option value="other" {{ $network_associate->user->gender == 'other' ? 'selected' : '' }} >Other</option>--}}
            </select>
            <label for="gender" class="form-label">Gender</label>
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
            <input type="text" class="form-control @if($errors->has('CNIC')) is-invalid @endif"
            id="CNIC" name="CNIC" placeholder="Please enter your CNIC" value="{{ $network_associate->user->CNIC }}">
            <label for="CNIC" class="form-label">CNIC</label>
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
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('NTN')) is-invalid @endif"
            id="NTN" name="NTN" placeholder="Please enter your NTN" value="{{ $network_associate->NTN }}">
            <label for="NTN" class="form-label">NTN</label>
            <div class="invalid-tooltip">
                @if($errors->has('NTN'))
                    {{ $errors->first('NTN') }}
                @else
                    NTN is required!
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('STRN')) is-invalid @endif"
            id="STRN" name="STRN" placeholder="Please enter your STRN" value="{{ $network_associate->STRN }}">
            <label for="STRN" class="form-label">STRN</label>
            <div class="invalid-tooltip">
                @if($errors->has('STRN'))
                    {{ $errors->first('STRN') }}
                @else
                    STRN is required!
                @endif
            </div>
        </div>
    </div>

    @csrf

    <div class="col-12 text-end">
        <button class="btn btn-primary" type="submit">Save Changes</button>
        <button type="button" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</button>
    </div>


</form>

@push('footer_scripts')
<script>
    $(document).ready(function () {

    });

</script>
@endpush
