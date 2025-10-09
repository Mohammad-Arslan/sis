<form class="g-3 needs-validation" action="{{ route('branches.store') }}" method="POST" enctype="multipart/form-data"
    novalidate>
    @csrf
    <div class="row">
        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('br_name')) is-invalid @endif"
                    id="branch_name" name="br_name" placeholder="Enter Branch Name" value="{{ old('br_name') }}"
                    required>
                <label for="branch_name" class="form-label">Branch</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('br_name'))
                        {{ $errors->first('br_name') }}
                    @else
                        Branch name is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('region_id')) is-invalid @endif"
                    aria-label=".form-select-sm example" id="regions" required name="region_id">
                    <option value="">Please select</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                            {{ $region->region_name }}</option>
                    @endforeach
                </select>
                <label for="regions" class="form-label">Regions</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('region_id'))
                        {{ $errors->first('region_id') }}
                    @else
                        Please choose a region.
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('state_id')) is-invalid @endif"
                    aria-label=".form-select-sm example" id="states" required name="state_id">
                    <option value="">Please select</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                            {{ $state->state_name }}</option>
                    @endforeach
                </select>
                <label for="states" class="form-label">State / Province</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('state_id'))
                        {{ $errors->first('state_id') }}
                    @else
                        Please choose a state.
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('company_id')) is-invalid @endif"
                    aria-label="form-select-sm example" id="companyId" required name="company_id">
                    <option value="">Please select</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                <label for="companyId" class="form-label">Company</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('company_id'))
                        {{ $errors->first('company_id') }}
                    @else
                        Please select a company.
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('class_group_id')) is-invalid @endif"
                    aria-label=".form-select-sm example" id="class_group_id" name="class_group_id" required>
                    <option value="">Please select</option>
                    @foreach ($class_groups as $class_group)
                        <option value="{{ $class_group->id }}"
                            {{ old('class_group_id') == $class_group->id ? 'selected' : '' }}>
                            {{ $class_group->name }}</option>
                    @endforeach
                </select>
                <label for="class_group_id" class="form-label">School Type</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('class_group_id'))
                        {{ $errors->first('class_group_id') }}
                    @else
                        Please choose a school type.
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('building_type_id')) is-invalid @endif"
                    aria-label="form-select-sm example" id="building_type_id" name="building_type_id" required>
                    <option value="" disabled selected>Select building type</option>
                    @if (isset($buildtypes))
                        @foreach ($buildtypes as $buildtype)
                            <option value="{{ $buildtype->id }}"
                                {{ old('building_type_id') == $buildtype->id ? 'selected' : '' }}>
                                {{ $buildtype->type_name }}</option>
                        @endforeach
                    @endif
                </select>
                <label for="building_type_id" class="form-label">Building Type</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('building_type_id'))
                        {{ $errors->first('building_type_id') }}
                    @else
                        Please choose a building type.
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('build_purpose')) is-invalid @endif"
                    aria-label="form-select-sm example" id="build_purpose" name="build_purpose" required>
                    <option value="" disabled selected>Select build purpose</option>
                    <option value="Non-purpose" {{ old('build_purpose') == 'Non-purpose' ? 'selected' : '' }}>
                        Non-purpose</option>
                    <option value="Purpose build" {{ old('build_purpose') == 'Purpose build' ? 'selected' : '' }}>
                        Purpose build</option>
                </select>
                <label for="build_purpose" class="form-label">Building Purpose</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('build_purpose'))
                        {{ $errors->first('build_purpose') }}
                    @else
                        Please choose a building purpose.
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select @if ($errors->has('status')) is-invalid @endif"
                    aria-label="form-select-sm example" id="branch_status" name="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <label for="branch_status" class="form-label">Status</label>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('branch_phone_number')) is-invalid @endif"
                    id="branch_phone_number" name="branch_phone_number" placeholder="Enter Branch Phone Number" value="{{ old('branch_phone_number') }}"
                    required>
                <label for="branch_phone_number" class="form-label">Branch Phone</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('branch_phone_number'))
                        {{ $errors->first('branch_phone_number') }}
                    @else
                        Branch phone number is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('website')) is-invalid @endif"
                    id="website" name="website" placeholder="Website URL" value="">
                <label for="website" class="form-label">Website</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('website'))
                        {{ $errors->first('website') }}
                    @else
                        Website url is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('instagram')) is-invalid @endif"
                    id="instagram" name="instagram" placeholder="Instagram Link" value="">
                <label for="instagram" class="form-label">Instagram</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('instagram'))
                        {{ $errors->first('instagram') }}
                    @else
                        Instagram link is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('twitter')) is-invalid @endif"
                    id="twitter" name="twitter" placeholder="Twitter Link" value="">
                <label for="twitter" class="form-label">Twitter</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('twitter'))
                        {{ $errors->first('twitter') }}
                    @else
                        Twitter link is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="file" class="form-control @if ($errors->has('branch_banner')) is-invalid @endif"
                    id="branch_banner" name="branch_banner" accept="image/*">
                <label for="EmpImage" class="form-label">Branch Image</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('branch_banner'))
                        {{ $errors->first('branch_banner') }}
                    @else
                        Branch image is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="input-group form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('setup_date')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                    value="{{ old('setup_date') }}" name="setup_date" id="setupDate" required>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                <label for="setupDate" class="form-label">Setup Date</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('setup_date'))
                        {{ $errors->first('setup_date') }}
                    @else
                        Setup Date is required!
                    @endif
                </div>
            </div>
        </div>


        <div class="col-md-4 col-sm-12 mt-4">
            <div class="input-group form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('closed_date')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y"
                    value="{{ old('closed_date') }}" name="closed_date" id="closingDate">
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                <label for="closingDate" class="form-label">Closing Date (Optional)</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('closed_date'))
                        {{ $errors->first('closed_date') }}
                    @else
                        Closed Date is required!
                    @endif
                </div>
            </div>
        </div>

        @if($tax_types->isNotEmpty())
        <div class="col-lg-4 col-md-6 mt-4">
            <div class="d-flex">
                @foreach ($tax_types as $tax_type)
                    <div class="col-md-6 form-check mb-2">
                        <label class="form-check-label" for="taxType{{ $tax_type->id }}">
                            <input class="form-check-input" type="checkbox" name="tax_types[]"
                                value="{{ $tax_type->id }}" id="taxType{{ $tax_type->id }}">
                            {{ $tax_type->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="col-lg-4 col-md-6 mt-4">
            <div class="input-group form-label-group in-border">
                <input class="form-control" name="student_id_from" type="number" required>
                <label class="form-label">Student ID From</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('student_id_from'))
                        {{ $errors->first('student_id_from') }}
                    @else
                        Student ID From is required!
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mt-4">
            <div class="input-group form-label-group in-border">
                <input class="form-control" name="student_id_to" type="number" required>
                <label class="form-label">Student ID To</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('student_id_to'))
                        {{ $errors->first('student_id_to') }}
                    @else
                        Student ID To is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 mt-4">
            <div class="input-group form-label-group in-border">
                <textarea class="form-control @if ($errors->has('closing_reason')) is-invalid @endif" id="branchClosingReason"
                    placeholder="Branch Closing Reason" name="closing_reason">{{ old('closing_reason') }}</textarea>
                <label for="branchClosingReason" class="form-label">Closing Reason</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('closing_reason'))
                        {{ $errors->first('closing_reason') }}
                    @else
                        Closed Date is required!
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 text-end">
            <button class="btn btn-primary" type="submit">Submit form</button>
            <a href="{{ url('branches') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
        </div>
    </div>
</form>
