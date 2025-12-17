<form class="g-3 needs-validation" action="{{ route('branches.update', $branch->id) }}" method="POST"
    enctype="multipart/form-data" novalidate>
    @csrf
    @method('PATCH')
    <div class="row">

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="text" class="form-control" id="branchName" name="br_name" placeholder="Enter Branch Name"
                    value="{{ $branch->br_name }}" required>
                <label for="branchName" class="form-label">Branch</label>
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
                <select class="form-select" aria-label=".form-select-sm example" id="regions" name="region_id"
                    required>
                    <option value="">Please select</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" {{ $branch->region_id == $region->id ? 'selected' : '' }}>
                            {{ $region->region_name }}</option>
                    @endforeach
                </select>
                <label for="regions" class="form-label">Regions</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('region_id'))
                        {{ $errors->first('region_id') }}
                    @else
                        Please choose a regions.
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select" aria-label=".form-select-sm example" id="states" name="state_id"
                    required>
                    <option value="">Please select</option>
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" {{ $branch->state_id == $state->id ? 'selected' : '' }}>
                            {{ $state->state_name }}</option>
                    @endforeach
                </select>
                <label for="states" class="form-label">State / Province</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('state_id'))
                        {{ $errors->first('state_id') }}
                    @else
                        Please choose a State.
                    @endif
                </div>
            </div>
        </div>



        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select" aria-label="form-select-sm example" id="companyId" required
                    name="company_id">
                    <option value="">Please select</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ $branch->company_id == $company->id ? 'selected' : '' }}>{{ $company->company_name }}
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
                <select class="form-select" aria-label=".form-select-sm example" id="classGroupId"
                    name="class_group_id" required>
                    <option value="">Please select</option>
                    @foreach ($class_groups as $class_group)
                        <option value="{{ $class_group->id }}"
                            {{ $branch->class_group_id == $class_group->id ? 'selected' : '' }}>
                            {{ $class_group->name }}</option>
                    @endforeach
                </select>
                <label for="classGroupId" class="form-label">School Type</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('class_group_id'))
                        {{ $errors->first('class_group_id') }}
                    @else
                        Please select a school type.
                    @endif
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select" aria-label="form-select-sm example" id="fee_period_id"
                    name="fee_period_id" required>
                    <option value="" disabled selected>Select fee period</option>
                    @if (isset($feeperiods))
                        @foreach ($feeperiods as $feeperiod)
                            <option value="{{ $feeperiod->id }}"
                                {{ $branch->fee_period_id == $feeperiod->id ? 'selected' : '' }}>
                                {{ $feeperiod->period_name }}</option>
                        @endforeach
                    @endif
                </select>
                <label for="fee_period_id" class="form-label">Fee Period</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('fee_period_id'))
                        {{ $errors->first('fee_period_id') }}
                    @else
                        Please choose a fee period.
                    @endif
                </div>
            </div>
        </div> --}}
        <div class="col-md-4 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <select class="form-select" aria-label="form-select-sm example" id="building_type_id"
                    name="building_type_id" required>
                    <option value="" disabled selected>Select building type</option>
                    @if (isset($buildtypes))
                        @foreach ($buildtypes as $buildtype)
                            <option value="{{ $buildtype->id }}"
                                {{ $branch->building_type_id == $buildtype->id ? 'selected' : '' }}>
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
                <select class="form-select" aria-label="form-select-sm example" id="build_purpose"
                    name="build_purpose" required>
                    <option value="" disabled selected>Select build purpose</option>
                    <option value="Non-purpose" {{ $branch->build_purpose == 'Non-purpose' ? 'selected' : '' }}>
                        Non-purpose</option>
                    <option value="Purpose build" {{ $branch->build_purpose == 'Purpose build' ? 'selected' : '' }}>
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
                <select class="form-select" aria-label=".form-select-sm example" id="branchStatus" name="status">
                    <option value="1" {{ $branch->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $branch->status == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                <label for="branchStatus" class="form-label">Status</label>
            </div>
        </div>

        <div class="col-md-3 col-sm-12 mt-4">
            <div class="form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('branch_phone_number')) is-invalid @endif"
                    id="branch_phone_number" name="branch_phone_number" placeholder="Enter Branch Phone Number" value="{{ $branch->branch_phone_number }}"
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
                <input type="text" class="form-control" id="website" name="website" placeholder="Website URL"
                    value="{{ $branch->website }}">
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
                <input type="text" class="form-control" id="instagram" name="instagram" placeholder="Instagram Link"
                    value="{{ $branch->instagram }}">
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
                <input type="text" class="form-control" id="twitter" name="twitter" placeholder="Twitter Link"
                    value="{{ $branch->twitter }}">
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
                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date=""
                    value="{{ $branch->setup_date }}" name="setup_date" id="setupDate">
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                <label for="setupDate" class="form-label">Setup Date</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('setup_date'))
                        {{ $errors->first('setup_date') }}
                    @else
                        Setup date is required!
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-12 mt-4">
            <div class="input-group form-label-group in-border">
                <input type="text" class="form-control @if ($errors->has('closed_date')) is-invalid @endif"
                    data-provider="flatpickr" data-date-format="Y-m-d" data-altFormat="d-m-Y" data-deafult-date=""
                    value="{{ $branch->closed_date }}" name="closed_date" id="closingDate">
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
                <label for="closingDate" class="form-label">Closing Date</label>
                <div class="invalid-tooltip">
                    @if ($errors->has('closed_date'))
                        {{ $errors->first('closed_date') }}
                    @else
                        Closing date is required!
                    @endif
                </div>
            </div>
        </div>
        @if(isset($tax_types[0]))
        <div class="col-lg-4 col-md-6 mt-4">
            <div class="d-flex">
                @foreach ($tax_types as $tax_type)
                    <div class="col-md-6 form-check mb-2">
                        <label class="form-check-label" for="taxType{{ $tax_type->id }}">
                            <input class="form-check-input" type="checkbox" name="tax_types[]"
                                value="{{ $tax_type->id }}"
                                {{ in_array($tax_type->id, $branch_taxes) ? 'checked' : '' }}
                                id="taxType{{ $tax_type->id }}">
                            {{ $tax_type->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="col-lg-4 col-md-6 mt-4">
            <div class="input-group form-label-group in-border">
                <input class="form-control" value="{{$branch->branch_code}}" disabled>
                <label for="branchClosingReason" class="form-label">Branch Code</label>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mt-4">
            <div class="input-group form-label-group in-border">
                <input class="form-control" name="student_id_from" value="{{sprintf('%05u',$branch->student_id_from)}}">
                <label class="form-label">Student ID From</label>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mt-4">
            <div class="input-group form-label-group in-border">
                <input class="form-control" name="student_id_to" value="{{$branch->student_id_to}}">
                <label class="form-label">Student ID To</label>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="input-group form-label-group in-border">
                <textarea class="form-control" id="branchClosingReason" placeholder="Branch Closing Reason"
                    name="closing_reason">{{ $branch->closing_reason }}</textarea>
                <label for="branchClosingReason" class="form-label">Closing Reason</label>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 text-end">
            <button class="btn btn-primary" type="submit">Update form</button>
            <a href="{{ url('branches') }}" class="btn btn-light bg-gradient waves-effect waves-light">Cancel</a>
        </div>
    </div>
</form>
