<form action="{{ route('network-associates.store') }}" method="POST" class="row g-3 needs-validation" novalidate>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('nwa_name')) is-invalid @endif" id="nwaName" name="nwa_name" placeholder="NWA name" value="{{ old('nwa_name') }}" required>
            <label for="nwaName" class="form-label">NWA name</label>
        <div class="invalid-tooltip">
            @if($errors->has('nwa_name'))
            {{ $errors->first('nwa_name') }}
            @else
            Netword associate name is required!
            @endif
        </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('company')) is-invalid @endif load-select" id="company" name="company" aria-label="Company select" placeholder="Company" data-url="{{ route('list-branches') }}" data-target="branch_id" required>
                <option value="">Please select</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}" {{ old('company') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
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
    <!-- <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('branch_id')) is-invalid @endif" id="branchId" name="branch_id" aria-label="Branches select" placeholder="Branch" data-choices="" data-choices-removeitem="" multiple="" required>
                <option value="">Please select</option>
                @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->br_name }}</option>
                @endforeach
            </select>
            <label for="branchId" class="form-label">Branches</label>
        </div>
        <div class="invalid-tooltip">
            @if($errors->has('branch_id'))
            {{ $errors->first('branch_id') }}
            @else
            Branch is required!
            @endif
        </div>
    </div> -->
    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
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
            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" id="password" name="password" placeholder="Please enter your password" value="{{ old('password') }}" required>
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
            <input type="text" class="form-control @if($errors->has('date_of_birth')) is-invalid @endif" data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date="{{ date('d-m-Y') }}" value="{{ old('date_of_birth') }}" name="date_of_birth" id="cleave-date" required>
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

    <div class="col-md-4 col-sm-12">
        <div class="form-label-group in-border">
            <select class="form-select @if($errors->has('gender')) is-invalid @endif" id="gender" name="gender" aria-label="Gender select" required>


                <option value="">Please select</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                {{--<option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>--}}
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
        {{-- <div class="col-xl-6">
            <div class="mb-3">
                <label for="cleave-ccard" class="form-label">Credit Card</label>
                <input type="text" class="form-control" id="input-credit-card" placeholder="xxxx xxxx xxxx xxxx">
            </div>
        </div> --}}
        {{-- <div class="col-xl-6">
            <div class="mb-3">
                <label for="cleave-delimiters" class="form-label">Delimiters</label>
                <input type="text" class="form-control" id="cleave-delimiters" placeholder="xxx.xxx.xxx-xx">
            </div>
        </div> --}}


        <div class="form-label-group in-border">
            <input type="text" class="form-control @if($errors->has('CNIC')) is-invalid @endif" id="CNIC" name="CNIC" placeholder="Please enter your CNIC" value="{{ old('CNIC') }}" required>
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
            <input type="text" class="form-control @if($errors->has('NTN')) is-invalid @endif" id="NTN" name="NTN" placeholder="Please enter your NTN" value="{{ old('NTN') }}" required>
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
            <input type="text" class="form-control @if($errors->has('STRN')) is-invalid @endif" id="STRN" name="STRN" placeholder="STRN" value="{{ old('STRN') }}" required>
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
